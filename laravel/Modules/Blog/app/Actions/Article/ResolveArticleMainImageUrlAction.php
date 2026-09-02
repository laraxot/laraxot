<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Illuminate\Support\Facades\Storage;
use Modules\Blog\Models\Article;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

final class ResolveArticleMainImageUrlAction
{
    use QueueableAction;

    public function execute(Article $article): string
    {
        if ($article->media) {
            return $article->getFirstMediaUrl('main_image_upload');
        }

        if ($article->main_image_upload) {
            return Storage::url(SafeStringCastAction::cast($article->main_image_upload));
        }

        if (null !== $article->main_image_url) {
            return SafeStringCastAction::cast($article->main_image_url);
        }

        return '#';
    }
}
