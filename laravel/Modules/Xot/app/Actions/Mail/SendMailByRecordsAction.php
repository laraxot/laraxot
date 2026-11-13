<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Mail;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class SendMailByRecordsAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
     * @param  Collection<int, Model>  $records
=======
<<<<<<< .merge_file_fRskTp
     * @param  Collection<int, Model>  $records
=======
     * <<<<<<< HEAD.
     *
     * @param Collection<int, Model> $records
     *                                        =======
     *                                        <<<<<<< HEAD
     * @param Collection<int, Model> $records
     *                                        =======
     *                                        <<<<<<< HEAD
     *                                        <<<<<<< HEAD
     * @param Collection<int, Model> $records
     *                                        =======
     * @param Collection<int, Model> $records
     *                                        >>>>>>> laraxot/dev
     *                                        =======
     * @param Collection<int, Model> $records
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
     *                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_3wn5nw
>>>>>>> 77b1e2748 (first)
     */
    public function execute(Collection $records, string $mail_class): bool
    {
        foreach ($records as $record) {
            app(SendMailByRecordAction::class)->execute($record, $mail_class);
        }

        return true;
    }
}
