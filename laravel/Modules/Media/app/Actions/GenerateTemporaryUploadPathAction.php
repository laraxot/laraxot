<?php

declare(strict_types=1);

namespace Modules\Media\Actions;

use Modules\Media\Models\Media;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GenerateTemporaryUploadPathAction
{
    use QueueableAction;

    public function execute(Media $media, string $purpose = 'original'): string
    {
        $suffix = match ($purpose) {
            'conversion' => 'conversion',
            'responsive' => 'responsive',
            default => 'original',
        };

        return $this->getBasePath($media).'/'.md5($media->id.$media->uuid.$suffix).'/';
    }

    protected function getBasePath(Media $media): string
    {
        Assert::string($id = $media->getKey());
        $key = md5($media->uuid.$id);

        return "tmp/{$key}";
    }
}
