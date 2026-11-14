export type UnitSystem = "metric" | "imperial";

export type Unit = {
  symbol: string;
  fractionDigits: number;
  convert: (metricValue: number) => number;
};

export const temperatureUnits: Record<UnitSystem, Unit> = {
  metric: {
    symbol: "°C",
    fractionDigits: 1,
    convert: (celsius) => celsius,
  },
  imperial: {
    symbol: "°F",
    fractionDigits: 1,
    convert: (celsius) => (celsius * 9) / 5 + 32,
  },
};

export const precipitationUnits: Record<UnitSystem, Unit> = {
  metric: {
    symbol: "mm",
    fractionDigits: 1,
    convert: (millimetres) => millimetres,
  },
  imperial: {
    symbol: "in",
    fractionDigits: 2,
    convert: (millimetres) => millimetres / 25.4,
  },
};
