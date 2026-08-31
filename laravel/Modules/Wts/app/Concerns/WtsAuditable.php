<?php

declare(strict_types=1);

namespace Modules\Wts\Concerns;

use Modules\User\Models\User;
use Modules\Wts\Models\WtsAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Audit trail append-only dei model WTS: ogni creazione, modifica,
 * cancellazione e ripristino lascia una riga in wts_audit_logs con i valori
 * cambiati (prima/dopo) e l'utente che ha operato. Le modifiche via query
 * builder di massa non passano dagli eventi Eloquent e quindi non vanno usate
 * sui dati rilevanti per la tracciabilita'.
 */
trait WtsAuditable
{
    public static function bootWtsAuditable(): void
    {
        static::created(static function (Model $model): void {
            self::writeWtsAudit($model, 'created', [], $model->getAttributes());
        });

        static::updated(static function (Model $model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            self::writeWtsAudit(
                $model,
                'updated',
                array_intersect_key($model->getRawOriginal(), $changes),
                $changes,
            );
        });

        static::deleted(static function (Model $model): void {
            self::writeWtsAudit($model, 'deleted', [], []);
        });

        static::restored(static function (Model $model): void {
            self::writeWtsAudit($model, 'restored', [], []);
        });
    }

    /**
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    private static function writeWtsAudit(Model $model, string $action, array $oldValues, array $newValues): void
    {
        $userId = Auth::id();
        $user = Auth::user();

        WtsAuditLog::query()->create([
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'action' => $action,
            'old_values' => $oldValues === [] ? null : $oldValues,
            'new_values' => $newValues === [] ? null : $newValues,
            // L'id utente qui e' un UUID: filtrarlo con is_int() lo avrebbe
            // azzerato, lasciando ogni riga di audit senza autore.
            'performed_by_user_id' => is_int($userId) || is_string($userId) ? $userId : null,
            'performed_by_name' => $user instanceof User ? $user->name : null,
            'performed_at' => now(),
        ]);
    }
}
