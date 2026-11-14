import { createContext } from "react";
import type { UnitSystem } from "../utils/units.ts";

export type UnitSystemContextValue = {
  unitSystem: UnitSystem;
  setUnitSystem: (unitSystem: UnitSystem) => void;
};

export const UnitSystemContext = createContext<UnitSystemContextValue | null>(
  null,
);
