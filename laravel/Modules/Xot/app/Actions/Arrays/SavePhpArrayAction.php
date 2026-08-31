<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Arrays;

use Spatie\QueueableAction\QueueableAction;

use function Safe\file_put_contents;

class SavePhpArrayAction
{
    use QueueableAction;

    /**
     * @param  array<int|string, mixed>  $data
     */
    public function execute(array $data, string $filename): bool
    {
        $content = "<?php\n\nreturn ".var_export($data, true).";\n";

        return (bool) file_put_contents($filename, $content);
    }
}
