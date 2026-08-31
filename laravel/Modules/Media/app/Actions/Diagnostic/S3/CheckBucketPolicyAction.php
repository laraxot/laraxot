<?php

declare(strict_types=1);

namespace Modules\Media\Actions\Diagnostic\S3;

use Aws\Exception\AwsException;
use Modules\Media\Actions\Diagnostic\Support\CreateFilesystemS3ClientAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

use function Safe\json_decode;
use function Safe\json_encode;

class CheckBucketPolicyAction
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        try {
            $s3 = app(CreateFilesystemS3ClientAction::class)->execute();
            $policy = $s3->getBucketPolicy(['Bucket' => app(CreateFilesystemS3ClientAction::class)->bucket()]);
            $policyJson = $policy['Policy'];
            Assert::string($policyJson);

            return [
                'title' => '📜 Bucket Policy',
                'status' => 'info',
                'data' => [
                    'Policy Exists' => '✅ Yes',
                    'Policy' => json_encode(json_decode($policyJson), JSON_PRETTY_PRINT),
                ],
            ];
        } catch (AwsException $exception) {
            if (($exception->getAwsErrorCode() ?? '') === 'NoSuchBucketPolicy') {
                return [
                    'title' => '📜 Bucket Policy',
                    'status' => 'info',
                    'data' => [
                        'Policy Exists' => 'ℹ️ No (This is usually OK)',
                    ],
                ];
            }

            return [
                'title' => '📜 Bucket Policy',
                'status' => 'error',
                'data' => [
                    'Error' => $exception->getAwsErrorCode() ?? 'UnknownError',
                    'Message' => $exception->getMessage(),
                ],
            ];
        }
    }
}
