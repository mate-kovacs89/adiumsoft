import type { DateRange } from "../types/weather.ts";
import { Field } from "./Field.tsx";
import { inputClassName } from "./styles.ts";

type DateRangeFieldsProps = {
  value: DateRange;
  onChange: (value: DateRange) => void;
};

export function DateRangeFields({ value, onChange }: DateRangeFieldsProps) {
  return (
    <>
      <Field label="Kezdő dátum" htmlFor="from">
        <input
          id="from"
          type="date"
          required
          value={value.from}
          max={value.to || undefined}
          onChange={(event) => onChange({ ...value, from: event.target.value })}
          className={inputClassName}
        />
      </Field>
      <Field label="Záró dátum" htmlFor="to">
        <input
          id="to"
          type="date"
          required
          value={value.to}
          min={value.from || undefined}
          onChange={(event) => onChange({ ...value, to: event.target.value })}
          className={inputClassName}
        />
      </Field>
    </>
  );
}
