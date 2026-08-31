<?php

declare(strict_types=1);

namespace Modules\Media\Actions\Diagnostic\Support;

use Aws\S3\S3Client;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class CreateFilesystemS3ClientAction
{
    use QueueableAction;

    private const string DEFAULT_REGION = 'eu-west-1';

    public function execute(): S3Client
    {
        return new S3Client([
            'region' => config('filesystems.disks.s3.region', self::DEFAULT_REGION),
            'version' => 'latest',
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }

    public function bucket(): string
    {
        $bucket = config('filesystems.disks.s3.bucket');
        Assert::string($bucket);

        return $bucket;
    }
}
