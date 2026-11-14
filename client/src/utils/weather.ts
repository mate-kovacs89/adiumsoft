import type { DailyWeather } from "../types/weather.ts";

export function averageTemperature({ minTemp, maxTemp }: DailyWeather): number {
  return (minTemp + maxTemp) / 2;
}
