<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

it('sets DomPDF public_path to Laravel public_path()', function (): void {
    $configured = config('dompdf.public_path');

    expect($configured)->toBeString();
    expect($configured)->toBe(public_path());

    $resolved = realpath($configured);
    expect($resolved)->not()->toBeFalse();
});

