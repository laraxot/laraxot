<?php

declare(strict_types=1);

namespace Modules\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

use function in_array;

class FileExtensionRule implements ValidationRule
{
    /** @var list<string> */
    protected array $validExtensions = [];

    /**
     * @param  list<string>  $validExtensions
     */
    public function __construct(array $validExtensions = [])
    {
        $this->validExtensions = array_values(array_map(
            /**
             * @return lowercase-string
             */
            static fn (string $ext): string => mb_strtolower($ext),
            $validExtensions
        ));
    }

    public function validate(string $_attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail($this->message());

            return;
        }

        if (! in_array(mb_strtolower($value->getClientOriginalExtension()), $this->validExtensions, strict: false)) {
            $fail($this->message());
        }
    }

    public function message(): string
    {
        $message = trans('media::validation.mime', [
            'mimes' => implode(', ', $this->validExtensions),
        ]);

        if (is_string($message)) {
            return $message;
        }

        $parts = [];
        foreach ($message as $part) {
            if (is_string($part)) {
                $parts[] = $part;
            }
        }

        return implode(' ', $parts);
    }
}
