import { type ReactNode, useMemo, useState } from "react";
import { UnitSystemContext } from "../contexts/UnitSystemContext.ts";
import type { UnitSystem } from "../utils/units.ts";

type UnitSystemProviderProps = {
  children: ReactNode;
};

export function UnitSystemProvider({ children }: UnitSystemProviderProps) {
  const [unitSystem, setUnitSystem] = useState<UnitSystem>("metric");
  const value = useMemo(() => ({ unitSystem, setUnitSystem }), [unitSystem]);

  return <UnitSystemContext value={value}>{children}</UnitSystemContext>;
}
