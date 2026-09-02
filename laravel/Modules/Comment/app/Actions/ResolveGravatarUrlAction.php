<?php

declare(strict_types=1);

namespace Modules\Comment\Actions;

use Modules\Comment\Datas\CommentConfigData;
use Spatie\QueueableAction\QueueableAction;

class ResolveGravatarUrlAction
{
    use QueueableAction;

    public function execute(string $email): string
    {
        return $this->handle($email);
    }

    public function handle(string $email): string
    {
        $defaultImage = (string) (CommentConfigData::make()->gravatar['default_image'] ?? 'mp');
        $segment = md5(strtolower($email));

        return "https://www.gravatar.com/avatar/{$segment}?d={$defaultImage}";
    }
}
