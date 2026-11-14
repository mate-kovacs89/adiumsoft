import type { ApiError } from "../api/httpClient.ts";

type ErrorAlertProps = {
  error: ApiError;
};

export function ErrorAlert({ error }: ErrorAlertProps) {
  const fieldMessages = Object.entries(error.fieldErrors).flatMap(
    ([field, messages]) =>
      messages.map((message) => ({ key: `${field}:${message}`, message })),
  );

  return (
    <div
      role="alert"
      className="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
    >
      <p className="font-medium">{error.message}</p>
      {fieldMessages.length > 0 && (
        <ul className="mt-1 list-inside list-disc">
          {fieldMessages.map(({ key, message }) => (
            <li key={key}>{message}</li>
          ))}
        </ul>
      )}
    </div>
  );
}
