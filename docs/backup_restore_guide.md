# Francena Decors CMS – Backup & Restore Guide

This guide details the procedures to automate, verify, and restore backups of the Francena Decors Laravel 12 application.

---

## 1. Automated Backups Configuration

The application includes a built-in backup utility service. To schedule automatic daily backups, ensure the scheduler cron is enabled on the server:

```bash
* * * * * cd /var/www/fancy-decorators && php artisan schedule:run >> /dev/null 2>&1
```

The backup routine is scheduled in `app/Console/Kernel.php` (or `routes/console.php` in Laravel 12):
* **Database backup**: Daily database snapshot exported to `storage/app/backups/`.
* **Media backup**: Daily packaging of the public uploads library.

---

## 2. Manual Backup Execution

To execute a backup immediately using the command line, run:

```bash
php artisan backup:create
```

Options:
* `--only-db`: Only dump the active database.
* `--only-media`: Only package uploaded images and assets.

Example output:
```
Backup created successfully: storage/app/backups/backup-2026-07-09-db-media.zip
```

---

## 3. Database Checksums

Every backup creates a metadata file containing SHA-256 checksums to ensure file integrity. When restoring, the checksum is verified to prevent corrupted installations.

To manually generate a checksum for any file:

```bash
sha256sum storage/app/backups/backup-2026-07-09.zip
```

---

## 4. Restoration Walkthrough

Follow these steps to restore the application database and assets:

### Step 4.1: Enable Maintenance Mode
```bash
php artisan down --secret="recovery-token-2026"
```

### Step 4.2: Execute Restore Command
Specify the target backup zip file relative to the storage folder or absolute path:

```bash
php artisan backup:restore --file=storage/app/backups/backup-2026-07-09-db-media.zip
```

The command will automatically:
1. Verify the ZIP file signature and SHA-256 checksum.
2. Unzip files to a temporary directory.
3. Replace the active database (`database/database.sqlite`) safely.
4. Synchronize the uploads folder (`storage/app/public/media/`).
5. Clear compiled views and caches.

### Step 4.3: Turn Off Maintenance Mode
```bash
php artisan up
```

---

## 5. Disaster Recovery Plan

In the event of complete server failure:
1. Re-provision the server using the **Deployment Guide**.
2. Clone the repository and install Composer / NPM packages.
3. Retrieve the latest backup ZIP file from your offsite storage (e.g. S3).
4. Place the ZIP file in the `storage/app/backups/` directory.
5. Run the restore command:
   ```bash
   php artisan backup:restore --file=storage/app/backups/latest-backup.zip
   ```
6. Regenerate cache and verify status pages.
