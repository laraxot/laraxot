<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Makes the intervention calendar actually populated in the demo:
 *  1. ensures a few technician (operator) users exist, so there are several
 *     calendars to look at with distinct colors;
 *  2. backfills `scheduled_at` from `started_at` (the calendar keys on it, but
 *     the synthetic seeder only set started_at) — otherwise nothing shows;
 *  3. spreads the seeded interventions across ALL existing users (round-robin),
 *     so whoever logs in sees their own agenda (and admin sees everyone's).
 * Idempotent.
 */
final class InterventionsAgendaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureDemoTechnicians();

        // 2) Backfill the field the calendar filters on.
        DB::table('interventions')
            ->whereNull('scheduled_at')
            ->whereNotNull('started_at')
            ->update(['scheduled_at' => DB::raw('started_at')]);

        // 3) Distribute interventions (and their time punches) across ALL users,
        //    including the ones already present, so everyone has a full agenda.
        /** @var list<int> $userIds */
        $userIds = DB::table('users')->orderBy('id')->pluck('id')->map(static fn ($id): int => (int) $id)->all();
        if ($userIds === []) {
            return;
        }

        $ids = DB::table('interventions')->whereNotNull('scheduled_at')->orderBy('id')->pluck('id');
        foreach ($ids as $index => $interventionId) {
            $userId = $userIds[$index % count($userIds)];
            DB::table('interventions')->where('id', $interventionId)->update(['performed_by_user_id' => $userId]);
            DB::table('time_punches')->where('intervention_id', $interventionId)->update(['user_id' => $userId]);
        }
    }

    private function ensureDemoTechnicians(): void
    {
        // Make sure the role exists even when this seeder runs standalone.
        if (class_exists(Role::class)) {
            Role::findOrCreate('operator');
        }

        $technicians = [
            'tecnico1@local.test' => 'Tecnico Rossi',
            'tecnico2@local.test' => 'Tecnico Bianchi',
            'tecnico3@local.test' => 'Tecnico Verdi',
        ];

        foreach ($technicians as $email => $name) {
            /** @var User $user */
            $user = User::query()->firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('ChangeMe123!'), 'approved_at' => now()],
            );
            $user->forceFill(['approved_at' => $user->approved_at ?? now()])->save();
            if (method_exists($user, 'syncRoles')) {
                $user->syncRoles(['operator']);
            }
        }
    }
}
