<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Modules\Comment\Datas\CommentConfigData;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class SanitizeCommentTextAction
{
    use QueueableAction;

    public function execute(string $text): string
    {
        return (new HtmlSanitizer($this->buildConfig()))->sanitize($text);
    }

    private function buildConfig(): HtmlSanitizerConfig
    {
        $config = new HtmlSanitizerConfig();
        $config = $config->allowRelativeLinks();
        $config = $config->allowRelativeMedias();
        $config = $config->allowSafeElements();

        $commentConfig = CommentConfigData::make();

        foreach ($commentConfig->allowedAttributes as $element => $attributes) {
            if (! is_string($element) || ! is_array($attributes)) {
                continue;
            }

            /** @var list<string> $allowedAttributes */
            $allowedAttributes = array_values(array_filter($attributes, is_string(...)));

            $config = $config->allowElement($element, $allowedAttributes);
        }

        if ($commentConfig->mentions['enabled'] ?? false) {
            $config = $config->allowElement('span', ['data-mention', 'class']);
        }

        return $config;
    }
}
