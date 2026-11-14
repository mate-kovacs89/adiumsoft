import { useContext } from "react";
import {
  UnitSystemContext,
  type UnitSystemContextValue,
} from "../contexts/UnitSystemContext.ts";

export function useUnitSystem(): UnitSystemContextValue {
  const context = useContext(UnitSystemContext);

  if (context === null) {
    throw new Error("useUnitSystem must be used within a UnitSystemProvider.");
  }

  return context;
}
