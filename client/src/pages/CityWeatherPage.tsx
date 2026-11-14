import { useMemo, useState } from "react";
import { getCityWeather } from "../api/weatherApi.ts";
import { Field } from "../components/Field.tsx";
import { PageHeader } from "../components/PageHeader.tsx";
import { inputClassName } from "../components/styles.ts";
import { WeatherQueryForm } from "../components/WeatherQueryForm.tsx";
import { WeatherResult } from "../components/WeatherResult.tsx";
import { useRequest } from "../hooks/useRequest.ts";
import type { City } from "../types/weather.ts";
import { groupCitiesByRegion } from "../utils/cities.ts";
import { defaultDateRange } from "../utils/dateRange.ts";

type CityWeatherPageProps = {
  cities: City[];
};

export function CityWeatherPage({ cities }: CityWeatherPageProps) {
  const citiesByRegion = useMemo(() => groupCitiesByRegion(cities), [cities]);
  const [cityId, setCityId] = useState(() => cities[0]?.id ?? 0);
  const [dateRange, setDateRange] = useState(defaultDateRange);
  const { state, run } = useRequest(getCityWeather);

  return (
    <section className="space-y-6">
      <PageHeader
        title="Város időjárása"
        description="Egy város napi időjárási adatai a kiválasztott időszakban."
      />
      <WeatherQueryForm
        dateRange={dateRange}
        onDateRangeChange={setDateRange}
        onSubmit={() => void run({ cityId, ...dateRange })}
        isLoading={state.status === "loading"}
      >
        <Field label="Város" htmlFor="city">
          <select
            id="city"
            value={cityId}
            onChange={(event) => setCityId(Number(event.target.value))}
            className={inputClassName}
          >
            {[...citiesByRegion].map(([region, regionCities]) => (
              <optgroup key={region} label={region}>
                {regionCities.map((city) => (
                  <option key={city.id} value={city.id}>
                    {city.name}
                  </option>
                ))}
              </optgroup>
            ))}
          </select>
        </Field>
      </WeatherQueryForm>
      <WeatherResult state={state} />
    </section>
  );
}
