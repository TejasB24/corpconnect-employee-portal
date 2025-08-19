# CorpConnect Backend (PHP + MySQL)

## Quick start

1. Ensure MySQL is running and you know credentials.
2. Export environment variables (or edit `db/config.php` defaults):

```
export DB_HOST=127.0.0.1
export DB_NAME=corpconnect
export DB_USER=root
export DB_PASS=yourpassword
```

3. Start the PHP dev server from this folder:

```
php -S 0.0.0.0:8080 router.php
```

4. In the frontend `.env` (or `.env.local`), set:

```
VITE_API_URL=http://localhost:8080
```

## Endpoints

- POST `/auth/register` { name, email, password, role? }
- POST `/auth/login` { email, password } → { token }
- POST `/auth/logout` (Bearer token)
- GET `/me` (Bearer token)
- `/projects` (Bearer token) → GET list; admin can POST/PUT/PATCH/DELETE
- `/messages` (Bearer token) → GET list; admin can POST/PUT/PATCH/DELETE
- `/employees` (Bearer token)
  - GET list (admin) or the caller's employee record by email (employee)
  - POST create (admin)
  - PUT/PATCH update by `?id=` (admin)
  - DELETE by `?id=` (admin)

## Notes

- Tables are created automatically on first request.
- Tokens are stored in `auth_tokens` and expire in 24h.
- CORS is enabled for development (`*`). Adjust for production.

## Seeding demo data

Optionally populate demo data (admin, employees, projects, messages):

```
cd backend
php seed.php
```

## Credentials management

- Copy `backend/db/db.example.php` to `backend/db/db.php` and set your local credentials.
- `backend/db/db.php` is ignored by git via `.gitignore`.
