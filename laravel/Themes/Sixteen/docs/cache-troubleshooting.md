# Cache troubleshooting (theme notes)

When Livewire or other components throw SQL errors referencing the `cache` table:

- Check application cache driver in laravel/.env (CACHE_DRIVER). If set to `database`, ensure migrations for cache table exist and are applied.
- For quick local preview, switching to `file` driver avoids the DB dependency: set `CACHE_DRIVER=file` and reload.
- After migration, run `php artisan cache:clear` and `php artisan config:clear`.

Mobile/Dev note: Livewire requests are common in the Segnalazione wizard; missing cache table often surfaces as checksum failures.
