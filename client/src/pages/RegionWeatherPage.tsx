import { useMemo, useState } from "react";
import { getRegionWeather } from "../api/weatherApi.ts";
import { Field } from "../components/Field.tsx";
import { PageHeader } from "../components/PageHeader.tsx";
import { inputClassName } from "../components/styles.ts";
import { WeatherQueryForm } from "../components/WeatherQueryForm.tsx";
import { WeatherResult } from "../components/WeatherResult.tsx";
import { useRequest } from "../hooks/useRequest.ts";
import type { City } from "../types/weather.ts";
import { getRegions } from "../utils/cities.ts";
import { defaultDateRange } from "../utils/dateRange.ts";

type RegionWeatherPageProps = {
  cities: City[];
};

export function RegionWeatherPage({ cities }: RegionWeatherPageProps) {
  const regions = useMemo(() => getRegions(cities), [cities]);
  const [region, setRegion] = useState(() => regions[0] ?? "");
  const [dateRange, setDateRange] = useState(defaultDateRange);
  const { state, run } = useRequest(getRegionWeather);

  return (
    <section className="space-y-6">
      <PageHeader
        title="Régió időjárása"
        description="A régió városainak napi átlagolt időjárási adatai a kiválasztott időszakban."
      />
      <WeatherQueryForm
        dateRange={dateRange}
        onDateRangeChange={setDateRange}
        onSubmit={() => void run({ region, ...dateRange })}
        isLoading={state.status === "loading"}
      >
        <Field label="Régió" htmlFor="region">
          <select
            id="region"
            value={region}
            onChange={(event) => setRegion(event.target.value)}
            className={inputClassName}
          >
            {regions.map((name) => (
              <option key={name} value={name}>
                {name}
              </option>
            ))}
          </select>
        </Field>
      </WeatherQueryForm>
      <WeatherResult state={state} />
    </section>
  );
}
