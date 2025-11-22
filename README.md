# CoFi

Lightweight PHP web project for managing a cafe website (admin & public front-end).

## About

This repository contains the code for the `CoFi` project. It uses plain PHP with Composer-managed dependencies and a small project structure inside `src/` and `public/` for the served site.

## Requirements

- PHP (7.4+ recommended)
- Composer
- MySQL or MariaDB

## Quick start

1. Clone the repository:

```powershell
git clone <repo-url>
cd cofi.cafe
```

2. Install dependencies with Composer:

```powershell
composer install
```

3. Copy environment template and configure environment variables:

```powershell
Copy-Item .env.template .env
```

Open `.env` and fill values (DB credentials, app URL, etc.).

4. Import the database schema and seed data (SQL dump included):

```powershell
mysql -u <db_user> -p <database_name> < src/Core/sql/dump-cofi_cafe_db-202511151717.sql
```

5. Run the development server (PHP built-in server):

```powershell
php -S localhost:8000 -t public
```

Then open `http://localhost:8000` in your browser.

## Project Structure

- `public/` : Document root for web server (entry point `index.php`, assets, js, styles).
- `src/` : Application source code (Core utilities, routing, includes, views).
- `vendor/` : Composer dependencies.
- `Process/` : Request handlers (login, register, destroyer, etc.).
- `archive/` : Misc exports and templates.
- `logs/` : Log files.

Notable files:

- `src/Core/sql/dump-cofi_cafe_db-202511151717.sql` – SQL dump to create the app database.
- `.env.template` – Environment variables template to copy to `.env`.

## Environment variables

The repository contains `.env.template` at the project root. Copy it to `.env` and set the following values before running the app:

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- Any other app-specific keys found in `.env.template`.

## Running & Development notes

- On Windows PowerShell use the `Copy-Item` command shown above.
- If you run into permission issues with uploads or logs, ensure the `public/assets` and `logs/` folders are writable by your web server user.

## Testing

This repository does not include an automated test suite. Add tests and CI as needed.

## Contributing

1. Open an issue describing the change or bug.
2. Create a feature branch from `main` or `devRoi`.
3. Open a pull request with a clear description of changes.

## License

No license file found in the repository. Add a `LICENSE` file if you want to make the project's license explicit.

---

If you'd like, I can:

- add badges (build / license),
- expand environment examples, or
- create a `Makefile`/PowerShell script to simplify common commands.

Tell me which you'd prefer next.
