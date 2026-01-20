# RoleGuard

**RoleGuard** is a Laravel 12 + Inertia + Vue starter kit focused on **RBAC (roles & permissions)** and **audit logging** for secure admin panels.

## What you get

- **Auth-ready admin area** under `/admin`
- **Roles & permissions** with backend enforcement + frontend UI hiding
- **Super Admin bypass** (no permission required)
- **Audit logging** middleware to track authenticated user actions
- **Reusable admin UI primitives** (DataTable, ConfirmDialog, toast notifications)
- Example admin CRUDs: **Services** and **FAQs**

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm
- SQLite (default) or your preferred DB

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
```

Then open `/` (homepage) and `/admin` (after login).

## Super Admin

Super Admin bypasses **all** permission/role checks.

Set in `.env`:

```env
SUPER_ADMIN_EMAIL=admin@admin.com
```

Config lives in:

- `config/admin.php` → `super_admin_email`

Fallback behavior:

- If `SUPER_ADMIN_EMAIL` is **empty/null**, the **first user (id=1)** is treated as super admin.

## Roles & permissions (how it works)

- Backend enforcement is done via middleware:
  - `permission:<slug>` (see `app/Http/Middleware/RequirePermission.php`)
  - `role:<slug>` (see `app/Http/Middleware/RequireRole.php`)
- Admin controllers declare per-action permissions via Laravel 11 controller middleware:
  - Example: `App\Http\Controllers\Admin\ServiceController::middleware()`
- Frontend hides/disables UI elements using:
  - `resources/js/composables/usePermissions.ts`
  - Inertia shared props from `app/Http/Middleware/HandleInertiaRequests.php`

## Audit logging

RoleGuard tracks authenticated requests via middleware:

- `app/Http/Middleware/AuditRequest.php`

Logs are stored in Laravel logs (and/or wherever you extend it to store audits).

## Admin routes

Admin routes live in:

- `routes/admin.php`

Public routes are intentionally minimal (homepage only) to keep this repo focused on the starter kit.

## Common UI components

- `resources/js/components/admin/DataTable.vue`
- `resources/js/components/admin/ConfirmDialog.vue`
- `resources/js/components/admin/ToastContainer.vue`
- `resources/js/composables/useToast.ts`

## Troubleshooting

### `vite build` fails with `.env` permission denied

If you see `EACCES: permission denied, open '.env'`, fix file permissions:

```bash
chmod 644 .env .env.example
```

Also ensure the files are owned by your user (not `root`) if needed.

