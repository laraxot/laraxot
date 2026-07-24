<?php

declare(strict_types=1);

namespace Modules\HR\Tests\Feature;

use Modules\HR\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\Storage;
use Modules\HR\Actions\AddEmployeeDocumentAction;
use Modules\HR\Models\Employee;

it('defaults media kind to document when kind is not provided', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('media/employees/documents/doc.txt', 'doc');

    $employee = Employee::query()->create([
        'user_id' => null,
        'first_name' => 'Mario',
        'last_name' => 'Rossi',
        'email' => null,
        'phone' => null,
        'hired_at' => '2024-01-01',
        'terminated_at' => null,
        'notes' => null,
        'is_active' => true,
    ]);

    $doc = app(AddEmployeeDocumentAction::class)->execute(
        employee: $employee,
        path: 'media/employees/documents/doc.txt',
        uploadedByUserId: null,
        title: 'Documento',
        kind: null,
    );

    expect($doc->media)->not->toBeNull();
    expect($doc->media?->kind)->toBe('document');
});

it('persists the employee document kind when provided', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('media/employees/documents/contract.txt', 'contract');

    $employee = Employee::query()->create([
        'user_id' => null,
        'first_name' => 'Mario',
        'last_name' => 'Rossi',
        'email' => null,
        'phone' => null,
        'hired_at' => '2024-01-01',
        'terminated_at' => null,
        'notes' => null,
        'is_active' => true,
    ]);

    $doc = app(AddEmployeeDocumentAction::class)->execute(
        employee: $employee,
        path: 'media/employees/documents/contract.txt',
        uploadedByUserId: null,
        title: 'Contratto',
        kind: 'contract',
    );

    expect($doc->title)->toBe('Contratto');
    expect($doc->kind)->toBe('contract');
    expect($doc->media)->not->toBeNull();
    expect($doc->media?->kind)->toBe('contract');
});
