<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Illuminate\Support\Facades\Storage;
use Modules\Blog\Models\Article;
use Spatie\QueueableAction\QueueableAction;

final class GetArticleMainImageUrlAction
{
    use QueueableAction;

    public function execute(Article $article): string
    {
        if ($article->media) {
            return $article->getFirstMediaUrl('main_image_upload');
        }

        if ($article->main_image_upload) {
            return Storage::url($article->main_image_upload);
        }

        if (null !== $article->main_image_url) {
            return $article->main_image_url;
        }

        return '#';
    }
}
