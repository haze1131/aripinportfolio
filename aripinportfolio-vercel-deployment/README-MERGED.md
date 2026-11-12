# Aripin Portfolio — merged front-end

This package combines the supplied Blade portfolio front-end with the first Laravel project's existing database-backed admin, projects, and skills.

## Install
1. Copy the project files into your Laravel project (or extract this package as the project).
2. Create your local `.env` from `.env.example` and configure the database.
3. Install dependencies if they are not already installed:
   - `composer install`
   - `npm install`
4. Run migrations if needed: `php artisan migrate`
5. Create the public storage symlink: `php artisan storage:link`
6. Clear cached views/config: `php artisan optimize:clear`
7. Start the app: `php artisan serve`

## Admin
- Login: `/admin/login`
- Dashboard: `/admin`
- The existing authentication flow and database tables are retained.

## Notes
- The new front-end reads profile values from `portfolio_contents` and projects/skills from their existing tables.
- The new design includes sections for experiences, certificates, learning logs, and testimonials. The original backend does not yet have tables or admin CRUD for those sections, so they safely render empty states until those features are implemented.
- Profile images can now be uploaded separately for Home and About from the admin dashboard.
- The legacy `about_image` key is still supported for compatibility.
- `.env`, `vendor`, and `node_modules` are intentionally excluded. Do not distribute real credentials.
