# Francena Decors — Static to Laravel Migration (Guide)

This repository currently contains the original static site files and a small Blade scaffold to start migrating into a Laravel 12 application.

Quick checklist to continue migration locally:

1. Create a fresh Laravel 12 project (recommended outside this repo or replace after backup):

```bash
composer create-project laravel/laravel:^12 fancy-decorators
cd fancy-decorators
```

2. Copy the `resources/views`, `resources/js`, `resources/css`, and `routes/web.php` files from this repository into the Laravel project.

3. Move your static assets (`style.css`, `script.js`, images, fonts) into the Laravel `public/` folder. Update Blade templates to use `{{ asset('...') }}` helpers.

4. Install node packages and configure Vite (see previous plan). Then build assets:

```bash
npm install
npm run dev
php artisan key:generate
php artisan storage:link
php artisan migrate
```

5. Progressively convert static HTML sections into Blade partials in `resources/views/layouts/partials/` and replace placeholders in `welcome.blade.php`.

6. When ready, scaffold authentication, admin controllers, and database models.

If you want, I can now:
- Copy the full `index.html` static markup into structured Blade partials automatically in this workspace.
- Or generate a full Laravel skeleton (composer create-project cannot be run from here). Tell me which action to take next.