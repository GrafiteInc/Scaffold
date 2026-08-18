# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## About

Grafite Scaffold is an opinionated Laravel 13 boilerplate meant to be forked and heavily
altered per-project (it's a starting point, not a maintained library consumers depend on).
It bundles first-party Grafite packages (`grafite/auth`, `grafite/forms`, `grafite/charts`,
`grafite/database`, `grafite/support`, `grafite/blacksmith`, `grafite/maintenance`,
`grafite/mission-control-laravel`) that define most of the app's conventions — read a
package's source in `vendor/grafite/*` when its behavior isn't obvious from usage alone.

## Commands

### PHP / backend
```bash
composer test              # PHPUnit (Unit + Feature) with coverage, enforces 50% min via coverage-checker.php
composer test-debug         # same, but emits HTML coverage report to test-html/
vendor/bin/phpunit --filter TestClassName          # run a single test class
vendor/bin/phpunit --filter testMethodName          # run a single test method
vendor/bin/phpunit tests/Feature/Controllers/TeamsControllerTest.php   # run one file

composer check-style        # Pint, dry-run
composer fix-style          # Pint, auto-fix
composer analyse            # Larastan/PHPStan (level 5, see phpstan.neon.dist)
composer insights           # phpinsights
```
There is no Pest — tests are plain PHPUnit `TestCase` classes. `tests/Architecture/` exists
but is currently empty. Dusk browser tests live in `tests/Browser/` and use `.env.dusk.local`.

### JS / frontend
```bash
npm run dev          # Vite dev server
npm run build         # Vite production build (runs purgecss + gzip/brotli compression, see vite.config.js)
npm run check-style   # ESLint (resources/js, .js/.vue)
npm run fix-style     # ESLint --fix
```

### Environment
`sail up` is the documented way to run the app locally (README). CI (`.github/workflows/`)
runs on PHP 8.4 against `.env.example`, so keep that file's keys in sync with any new config.

## Architecture

### Request flow: thin controllers, fat services
Controllers under `app/Http/Controllers/**` (organized by `Admin/`, `Api/`, `Ajax/`, `Auth/`,
`User/`) inject a single `App\Services\*Service` in the constructor and delegate all
create/update/destroy logic to it (see `TeamsController` + `TeamService`). One-off pieces of
business logic that don't warrant a full service live in `app/Actions/` as invokable classes
(e.g. `UpdateUserAvatar`, `ProcessUserTwoFactorSettings`). Controllers wrap service calls in
try/catch and use `abort_unless`/`Gate::allows` for authorization, not FormRequest-embedded
authorization — request validation lives in `app/Http/Requests/`.

### Models: capability via `Concerns` traits, not inheritance
`app/Models/Concerns/*` holds single-purpose traits (`HasRoles`, `HasPermissions`, `HasTeams`,
`HasSubscription`, `HasTwoFactor`, `HasActivity`, `HasAvatar`, `HasDevices`,
`HasCachedValues`, `DatabaseSearchable`, `Invitable`). `User` is the composition root and pulls
in most of them plus Grafite/Cashier/Sanctum traits — look there first to see what a model
"has." `DatabaseSearchable::search()` does a naive `LIKE` scan across every column returned by
`Schema::getColumnListing()`; it is intentionally schema-driven rather than an explicit
searchable-attributes list.

### Forms are declarative classes, not Blade forms
`app/View/Forms/*Form.php` extends Grafite `ModelForm`/`WizardForm` and defines fields via a
`fields()` method using `Grafite\Forms\Fields\*` (`Text::make(...)`, `Email::make(...)`, etc.),
plus config properties (`$columns`, `$orientation`, `$buttons`, `$withJsValidation`). A model
opts into a form by setting `public $form = SomeForm::class;` and using `HasForm`. Never
hand-roll a `<form>` in Blade for model CRUD — check `app/View/Forms/` and the Grafite Forms
docs/fields list first.

### Charts are declarative classes too
`app/View/Charts/*` extends the shared `CanChart` base and produces `grafite/charts` chart
config; paired Blade-facing wrappers live in `app/View/Components/Charts/`.

### Livewire is used sparingly
Only `app/Livewire/` (`Cart`, `NotificationBadge`, `UserSettings`) — most interactivity goes
through Grafite view components instead of full Livewire components. Check there before
reaching for Livewire on a new feature.

### Code generation via Artisan — prefer these over hand-writing boilerplate
Beyond stock Laravel generators, this app ships extra `make:*` commands (mostly from
`grafite/blacksmith`/`grafite/support`) that produce project-convention-correct stubs:
`make:model-form`, `make:wizard-form`, `make:modal-form`, `make:base-form`, `make:field`,
`make:chart`, `make:livewire-form`, `make:global-component`, `make:regex-class` (for
`yorcreative/laravel-scrubber` log scrubbing). **Run `php artisan list` / `php artisan help
make:X` before authoring a new Form, Chart, Field, or global component by hand** — the
generator stub already matches this repo's conventions and is cheaper than reconstructing the
pattern from scratch by reading multiple example files.

### Autoloaded helper functions
`app/Helpers/ActivityHelper.php` and `app/Helpers/NotificationHelper.php` are loaded globally
via Composer's `autoload.files` (see `composer.json`), not namespaced classes — they're global
functions available everywhere without a `use` statement.

### Config is split finely, one file per concern
`config/` has one file per package/feature (`billing.php`, `google2fa.php`, `honeypot.php`,
`mission-control.php`, `scramble.php`, `secure-headers.php`, `insights.php`, ...) rather than
grouping settings into `app.php`. `config/general.php` is the catch-all for app-specific
(non-package) settings. When looking for where a package's behavior is tuned, check
`config/<package-name>.php` before searching `.env`.

### API docs are generated, not hand-maintained
`dedoc/scramble` auto-generates OpenAPI docs from `app/Http/Controllers/Api/*` and
`app/Http/Resources/*` — don't write API doc comments/annotations by hand; ensure request/
response types (FormRequests, API Resources) are correct instead.

### Observability
`grafite/mission-control-laravel` (config: `mission-control.php`) centrally logs errors, slow
queries/pages, and queue activity to an external Mission Control instance — check this config
before assuming Sentry/Bugsnag-style tooling is in play.
