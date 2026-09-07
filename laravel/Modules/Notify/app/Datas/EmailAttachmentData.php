<?php

declare(strict_types=1);

namespace Modules\Notify\Datas;

use Spatie\LaravelData\Data;

class EmailAttachmentData extends Data
{
    /**
     * @return void
     */
    public function __construct(
        private string $content,
        public string $name,
        public string $contentType = 'application/octet-stream',
<<<<<<< HEAD
    ) {}
=======
    ) {
    }
>>>>>>> a988596b (first)

    public function getContent(): string
    {
        return $this->content;
    }
}
