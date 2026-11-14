export type City = {
  id: number;
  name: string;
  region: string;
};

export type DailyWeather = {
  date: string;
  minTemp: number;
  maxTemp: number;
  precipitation: number;
};

export type CityDailyWeather = DailyWeather & {
  cityId: number;
  cityName: string;
};

export type RegionDailyWeather = DailyWeather & {
  region: string;
};

export type DateRange = {
  from: string;
  to: string;
};
