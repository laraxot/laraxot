<?php

declare(strict_types=1);

namespace Modules\Quotation\DTO;

final readonly class Money
{
    private function __construct(
        public int $cents,
    ) {}

    public static function zero(): self
    {
        return new self(0);
    }

    public static function fromFloat(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function sub(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    public function asDecimalString(): string
    {
        return number_format($this->cents / 100, 2, '.', '');
    }

    public function asFloat(): float
    {
        return $this->cents / 100;
    }
}
