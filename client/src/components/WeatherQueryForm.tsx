import type { FormEvent, ReactNode } from "react";
import type { DateRange } from "../types/weather.ts";
import { DateRangeFields } from "./DateRangeFields.tsx";

type WeatherQueryFormProps = {
  dateRange: DateRange;
  onDateRangeChange: (value: DateRange) => void;
  onSubmit: () => void;
  isLoading: boolean;
  children: ReactNode;
};

export function WeatherQueryForm({
  dateRange,
  onDateRangeChange,
  onSubmit,
  isLoading,
  children,
}: WeatherQueryFormProps) {
  const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    onSubmit();
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
    >
      <div className="grid gap-4 sm:grid-cols-3">
        {children}
        <DateRangeFields value={dateRange} onChange={onDateRangeChange} />
      </div>
      <button
        type="submit"
        disabled={isLoading}
        className="mt-5 rounded-lg bg-sky-600 px-5 py-2 font-semibold text-white shadow-sm hover:bg-sky-700 focus:ring-2 focus:ring-sky-300 focus:outline-none disabled:cursor-wait disabled:opacity-60"
      >
        {isLoading ? "Betöltés…" : "Lekérdezés"}
      </button>
    </form>
  );
}
