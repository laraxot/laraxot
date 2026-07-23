<?php

declare(strict_types=1);

namespace Modules\Platform\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Services\AuditLogger;
use Modules\Platform\Models\PlatformSetting;

final class AuditObserver
{
    public function __construct(
        private readonly AuditLogger $logger,
    ) {}

    public function created(Model $model): void
    {
        /** @var array<string, mixed> $after */
        $after = $this->sanitizeModelPayload($model, $model->getAttributes());
        $this->logger->log('created', $model, null, $after);
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        if ($changes === []) {
            return;
        }

        $original = $model->getOriginal();

        /** @var array<string, mixed> $before */
        $before = [];
        /** @var array<string, mixed> $after */
        $after = [];

        foreach ($changes as $key => $newValue) {
            $before[$key] = $original[$key] ?? null;
            $after[$key] = $newValue;
        }

        $before = $this->sanitizeModelPayload($model, $before);
        $after = $this->sanitizeModelPayload($model, $after);

        $this->logger->log('updated', $model, $before, $after);
    }

    public function deleted(Model $model): void
    {
        /** @var array<string, mixed> $before */
        $before = $this->sanitizeModelPayload($model, $model->getAttributes());
        $this->logger->log('deleted', $model, $before, null);
    }

    public function restored(Model $model): void
    {
        /** @var array<string, mixed> $after */
        $after = $this->sanitizeModelPayload($model, $model->getAttributes());
        $this->logger->log('restored', $model, null, $after);
    }

    public function forceDeleted(Model $model): void
    {
        /** @var array<string, mixed> $before */
        $before = $this->sanitizeModelPayload($model, $model->getAttributes());
        $this->logger->log('force_deleted', $model, $before, null);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function sanitizeModelPayload(Model $model, array $payload): array
    {
        if (! $model instanceof PlatformSetting) {
            return $payload;
        }

        $rawKey = $payload['key'] ?? $model->getAttribute('key') ?? '';
        $key = is_scalar($rawKey) ? (string) $rawKey : '';
        $keyLower = strtolower($key);

        $isSensitiveSetting = str_contains($keyLower, 'api_key')
            || str_contains($keyLower, 'token')
            || str_contains($keyLower, 'secret')
            || str_contains($keyLower, 'password');

        if (! $isSensitiveSetting) {
            return $payload;
        }

        if (array_key_exists('value_json', $payload)) {
            $payload['value_json'] = '[REDACTED]';
        }

        if (array_key_exists('value_json_raw', $payload)) {
            $payload['value_json_raw'] = '[REDACTED]';
        }

        return $payload;
    }
}

