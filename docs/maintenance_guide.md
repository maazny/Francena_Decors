# Fancy Decorators CMS – System Maintenance Guide

This guide details standard operating procedures for ongoing maintenance, cache flushing, log audits, and queue monitoring of the Fancy Decorators CMS application.

---

## 1. Putting the Site into Maintenance Mode

To perform system upgrades, place the application into maintenance mode:

```bash
php artisan down --redirect=/ --secret="maintenance-secret"
```

* **Secret Bypass**: Admins can bypass the maintenance page by visiting `https://your-domain.com/maintenance-secret`. A cookie is set to allow normal navigation.
* **Branded Template**: The template returned is `errors.503`.

To return the application to active service:
```bash
php artisan up
```

---

## 2. Flushing Caches Safely

If content or configurations are modified in the CMS and require instant cache invalidation, run:

```bash
# Clear application data cache
php artisan cache:clear

# Clear layouts and compiled views
php artisan view:clear

# Clear configured systems
php artisan config:clear
php artisan route:clear
```

In production, immediately rebuild compiled caches to maximize performance:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3. Auditing Activity & System Logs

System activity logs record administrative actions (CMS edits, logins, role updates).

* **Laravel Application Logs**: Located in `storage/logs/laravel.log`. Check for critical errors or exceptions:
  ```bash
  tail -f -n 100 storage/logs/laravel.log
  ```
* **Activity Log Table**: Direct queries can be performed in the DB, or checked inside the Admin CMS dashboard panel under logs.
* **Worker Logs**: Supervisor background queue tasks output details to `storage/logs/worker.log`.

---

## 4. Troubleshooting Background Queue Workers

If emails fail to send or scheduled jobs do not dispatch:

1. Check if the Supervisor daemon is active:
   ```bash
   sudo supervisorctl status
   ```
2. View worker outputs:
   ```bash
   tail -n 100 storage/logs/worker.log
   ```
3. Inspect and retry failed jobs:
   ```bash
   # List failed background tasks
   php artisan queue:failed

   # Retry all failed tasks
   php artisan queue:retry all

   # Flush/clear failed jobs log
   php artisan queue:flush
   ```
