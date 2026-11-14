import type { RequestState } from "../hooks/useRequest.ts";
import type { DailyWeather } from "../types/weather.ts";
import { ErrorAlert } from "./ErrorAlert.tsx";
import { WeatherTable } from "./WeatherTable.tsx";

type WeatherResultProps = {
  state: RequestState<DailyWeather[]>;
};

export function WeatherResult({ state }: WeatherResultProps) {
  switch (state.status) {
    case "idle":
      return (
        <Message>Válassz paramétereket, majd kattints a lekérdezésre.</Message>
      );
    case "loading":
      return <Message>Adatok betöltése…</Message>;
    case "error":
      return <ErrorAlert error={state.error} />;
    case "success":
      return state.data.length === 0 ? (
        <Message>A megadott időszakra nincs adat.</Message>
      ) : (
        <WeatherTable rows={state.data} />
      );
  }
}

function Message({ children }: { children: string }) {
  return (
    <p className="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">
      {children}
    </p>
  );
}
