# CPUT Faculty of Health & Wellness Sciences — Transport Request Platform

A Laravel app for managing student clinical-placement transport requests: students submit trip requests, staff approve them and bulk-onboard student accounts, and admins consolidate trips, combine nearby ones into shared journeys, generate RFQs, and track placements on a map.

## Stack

- Laravel 12, PHP 8.4 (via [Herd](https://herd.laravel.com))
- **MySQL** for storage (see setup below)
- Leaflet/OpenStreetMap for the placements map — no external API key required

## Local setup

Requires [Herd](https://herd.laravel.com) (or any PHP 8.2+/Composer setup) and MySQL.

### 1. Install and start MySQL

```bash
brew install mysql
brew services start mysql   # starts now and on every login
```

### 2. Create the database and app user

```bash
mysql -u root <<'SQL'
CREATE DATABASE transport_herd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'transport_herd'@'localhost' IDENTIFIED BY 'choose-a-password';
GRANT ALL PRIVILEGES ON transport_herd.* TO 'transport_herd'@'localhost';
FLUSH PRIVILEGES;
SQL
```

### 3. Configure and migrate

```bash
composer install
cp .env.example .env
php artisan key:generate
# edit .env: set DB_PASSWORD to the password you chose above
php artisan migrate --seed
```

### 4. Serve it

If this folder is parked under Herd (e.g. `~/Herd/transport-herd`), it's already served at `https://transport-herd.test`. Otherwise:

```bash
php artisan serve
```

## Demo accounts (from the seeder)

| Role    | Email                     | Password    |
|---------|----------------------------|-------------|
| Admin   | admin@cput.ac.za          | admin123    |
| Staff   | staff@cput.ac.za          | staff123    |
| Student | student@mycput.ac.za      | student123  |

Students can also self-register from the login page. Staff can bulk-create student accounts under **Staff → Bulk Upload Students** with temporary passwords students must change on first login.

## Running tests

Tests run against an isolated in-memory SQLite database (configured in `phpunit.xml`), independent of the MySQL database used for the app itself:

```bash
php artisan test
```

## Key features

- **Student**: submit transport requests (clinical site, date, time, department, qualification); pickup is always CPUT Bellville Campus.
- **Staff**: approve/reject requests, bulk-upload trips or student accounts via CSV.
- **Admin**:
  - Consolidate/finalise trips, generate RFQs in the HG Travelling Services invoice format
  - Manage the clinical site directory (name, address, coordinates)
  - **AI Trip Planner** — recommends combining separate trips into one multi-stop journey when their clinical sites are close together (deterministic distance-clustering, not a hosted AI model — see `App\Support\JourneyPlanner`)
  - **Placements map** — Leaflet map of where students are placed, filterable by department/date/shift
  - **Dashboard** — department/qualification usage stats, CSV export

## Notes

- `AUTH_SECRET`-equivalent here is Laravel's `APP_KEY`, generated via `php artisan key:generate` — treat it as a secret, especially in production.
- Session/cache/queue all use the `database` driver, so they persist in MySQL alongside app data.
