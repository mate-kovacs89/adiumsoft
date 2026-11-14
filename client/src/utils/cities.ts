import type { City } from "../types/weather.ts";

const collator = new Intl.Collator("hu-HU");

export function getRegions(cities: readonly City[]): string[] {
  return [...new Set(cities.map((city) => city.region))].sort(collator.compare);
}

export function groupCitiesByRegion(
  cities: readonly City[],
): Map<string, City[]> {
  const groups = new Map<string, City[]>();

  for (const region of getRegions(cities)) {
    groups.set(
      region,
      cities
        .filter((city) => city.region === region)
        .sort((a, b) => collator.compare(a.name, b.name)),
    );
  }

  return groups;
}
