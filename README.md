# MC Payment

Multi-currency payment request system built with Laravel 12.

## Requirements

- PHP 8.3+
- Composer
- Node.js & NPM
- SQLite (default) or MySQL

## Setup

```bash
composer run setup
```

Or manually:

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Development

Start the development servers:

```bash
composer run dev
```

This runs the PHP server, queue worker, log watcher, and Vite dev server concurrently.

## Testing

```bash
composer run test
```

Or with Sail:

```bash
vendor/bin/sail artisan test --compact
```

## Available Commands

| Command | Description |
|---------|-------------|
| `composer run dev` | Start all dev servers concurrently |
| `composer run test` | Run tests with fresh config |
| `composer run setup` | Full project setup |
| `vendor/bin/sail up -d` | Start Docker containers |
| `vendor/bin/sail artisan <cmd>` | Run Artisan via Sail |

## Tech Stack

- **Framework:** Laravel 12
- **Server:** Laravel Octane (FrankenPHP)
- **Testing:** Pest 4 / PHPUnit 12
- **Frontend:** Tailwind CSS 4 + Vite 8
- **Database:** SQLite / MySQL
- **Tools:** Sail, Pint, Pail, Boost

## Project Structure

```
app/          - Application logic (models, controllers, etc.)
config/       - Configuration files
database/     - Migrations, factories, seeders
routes/       - Web and console routes
resources/    - Views, frontend assets
tests/        - Feature and unit tests
docs/         - Documentation and requirements
```
