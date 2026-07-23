<?php

declare(strict_types=1);

namespace Modules\Email\Console\Commands;

use Illuminate\Console\Command;
use Modules\Email\Actions\PollEmailInboxAction;
use Modules\Email\Settings\EmailSettings;

final class PollEmailInboxCommand extends Command
{
    protected $signature = 'email-ai:poll-inbox {--limit= : Numero massimo di email da leggere} {--force : Esegue anche se il polling e disabilitato}';

    protected $description = 'Legge periodicamente la inbox configurata e importa le email nell AI workflow.';

    public function handle(EmailSettings $settings, PollEmailInboxAction $poll): int
    {
        if (! $settings->pollEnabled() && ! (bool) $this->option('force')) {
            $this->info('Polling email AI disabilitato.');

            return self::SUCCESS;
        }

        $limitOption = $this->option('limit');
        $limit = is_numeric($limitOption) ? (int) $limitOption : null;
        $result = $poll->execute($limit);

        $this->info(sprintf(
            'Email lette: %d (inbox: %d, inviate: %d), salvate: %d, duplicate: %d.',
            $result['fetched'],
            $result['fetched_inbound'],
            $result['fetched_outbound'],
            $result['stored'],
            $result['skipped'],
        ));

        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        return $result['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
