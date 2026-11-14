import { useUnitSystem } from "../hooks/useUnitSystem.ts";
import type { UnitSystem } from "../utils/units.ts";

const options: { value: UnitSystem; label: string; title: string }[] = [
  { value: "metric", label: "°C, mm", title: "SI mértékegységek" },
  { value: "imperial", label: "°F, in", title: "Angolszász mértékegységek" },
];

export function UnitSystemSwitch() {
  const { unitSystem, setUnitSystem } = useUnitSystem();

  return (
    <div
      role="group"
      aria-label="Mértékegységek"
      className="flex gap-1 rounded-lg bg-slate-100 p-1 text-sm font-medium"
    >
      {options.map((option) => {
        const isActive = option.value === unitSystem;
        return (
          <button
            key={option.value}
            type="button"
            title={option.title}
            aria-pressed={isActive}
            onClick={() => setUnitSystem(option.value)}
            className={`cursor-pointer rounded-md px-3 py-1.5 transition-colors ${
              isActive
                ? "bg-white text-sky-700 shadow-sm"
                : "text-slate-600 hover:text-slate-900"
            }`}
          >
            {option.label}
          </button>
        );
      })}
    </div>
  );
}
