import type { Unit } from "./units.ts";

const dateFormat = new Intl.DateTimeFormat("hu-HU", { timeZone: "UTC" });

export function formatMeasurement(value: number, unit: Unit): string {
  return `${formatNumber(value, unit.fractionDigits)} ${unit.symbol}`;
}

export function formatDate(isoDate: string): string {
  return dateFormat.format(new Date(`${isoDate}T00:00:00Z`));
}

function formatNumber(value: number, fractionDigits: number): string {
  const factor = 10 ** fractionDigits;
  // "|| 0" avoids showing "-0,0".
  const rounded = Math.round(value * factor) / factor || 0;

  return rounded.toLocaleString("hu-HU", {
    minimumFractionDigits: fractionDigits,
    maximumFractionDigits: fractionDigits,
  });
}
