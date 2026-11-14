import { RouterProvider } from "@tanstack/react-router";
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { UnitSystemProvider } from "./components/UnitSystemProvider.tsx";
import "./index.css";
import { router } from "./router.tsx";

createRoot(document.getElementById("root")!).render(
  <StrictMode>
    <UnitSystemProvider>
      <RouterProvider router={router} />
    </UnitSystemProvider>
  </StrictMode>,
);
