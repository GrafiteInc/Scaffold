![Grafite Scaffold](https://docs.grafite.ca/img/grafite_scaffold.png)

[![Laravel Tests](https://github.com/grafiteinc/scaffold/workflows/Tests/badge.svg)](https://github.com/grafiteinc/scaffold/actions?query=workflow%3A%22Tests%22)
[![Maintainability](https://api.codeclimate.com/v1/badges/0c900848a2419c8ec20d/maintainability)](https://codeclimate.com/github/GrafiteInc/Scaffold/maintainability)
[![Packagist](https://img.shields.io/packagist/dt/grafite/scaffold.svg)](https://packagist.org/packages/grafite/scaffold)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/grafite/scaffold)

## About Grafite Scaffold
Grafite Scaffold was created as a highly opinionated boilerplate of Laravel's base application structure. In comes loaded with a variety of packages for various needs including:

- grafite/auth
- grafite/charts
- grafite/database
- grafite/forms
- grafite/html
- grafite/maintenance
- hisorange/browser-detect
- intervention/image
- guzzlehttp/guzzle
- bepsvpt/secure-headers
- livewire/livewire
- laravel/framework
- laravel/cashier
- laravel/dusk
- laravel/helpers
- laravel/sanctum
- lasserafn/php-initial-avatar-generator
- tightenco/ziggy
- pragmarx/google2fa-laravel
- pragmarx/recovery
- pusher/pusher-php-server
- silviolleite/laravelpwa
- spatie/laravel-honeypot

Scaffold is *intended* to be used to start a project and then be heavily altered.

##### Author(s):
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), mattlantz at gmail dot com)

## Requirements

1. PHP 8.2+

## Setup

### Local development (Sail)

```
cp .env.example .env    # then set DB_HOST=mysql, REDIS_HOST=valkey, MAIL_HOST=mailpit, MAIL_PORT=1025
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm run dev
```

`compose.yaml` runs the app with MySQL, Valkey and Mailpit.

### Production image

The `Dockerfile` builds one image (PHP 8.4 on [serversideup/php](https://serversideup.net/open-source/docker-php/) fpm-nginx, with the Vite assets compiled in) that runs every part of the app: the web server by default, and `queue:work`, `schedule:work` or `migrate` when given that command. To try it locally the way production runs it:

```
docker compose -f compose.production.yaml up --build    # https://localhost:8443
```

Configuration comes only from environment variables (`.env` files are not copied into the image). The container filesystem is temporary, so production sends uploads to S3/Spaces (`FILESYSTEM_DISK=s3`), logs to stderr, and reads Passport keys from `PASSPORT_PRIVATE_KEY` / `PASSPORT_PUBLIC_KEY`.

## Deploying to DigitalOcean App Platform

After the Tests workflow passes on `main`, `.github/workflows/deploy.yml` builds the image, pushes it to DigitalOcean Container Registry, and deploys `.do/app.yaml`. You can also run the workflow by hand from the Actions tab. The app spec defines:

- `web`: the HTTP service, health-checked at `/up`
- `queue`: a `queue:work` worker
- `scheduler`: a `schedule:work` worker. Keep it at one instance.
- `migrate`: a pre-deploy job. A failed migration stops the deploy.
- `db` and `cache`: managed MySQL and managed Valkey. Valkey handles cache, sessions, queues and scheduler locks.

### One-time setup

1. In DigitalOcean, create:
   - a Container Registry
   - a managed MySQL cluster, with a database and user for the app
   - a managed Valkey cluster
   - a Spaces bucket and a Spaces access key
   - an API token with read/write access, for GitHub
2. Generate the app and Passport keys locally. The Passport keys must be stored on a single line with `\n`:
   ```
   php artisan key:generate --show
   php artisan passport:keys --force
   awk '{printf "%s\\n", $0}' storage/oauth-private.key    # PASSPORT_PRIVATE_KEY
   awk '{printf "%s\\n", $0}' storage/oauth-public.key     # PASSPORT_PUBLIC_KEY
   ```
3. In GitHub, go to Settings → Secrets and variables → Actions. You can add these to the `production` environment or to the repository.
   - **Variables:**
     - `DOCR_REGISTRY`, `DOCR_REPOSITORY`
     - `DO_APP_NAME`, `DO_PROJECT_ID` (optional)
     - `DO_MYSQL_CLUSTER`, `DO_MYSQL_DATABASE`, `DO_MYSQL_USER`, `DO_VALKEY_CLUSTER`
     - `SPACES_REGION` (for example `tor1`), `SPACES_BUCKET`
     - `MAIL_HOST`, `MAIL_PORT`, `MAIL_FROM_ADDRESS`
     - `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_CLUSTER`
     - `STRIPE_KEY`, `PLAN_MONTHLY`, `PLAN_YEARLY`
     - `VAPID_SUBJECT`, `VAPID_PUBLIC_KEY`
     - `MISSION_CONTROL_PROJECT_UUID`
   - **Secrets:**
     - `DIGITALOCEAN_ACCESS_TOKEN`
     - `APP_KEY`, `PASSPORT_PRIVATE_KEY`, `PASSPORT_PUBLIC_KEY`
     - `SPACES_ACCESS_KEY_ID`, `SPACES_SECRET_ACCESS_KEY`
     - `MAIL_USERNAME`, `MAIL_PASSWORD`
     - `PUSHER_APP_SECRET`
     - `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
     - `ANTHROPIC_API_KEY`, `OPENAI_API_KEY`
     - `VAPID_PRIVATE_KEY`
     - `MISSION_CONTROL_USER_TOKEN`, `MISSION_CONTROL_PROJECT_KEY`
4. Push to `main`, or run the Deploy workflow by hand. The first run creates the app; later runs update it.
5. To use a custom domain, uncomment `domains:` in `.do/app.yaml` and point a CNAME at the app's `ondigitalocean.app` address.

Every deploy replaces the app's configuration with `.do/app.yaml`, so set secrets in GitHub, not in the DigitalOcean dashboard. A secret or variable that isn't set is sent as an empty value. To add a new setting, add it to `.do/app.yaml`, to the deploy step's `env:` in `deploy.yml`, and to GitHub.

## Connecting Claude (MCP)

The app ships with an MCP server for user accounts at `/mcp/users`, authorized with OAuth 2.1 via Laravel Passport.

1. Run `php artisan migrate` and `php artisan passport:keys` once per environment (the keys are gitignored).
2. In Claude, add a custom connector pointing at `https://{your-app}/mcp/users`. Claude registers itself, and you approve access on the app's authorize page.

Add servers and tools in `routes/ai.php` and `app/Mcp/` (`php artisan make:mcp-server`, `php artisan make:mcp-tool`).

## Compatibility and Support

Since Grafite Scaffold is a starter project it offers no support, and no compatibily guarantees.

## Installation

```
composer create-project grafite/scaffold {project-name} --prefer-dist
```

## Documentation

[https://docs.grafite.ca/scaffold/introduction](https://docs.grafite.ca/scaffold/introduction)

## License
Grafite Scaffold is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

## Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
