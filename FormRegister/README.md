# University Registration Form (PHP + MySQL)

A simple, secure PHP application for student registration to a university, backed by MySQL.

## Features
- Secure form handling with server-side validation
- PDO with prepared statements to prevent SQL injection
- Passwords hashed using `password_hash()`
- Unique constraint on email
- Simple, clean UI with Bootstrap CDN

## Prerequisites
- PHP 8.0+ (CLI or via XAMPP/WAMP)
- MySQL 5.7+/MariaDB 10.3+
- Composer not required

## Setup
1. Create a MySQL database, e.g. `university_db`.
2. Import schema:
   - Using CLI: `mysql -u <user> -p university_db < schema.sql`
   - Or via phpMyAdmin: import `schema.sql`.
3. Configure DB credentials in `config.php`:
   - Update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` as needed.
4. Run the app:
   - Option A (XAMPP/WAMP):
     - Put this project under your web root (e.g. `C:/xampp/htdocs/FormRegister/`).
     - Visit: `http://localhost/FormRegister/`.
   - Option B (PHP built-in server):
     - In this folder run: `php -S localhost:8000`
     - Visit: `http://localhost:8000/`

## Files
- `schema.sql` — Database schema (creates table `students`).
- `config.php` — PDO connection helper.
- `index.php` — Registration form.
- `submit.php` — Form processing and DB insert.
- `success.php` — Confirmation page.

## Security Notes
- CSRF: For demo simplicity this app does not include CSRF tokens. In production, add CSRF protection.
- Validation: Basic checks included. Extend as needed for your rules.
- Error display: Detailed DB errors are hidden from users. Check PHP error log during development.

## License
MIT
