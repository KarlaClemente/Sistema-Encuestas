# AGENTS.md

## Stack

- Laravel 13 + Livewire 4 + Flux UI + Tailwind CSS 4
- SQLite by default (DB_CONNECTION=sqlite)
- PHP 8.3+ required

## Developer Commands

```bash
# Initial setup
composer setup

# Run all dev servers (PHP, queue, logs, Vite)
composer dev

# Code formatting (Pint, Laravel preset)
composer lint
composer lint:check   # dry-run

# Testing (config:clear -> lint:check -> phpunit)
composer test

# Run tests directly (CI skips lint)
./vendor/bin/phpunit
```

## Test Environment

- Uses SQLite `:memory:` database (phpunit.xml:27)
- Sync queue, array cache/session, null broadcast

## Architecture Notes

- **Routes**: `routes/web.php` for survey CRUD; `routes/settings.php` for Fortify/Livewire settings
- **17 Models**: Encuesta, Pregunta, Grupo, Participante, Respuesta, Token*, and more in `app/Models/`
- **Controllers**: EncuestaController, PreguntaController, CorreoController in `app/Http/Controllers/`
- **Livewire**: Settings UI in `app/Livewire/Settings/`; logout action in `app/Livewire/Actions/`

## CI Workflows

- `tests.yml`: Runs on push/PR to develop, main, master, workos branches; tests PHP 8.3, 8.4, 8.5
- `lint.yml`: Runs Pint on same branches

## Important Constraints

- Flux UI requires credentials: `composer config http-basic.composer.fluxui.dev <user> <key>` before `composer install`
- Build assets before testing: `npm run build`
- Ignore `.env` - do not commit environment files
