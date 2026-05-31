# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- `composer test` — run the full PHPUnit suite (`vendor/bin/phpunit tests`)
- `vendor/bin/phpunit --filter testMethodName tests/Unit/ProblemTest.php` — run a single test or test file
- `composer phpstan` — run static analysis (PHPStan level 9 over `src`)

CI runs PHPStan on PHP 8.3 and the test suite on PHP 8.3 + 8.4. Both must pass.

## Architecture

`shredio/problem` is a small, dependency-light library (only `psr/log`; `symfony/translation` is dev-only) for building structured, serializable error payloads — think RFC 7807-style "problem details" for API responses. Everything is immutable value objects assembled through fluent builders.

The data model is a fixed three-level hierarchy:

1. **`Problem`** (root, `final readonly`, `JsonSerializable`) — carries `code`, `message`, `fatal`, and an optional `ProblemDetails`. `toArray()`/`jsonSerialize()` produce the wire format; `toLogger()` forwards the problem to a PSR logger, choosing the severity based on `fatal`.
2. **`ProblemDetails`** — an ordered collection of `ProblemDetail`. It silently drops any detail whose `isValid()` is false on `add()`/construct, and filters `isSensitive()` details during sanitized serialization.
3. **`ProblemDetail`** (interface) — a typed block, e.g. `ValidationProblemDetail` (`@type: Validation`, carries a `ValidationSeverity`) and `BadRequestProblemDetail` (`@type: BadRequest`). Each wraps a list of **`Violation`** (`FieldViolation` for a named field, `GlobalViolation` for form-level errors).

### The sanitize / stringify contract

This is the central cross-cutting concern — every `toArray()` down the tree threads two arguments:

- **`bool $sanitize`** (default `true`): production-safe output. When true, sensitive details are skipped entirely (`ProblemDetails`), and `VerboseMessage` resolves to its public `message` rather than its `debugMessage`. Pass `false` for internal/debug contexts (e.g. `toLogger()` calls `toArray(false)`).
- **`(callable(Stringable): string)|null $stringify`**: optional hook to render `Stringable` messages. When omitted, `TranslatableMessage` is resolved via `strtr`, otherwise plain `(string)` casting is used (see `ProblemHelper::stringifyMessages`).

When adding a new `ProblemDetail` or `Violation`, propagate both parameters unchanged through `toArray()`.

### Builders

`ValidationProblemBuilder` (422, groups violations by `ValidationSeverity`) and `BadRequestProblemBuilder` (400, flat list) are the intended entry points. Both expose `addFieldViolation()` / `addViolation()` / `addList()`, plus `build()` and `buildNullable()` (returns `null` when no violations were added — use this to conditionally emit a problem). `ValidationProblemBuilder` infers `fatal` from the presence of `Error`-severity violations unless `setFatal()` overrides it.

### Messages

`VerboseMessage` is a `Stringable` pair of `message` (public) + `debugMessage` (internal), selected by the `$sanitize` flag. `ProblemHelper` also offers presentation helpers: `describeValue()` (safe, truncated rendering of arbitrary values for error text), `humanImplode()`, and `stringifyMessages()`.

`Violation::debugString($separator)` produces a human-readable, never-sanitized rendering for logs/exceptions.

`ViolationAwareException` is the interface for exceptions that can surface their own `list<Violation>`.

## Conventions

- All value objects are `final readonly`; builders are `final` (mutable by design).
- `@type` keys and detail `Type` constants use PascalCase; enum cases use PascalCase with lowercase string backing values.
- Tests live under `tests/Unit/`, namespace `Tests\`, extending `Tests\TestCase`. They assert on the exact `toArray()` shape — when changing serialization, update the expected arrays.
