<?php

declare(strict_types=1);

namespace Modules\Media\Actions\Diagnostic\Aws;

use Spatie\QueueableAction\QueueableAction;

class GetAwsConfigSnapshotAction
{
    use QueueableAction;

    private const KEY_PREVIEW_LENGTH = 8;

    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        return [
            'AWS_ACCESS_KEY_ID' => substr((string) config('filesystems.disks.s3.key', ''), 0, self::KEY_PREVIEW_LENGTH).'...',
            'AWS_DEFAULT_REGION' => config('filesystems.disks.s3.region'),
            'AWS_BUCKET' => config('filesystems.disks.s3.bucket'),
            'CLOUDFRONT_URL' => config('filesystems.cloudfront.url'),
            'CLOUDFRONT_KEY_PAIR_ID' => config('filesystems.cloudfront.key_pair_id'),
        ];
    }
}
