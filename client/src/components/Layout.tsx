import { Link } from "@tanstack/react-router";
import type { ReactNode } from "react";
import { UnitSystemSwitch } from "./UnitSystemSwitch.tsx";

type LayoutProps = {
  children: ReactNode;
};

export function Layout({ children }: LayoutProps) {
  return (
    <div className="min-h-screen bg-slate-50 text-slate-900">
      <header className="border-b border-slate-200 bg-white">
        <div className="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-4 py-4">
          <h1 className="text-lg font-bold">Időjárás kimutatás</h1>
          <div className="flex flex-wrap items-center gap-3">
            <nav className="flex gap-1 rounded-lg bg-slate-100 p-1 text-sm font-medium">
              <NavLink to="/city">Város</NavLink>
              <NavLink to="/region">Régió</NavLink>
            </nav>
            <UnitSystemSwitch />
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-5xl px-4 py-8">{children}</main>
    </div>
  );
}

type NavLinkProps = {
  to: "/city" | "/region";
  children: string;
};

function NavLink({ to, children }: NavLinkProps) {
  return (
    <Link
      to={to}
      className="rounded-md px-4 py-1.5 transition-colors"
      activeProps={{ className: "bg-white text-sky-700 shadow-sm" }}
      inactiveProps={{ className: "text-slate-600 hover:text-slate-900" }}
    >
      {children}
    </Link>
  );
}
