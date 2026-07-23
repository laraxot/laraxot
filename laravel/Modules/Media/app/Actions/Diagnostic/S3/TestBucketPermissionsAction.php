<?php

declare(strict_types=1);

namespace Modules\Media\Actions\Diagnostic\S3;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Exception;
use Modules\Media\Actions\Diagnostic\Support\CreateFilesystemS3ClientAction;
use Spatie\QueueableAction\QueueableAction;

class TestBucketPermissionsAction
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    public function execute(string $testKeyPrefix = 'test-permissions-'): array
    {
        $results = [
            'title' => '🔒 S3 Permissions',
            'status' => 'info',
            'data' => [],
        ];

        try {
            $s3 = app(CreateFilesystemS3ClientAction::class)->execute();
            $bucket = app(CreateFilesystemS3ClientAction::class)->bucket();
            $testKey = $testKeyPrefix.time().'.txt';

            $results['data'] = $this->probePermissions($s3, $bucket, $testKey);
            $results['status'] = 'success';
        } catch (Exception $exception) {
            $results['status'] = 'error';
            $results['data']['Error'] = $exception->getMessage();
        }

        return $results;
    }

    /**
     * @return array<string, string>
     */
    private function probePermissions(S3Client $s3, string $bucket, string $testKey): array
    {
        $data = [];
        $data['ListBucket'] = $this->probeListBucket($s3, $bucket);

        return array_merge($data, $this->probeObjectCrud($s3, $bucket, $testKey));
    }

    private function probeListBucket(S3Client $s3, string $bucket): string
    {
        try {
            $s3->listObjectsV2(['Bucket' => $bucket, 'MaxKeys' => 1]);

            return '✅ OK';
        } catch (AwsException $exception) {
            return '❌ '.($exception->getAwsErrorCode() ?? 'UnknownError');
        }
    }

    /**
     * @return array<string, string>
     */
    private function probeObjectCrud(S3Client $s3, string $bucket, string $testKey): array
    {
        try {
            $s3->putObject([
                'Bucket' => $bucket,
                'Key' => $testKey,
                'Body' => 'Test permissions',
                'ACL' => 'private',
            ]);
        } catch (AwsException $exception) {
            return [
                'PutObject' => '❌ '.($exception->getAwsErrorCode() ?? 'UnknownError'),
                'GetObject' => 'Skipped (PutObject failed)',
                'DeleteObject' => 'Skipped (PutObject failed)',
            ];
        }

        return [
            'PutObject' => '✅ OK',
            'GetObject' => $this->probeGetObject($s3, $bucket, $testKey),
            'DeleteObject' => $this->probeDeleteObject($s3, $bucket, $testKey),
        ];
    }

    private function probeGetObject(S3Client $s3, string $bucket, string $testKey): string
    {
        try {
            $s3->getObject(['Bucket' => $bucket, 'Key' => $testKey]);

            return '✅ OK';
        } catch (AwsException $exception) {
            return '❌ '.($exception->getAwsErrorCode() ?? 'UnknownError');
        }
    }

    private function probeDeleteObject(S3Client $s3, string $bucket, string $testKey): string
    {
        try {
            $s3->deleteObject(['Bucket' => $bucket, 'Key' => $testKey]);

            return '✅ OK';
        } catch (AwsException $exception) {
            return '❌ '.($exception->getAwsErrorCode() ?? 'UnknownError');
        }
    }
}
