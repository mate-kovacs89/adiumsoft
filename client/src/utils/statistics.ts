export function median(values: readonly number[]): number | null {
  if (values.length === 0) {
    return null;
  }

  const sorted = [...values].sort((a, b) => a - b);
  const middle = Math.floor(sorted.length / 2);

  return sorted.length % 2 === 0
    ? (sorted[middle - 1] + sorted[middle]) / 2
    : sorted[middle];
}

// Rounded to display precision, so float noise doesn't make all values unique.
export function modes(
  values: readonly number[],
  fractionDigits: number,
): number[] {
  const factor = 10 ** fractionDigits;
  const counts = new Map<number, number>();

  for (const value of values) {
    const rounded = Math.round(value * factor) / factor;
    counts.set(rounded, (counts.get(rounded) ?? 0) + 1);
  }

  let maxCount = 0;
  for (const count of counts.values()) {
    maxCount = Math.max(maxCount, count);
  }

  if (maxCount <= 1 && values.length > 1) {
    return [];
  }

  return [...counts]
    .filter(([, count]) => count === maxCount)
    .map(([value]) => value)
    .sort((a, b) => a - b);
}
