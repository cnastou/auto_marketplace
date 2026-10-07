# Copilot instructions for this Laravel app

This repository is a Laravel application for managing transport requests and bids. Follow the project conventions in AGENTS.md and keep changes aligned with the existing Laravel patterns in this app.

## Core rules

- Treat this as a Laravel app running on PHP 8.5.
- Follow the existing project structure under app/, routes/, resources/, database/, and tests/.
- Prefer existing patterns and naming before introducing new abstractions.
- Use Laravel conventions over custom patterns: Eloquent models, route names, validation, factories, and feature tests.
- Keep changes minimal and targeted; do not create new base folders unless there is a clear need.

## PHP and Laravel standards

- Use PHP 8 syntax and explicit type hints.
- Use curly braces for control structures even on single-line blocks.
- Prefer descriptive names like isRegisteredForDiscounts over vague names.
- Prefer named routes and Laravel helpers when generating URLs.
- Default to feature tests for app behavior and use factories to create models in tests.
- Prefer existing validation patterns and request classes when they already exist.

## Testing expectations

- Add or update tests for behavior changes when meaningful coverage is relevant.
- Prefer small, real tests over mock-heavy tests.
- Use `php artisan make:test --phpunit` for PHPUnit tests and keep names aligned with the project.
- Run the narrowest relevant tests for the behavior being changed.
- Common verification commands:
  - `php artisan test --filter=SomeFeatureTest`
  - `vendor/bin/phpunit tests/Feature/SomeFeatureTest.php`
  - `vendor/bin/pint --dirty --format agent`

## App domain guidance

This app manages transport requests and bids. When working in this domain:

- Model ownership and authorization matter; check user_id relationships and route authorization.
- Accepting a bid should update both the bid status and the related transport request status consistently.
- Respect the existing request flow and status lifecycle instead of inventing parallel states.
- When in doubt, inspect the neighboring model, factory, and feature tests before changing logic.

## Frontend and UI

- If a UI change is not visible, it may require `npm run build`, `npm run dev`, or `composer run dev`.
- Keep Blade templates and component usage aligned with the existing app styling.

## Response style

- Keep suggestions brief and implementation-focused.
- Favor practical Laravel fixes over broad refactors.
- Do not add documentation files unless explicitly requested.
