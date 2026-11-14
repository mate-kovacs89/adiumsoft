# Adatbázis struktúra módosítás javaslatok

<!-- ez a fájl egy nem kötelező feladat része -->

## 1. Foreign key a mérési táblák és a városok között

A `minimum_temperatures`, a `maximum_temperatures` és a `precipitation` tábla
`city_id` oszlopa hivatkozzon a `cities.id` oszlopra.

**Miért:**

- Most semmi nem akadályozza meg, hogy egy mérés nem létező városhoz tartozzon,
  vagy hogy egy város törlése után "árva" mérések maradjanak.
- A kapcsolat a sémából is látszik, nem csak a lekérdezésekből.

## 2. Összetett elsődleges kulcs: `(city_id, date)`

A mérési táblákon jelenleg semmilyen kulcs vagy index nincs.

**Miért:**

- **Egyediség:** egy város egy napjára csak egy mérés tartozhat. Kulcs nélkül
  ugyanaz a nap kétszer is bekerülhet, és akkor az átlagok torzulnak.
- **Teljesítmény:** a lekérdezések pontosan ezt a két oszlopot használják
  (`WHERE city_id = ? AND date BETWEEN ? AND ?`). Index nélkül minden kérés a
  teljes táblát (közel 450 000 sort) végigolvassa, és egy régió teljes
  időszakára vonatkozó lekérdezés kb. 3 másodpercig fut.
- Az oszlopok sorrendje számít: előbb a `city_id` (egyenlőség), utána a `date`
  (tartomány). Így ugyanaz az index szolgálja ki a városi és a régiós
  lekérdezést is.

## 3. `DATE` típus a `bigint` helyett

A `date` oszlop jelenleg `bigint`, `20230101` alakú számokkal.

**Miért:**

- A `bigint` érvénytelen dátumot is elfogad (`20230230`, `99999999`), a `DATE`
  nem.
- Használhatók a MySQL dátumfüggvényei (`YEAR()`, `MONTH()`, `DATE_ADD()`,
  `DATEDIFF()`), például havi vagy éves összesítéshez.
- Kisebb helyet foglal: 3 bájt 8 helyett, soronként.
- Az alkalmazásnak nem kell a `Ymd` számformátum és az ISO dátum között
  átalakítania.

## 4. Egy mérési tábla a három helyett

A `minimum_temperatures`, a `maximum_temperatures` és a `precipitation` tábla
helyett egy `daily_weather` tábla, oszloponként egy mért értékkel.

**Miért:**

- A három tábla ugyanazt az eseményt írja le: egy város egy napjának mérését,
  ugyanazzal a kulccsal (`city_id`, `date`).
- Eltűnik a háromszoros `JOIN` minden lekérdezésből, így azok egyszerűbbek és
  gyorsabbak lesznek.
- A séma garantálja, hogy egy nap adatai együtt léteznek. Most csak az adat
  véletlenül teljes: ha valamelyik táblából hiányozna egy nap, az `INNER JOIN`
  miatt az egész nap csendben kimaradna az eredményből.
- Egy helyen kell kulcsot, foreign key-t és megszorításokat karbantartani,
  nem háromban.

## 5. Hiányzó értékek `NULL`-ként a `-999` helyett

Az adatokban a `-999` érték a hiányzó mérést jelöli.

Az érintett városok: Keszthely (1955–1957), Nyíregyháza (1922–1945), Miskolc,
Sopron és a 10-es azonosítójú város.

**Miért:**

- A `-999` számként tárolva bekerül a számításokba. A Dunántúl régió átlagos
  minimum hőmérséklete például 1955. január 1-jén így -253 °C-nak jön ki, mert
  Keszthely `-999`-es értéke a valódi mérésekkel együtt átlagolódik.
- A `NULL` az SQL-ben pontosan a "nincs érték" jelentést hordozza. Az `AVG()`,
  a `MIN()` és a `MAX()` automatikusan kihagyja, így a régiós átlag a ténylegesen
  mért városokból számolódik.
- A mágikus számot minden lekérdezésnek és alkalmazásnak ismernie kellene. A
  `NULL` esetén ez a tudás a sémában van.

Ehhez az érték oszlopoknak `NULL`-ozhatónak kell lenniük, a meglévő adatot pedig
át kell alakítani.


## 6. `DECIMAL(4,1)` a `float` helyett

A mért értékek jelenleg `float` típusúak, pedig mindegyik egy tizedesjegy
pontosságú (pl. `3.4`, `115.4`).

**Miért:**

- A `float` bináris közelítés, a legtöbb tizedes törtet nem tudja pontosan
  tárolni. Budapest 2023. január 1-jei minimuma például `3.4`, a MySQL mégis
  `3.4000000953674316`-ként számol vele.
- Emiatt a pontos egyezés nem működik: a
  `WHERE city_id = 1 AND date = 20230101 AND min_temp = 3.4` feltétel **0 sort**
  ad vissza, pedig a tárolt érték `3.4`.
- Az összesítések is a közelített értékkel számolnak: az `AVG(min_temp)` egyetlen
  `3.4`-es értékre `3.4000000953674316`-ot ad, és ez a zaj kerül ki az API-n is.
- A `DECIMAL` pontosan azt tárolja, amit beírtak, és a számítások is pontosak.
- A `DECIMAL(4,1)` −999,9 és 999,9 közötti értéket enged. Ez a valós
  adattartományt (−30,2 °C és 41,3 °C között, csapadék legfeljebb 115,4 mm)
  bőven lefedi.

## Összefoglalva: a javasolt tábla

```sql
CREATE TABLE daily_weather (
    city_id       INT          NOT NULL,
    date          DATE         NOT NULL,
    min_temp      DECIMAL(4,1) NULL COMMENT "Minimum hőmérséklet, Celsius fokban",
    max_temp      DECIMAL(4,1) NULL COMMENT "Maximum hőmérséklet, Celsius fokban",
    precipitation DECIMAL(4,1) NULL COMMENT "Csapadék, milliméterben",
    PRIMARY KEY (city_id, date),
    FOREIGN KEY (city_id) REFERENCES cities (id)
);
```
