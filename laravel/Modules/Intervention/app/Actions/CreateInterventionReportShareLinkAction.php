<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Illuminate\Support\Str;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionReportShare;

/**
 * Creates (or reuses) a public share link for an intervention's report PDF.
 *
 * An existing, non-expired share for the same intervention is reused so the
 * customer keeps a stable URL instead of accumulating dead links.
 */
final class CreateInterventionReportShareLinkAction
{
    public function execute(Intervention $intervention, ?int $userId = null): InterventionReportShare
    {
        $existing = InterventionReportShare::query()
            ->where('intervention_id', $intervention->id)
            ->latest('id')
            ->first();

        if ($existing !== null && ! $existing->isExpired()) {
            return $existing;
        }

        return InterventionReportShare::query()->create([
            'intervention_id' => $intervention->id,
            'token' => $this->generateToken(),
            'created_by_user_id' => $userId,
            'expires_at' => null,
        ]);
    }

    private function generateToken(): string
    {
        do {
            $token = Str::random(48);
        } while (InterventionReportShare::query()->where('token', $token)->exists());

        return $token;
    }
}
