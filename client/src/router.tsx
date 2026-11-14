import {
  Link,
  Outlet,
  createHashHistory,
  createRootRoute,
  createRoute,
  createRouter,
  redirect,
} from "@tanstack/react-router";
import { toApiError } from "./api/httpClient.ts";
import { getCities } from "./api/weatherApi.ts";
import { ErrorAlert } from "./components/ErrorAlert.tsx";
import { Layout } from "./components/Layout.tsx";
import { CityWeatherPage } from "./pages/CityWeatherPage.tsx";
import { RegionWeatherPage } from "./pages/RegionWeatherPage.tsx";

const rootRoute = createRootRoute({
  loader: () => getCities(),
  staleTime: Infinity,
  component: () => (
    <Layout>
      <Outlet />
    </Layout>
  ),
  pendingComponent: () => (
    <Layout>
      <p className="text-sm text-slate-500">Városok betöltése…</p>
    </Layout>
  ),
  errorComponent: ({ error }) => (
    <Layout>
      <ErrorAlert error={toApiError(error)} />
    </Layout>
  ),
  notFoundComponent: () => (
    <p className="text-sm text-slate-600">
      Az oldal nem található.{" "}
      <Link to="/city" className="font-medium text-sky-700 hover:underline">
        Vissza a kezdőlapra
      </Link>
    </p>
  ),
});

const indexRoute = createRoute({
  getParentRoute: () => rootRoute,
  path: "/",
  beforeLoad: () => {
    throw redirect({ to: "/city" });
  },
});

const cityRoute = createRoute({
  getParentRoute: () => rootRoute,
  path: "/city",
  component: function CityRoute() {
    return <CityWeatherPage cities={rootRoute.useLoaderData()} />;
  },
});

const regionRoute = createRoute({
  getParentRoute: () => rootRoute,
  path: "/region",
  component: function RegionRoute() {
    return <RegionWeatherPage cities={rootRoute.useLoaderData()} />;
  },
});

const routeTree = rootRoute.addChildren([indexRoute, cityRoute, regionRoute]);

// Hash URLs: the prod nginx config has no SPA fallback.
export const router = createRouter({
  routeTree,
  history: createHashHistory(),
});

declare module "@tanstack/react-router" {
  interface Register {
    router: typeof router;
  }
}
