type ErrorBody = {
  message?: string;
  error?: string;
  errors?: Record<string, string[]>;
};

export class ApiError extends Error {
  readonly fieldErrors: Record<string, string[]>;

  constructor(message: string, fieldErrors: Record<string, string[]> = {}) {
    super(message);
    this.name = "ApiError";
    this.fieldErrors = fieldErrors;
  }
}

export async function getJson<T>(
  path: string,
  params: Record<string, string> = {},
): Promise<T> {
  const query = new URLSearchParams(params).toString();
  const url = `${import.meta.env.VITE_API_URL}${path}${query ? `?${query}` : ""}`;

  let response: Response;
  try {
    response = await fetch(url);
  } catch {
    throw new ApiError("A szerver nem érhető el.");
  }

  if (!response.ok) {
    throw await createApiError(response);
  }

  return (await response.json()) as T;
}

export function toApiError(error: unknown): ApiError {
  return error instanceof ApiError
    ? error
    : new ApiError("Váratlan hiba történt.");
}

async function createApiError(response: Response): Promise<ApiError> {
  const body = await parseErrorBody(response);
  const message =
    body.message ?? body.error ?? `Váratlan hiba (${response.status}).`;

  return new ApiError(message, body.errors);
}

async function parseErrorBody(response: Response): Promise<ErrorBody> {
  try {
    return (await response.json()) as ErrorBody;
  } catch {
    return {};
  }
}
