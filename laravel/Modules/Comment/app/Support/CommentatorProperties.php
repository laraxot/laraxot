<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

/**
 * @property-read string|null $email
 * @property-read string|null $name
 * @property-read string|null $url
 * @property-read string|null $avatar
 */
class CommentatorProperties
{
    /** @param array<string, mixed> $properties */
    protected function __construct(protected array $properties) {}

    public function __get(string $name): mixed
    {
        return $this->properties[$name] ?? null;
    }

    public static function email(string $email): self
    {
        return new self(['email' => $email]);
    }

    public function name(?string $name): self
    {
        $this->properties['name'] = $name;

        return $this;
    }

    public function url(string $url): self
    {
        $this->properties['url'] = $url;

        return $this;
    }

    public function avatar(?string $avatar): self
    {
        $this->properties['avatar'] = $avatar;

        return $this;
    }

    public function add(string $name, mixed $value): self
    {
        $this->properties[$name] = $value;

        return $this;
    }
}
