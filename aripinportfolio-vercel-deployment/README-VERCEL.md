# Laravel deployment package for Vercel

This package is based on the Laravel project archive supplied in this conversation.
It adds the Vercel container deployment files without changing the app's routes,
controllers, models, or Blade views.

## Added files

- `Dockerfile.vercel` — builds Vite assets, installs production PHP dependencies,
  and packages the Laravel app in FrankenPHP.
- `Caddyfile` — serves Laravel from the `public/` directory.
- `.dockerignore` — excludes secrets, local SQLite data, and local dependencies.

## Deploy overview

1. Extract this ZIP into a new folder.
2. Put the complete project folder in a Git repository and push it to GitHub.
3. Import that repository in Vercel.
4. If Vercel detects Vite instead of the container, set the project's Framework
   Preset to **Container**. `Dockerfile.vercel` is intended to take precedence.
5. Add production environment variables in Vercel. Do not upload or commit `.env`.
6. Deploy a Preview first and test `/`, `/admin/login`, and admin functions.

The Laravel project root is the folder containing `artisan`, `composer.json`,
`Dockerfile.vercel`, and `Caddyfile`. If importing a repository with an outer
folder, set Vercel's Root Directory to that folder.

## Required production environment variables

At minimum:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` — use the same generated key for all instances of this deployment
- `APP_URL` — your Vercel deployment URL
- `LOG_CHANNEL=stderr`
- `DB_CONNECTION=mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`

Use the Railway MySQL connection values in Vercel's Environment Variables.
Never commit credentials or share them in screenshots.

## Important storage note

Vercel container filesystems are not durable across instance replacement and
are not shared between instances. This app currently uses Laravel's local
filesystem for public uploads. Before relying on image/CV uploads in production,
configure an S3-compatible object-storage service and change the app's upload
disk to that service. Existing files bundled under `storage/app/public/uploads`
may be present in the image, but new uploads to local storage are not durable.

## Database note

The local SQLite database is intentionally not included in the deployment
package. The deployed app must use Railway MySQL. Run migrations as a separate
deployment/release step only after confirming the Vercel environment variables.
Do not run seeders blindly on production; create/maintain the admin account
securely through the existing database or a controlled one-time command.

## Important

This package does not contain `.env`, database credentials, or a production
`APP_KEY`. It does not deploy anything by itself; it prepares the source tree
for a Git-based Vercel deployment.
