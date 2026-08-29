<?php

declare(strict_types=1);

namespace Modules\Platform\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Models\AuditLog;

final class AuditLogger
{
    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     */
    public function log(string $action, Model $subject, ?array $before = null, ?array $after = null): void
    {
        $request = null;
        try {
            $request = request();
        } catch (\Throwable) {
            $request = null;
        }

        $panelId = null;
        try {
            $panelId = filament()->getCurrentPanel()?->getId();
        } catch (\Throwable) {
            $panelId = null;
        }

        $route = $request?->path();
        $ip = $request?->ip();
        $userAgent = $request?->userAgent();
        $subjectKey = $subject->getKey();

        AuditLog::query()->create([
            'created_at' => now(),
            'actor_user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject::class,
            'subject_id' => is_scalar($subjectKey) ? (string) $subjectKey : null,
            'panel_id' => $panelId,
            'route' => is_string($route) ? $route : null,
            'ip' => is_string($ip) ? $ip : null,
            'user_agent' => is_string($userAgent) ? $userAgent : null,
            'before' => $this->sanitizePayload($before),
            'after' => $this->sanitizePayload($after),
        ]);
    }

    /**
     * @param  array<string, mixed>|null  $payload
     * @return array<string, mixed>|null
     */
    private function sanitizePayload(?array $payload): ?array
    {
        if ($payload === null) {
            return null;
        }

        $redactKeys = [
            'password',
            'remember_token',
            'api_key',
            'access_token',
            'refresh_token',
            'token',
            'secret',
            'client_secret',
            'private_key',
            'authorization',
        ];

        $result = [];
        foreach ($payload as $key => $value) {
            $keyLower = strtolower((string) $key);
            $shouldRedact = false;
            foreach ($redactKeys as $rk) {
                if ($keyLower === $rk || str_contains($keyLower, $rk)) {
                    $shouldRedact = true;
                    break;
                }
            }

            if ($shouldRedact) {
                $result[(string) $key] = '[REDACTED]';

                continue;
            }

            if (is_array($value)) {
                /** @var array<string, mixed> $value */
                $result[(string) $key] = $this->sanitizePayload($value);

                continue;
            }

            $result[(string) $key] = $value;
        }

        return $result;
    }
}
