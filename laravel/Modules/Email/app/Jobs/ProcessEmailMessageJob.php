<?php

declare(strict_types=1);

namespace Modules\Email\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Actions\ProcessEmailMessageAction;

final class ProcessEmailMessageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly int $messageId) {}

    public function handle(ProcessEmailMessageAction $action): void
    {
        $action->execute($this->messageId);
    }
}
