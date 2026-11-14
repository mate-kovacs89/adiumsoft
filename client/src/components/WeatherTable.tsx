import { useMemo } from "react";
import { useUnitSystem } from "../hooks/useUnitSystem.ts";
import type { DailyWeather } from "../types/weather.ts";
import { formatDate, formatMeasurement } from "../utils/format.ts";
import { median, modes } from "../utils/statistics.ts";
import {
  type Unit,
  type UnitSystem,
  precipitationUnits,
  temperatureUnits,
} from "../utils/units.ts";
import { averageTemperature } from "../utils/weather.ts";

type Column = {
  label: string;
  getMetricValue: (row: DailyWeather) => number;
  units: Record<UnitSystem, Unit>;
};

const columns: Column[] = [
  {
    label: "Minimum hőmérséklet",
    getMetricValue: (row) => row.minTemp,
    units: temperatureUnits,
  },
  {
    label: "Maximum hőmérséklet",
    getMetricValue: (row) => row.maxTemp,
    units: temperatureUnits,
  },
  {
    label: "Átlaghőmérséklet",
    getMetricValue: averageTemperature,
    units: temperatureUnits,
  },
  {
    label: "Csapadék",
    getMetricValue: (row) => row.precipitation,
    units: precipitationUnits,
  },
];

type WeatherTableProps = {
  rows: readonly DailyWeather[];
};

export function WeatherTable({ rows }: WeatherTableProps) {
  const { unitSystem } = useUnitSystem();

  const tableColumns = useMemo(
    () =>
      columns.map((column) => {
        const unit = column.units[unitSystem];
        const values = rows.map((row) =>
          unit.convert(column.getMetricValue(row)),
        );

        return {
          label: column.label,
          unit,
          values,
          median: median(values),
          modes: modes(values, unit.fractionDigits),
        };
      }),
    [rows, unitSystem],
  );

  return (
    <div className="max-h-[70vh] overflow-auto rounded-xl border border-slate-200 bg-white shadow-sm">
      <table className="w-full text-sm">
        <thead className="sticky top-0 bg-slate-100 text-left text-slate-700">
          <tr>
            <th scope="col" className="px-4 py-3 font-semibold">
              Dátum
            </th>
            {tableColumns.map((column) => (
              <th
                key={column.label}
                scope="col"
                className="px-4 py-3 text-right font-semibold"
              >
                {column.label}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="divide-y divide-slate-100">
          {rows.map((row, rowIndex) => (
            <tr key={row.date} className="hover:bg-sky-50">
              <td className="px-4 py-2 whitespace-nowrap text-slate-600">
                {formatDate(row.date)}
              </td>
              {tableColumns.map((column) => (
                <td
                  key={column.label}
                  className="px-4 py-2 text-right whitespace-nowrap tabular-nums"
                >
                  {formatMeasurement(column.values[rowIndex], column.unit)}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
        <tfoot className="sticky bottom-0 border-t-2 border-slate-300 bg-slate-100 text-slate-800">
          <tr>
            <th scope="row" className="px-4 py-2 text-left font-semibold">
              Medián
            </th>
            {tableColumns.map((column) => (
              <td
                key={column.label}
                className="px-4 py-2 text-right tabular-nums"
              >
                {column.median === null
                  ? "–"
                  : formatMeasurement(column.median, column.unit)}
              </td>
            ))}
          </tr>
          <tr>
            <th scope="row" className="px-4 py-2 text-left font-semibold">
              Módusz
            </th>
            {tableColumns.map((column) => (
              <td
                key={column.label}
                className="px-4 py-2 text-right tabular-nums"
              >
                {column.modes.length === 0
                  ? "–"
                  : column.modes
                      .map((value) => formatMeasurement(value, column.unit))
                      .join(", ")}
              </td>
            ))}
          </tr>
        </tfoot>
      </table>
    </div>
  );
}
