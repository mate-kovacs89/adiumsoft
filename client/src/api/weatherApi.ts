import type {
  City,
  CityDailyWeather,
  DateRange,
  RegionDailyWeather,
} from "../types/weather.ts";
import { getJson } from "./httpClient.ts";

export type CityWeatherQuery = DateRange & {
  cityId: number;
};

export type RegionWeatherQuery = DateRange & {
  region: string;
};

export function getCities(): Promise<City[]> {
  return getJson<City[]>("/cities");
}

export function getCityWeather({
  cityId,
  from,
  to,
}: CityWeatherQuery): Promise<CityDailyWeather[]> {
  return getJson<CityDailyWeather[]>("/weathers/cities", {
    cityId: String(cityId),
    from,
    to,
  });
}

export function getRegionWeather({
  region,
  from,
  to,
}: RegionWeatherQuery): Promise<RegionDailyWeather[]> {
  return getJson<RegionDailyWeather[]>("/weathers/regions", {
    region,
    from,
    to,
  });
}
