import { useCallback, useRef, useState } from "react";
import { type ApiError, toApiError } from "../api/httpClient.ts";

export type RequestState<T> =
  | { status: "idle" }
  | { status: "loading" }
  | { status: "success"; data: T }
  | { status: "error"; error: ApiError };

export function useRequest<TArgs extends unknown[], TResult>(
  request: (...args: TArgs) => Promise<TResult>,
) {
  const [state, setState] = useState<RequestState<TResult>>({
    status: "idle",
  });
  const latestRequestId = useRef(0);

  const run = useCallback(
    async (...args: TArgs) => {
      const requestId = ++latestRequestId.current;
      setState({ status: "loading" });

      try {
        const data = await request(...args);
        if (requestId === latestRequestId.current) {
          setState({ status: "success", data });
        }
      } catch (error) {
        if (requestId === latestRequestId.current) {
          setState({ status: "error", error: toApiError(error) });
        }
      }
    },
    [request],
  );

  return { state, run };
}
