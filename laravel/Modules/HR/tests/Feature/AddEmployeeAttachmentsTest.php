<?php

declare(strict_types=1);

uses(TestCase::class);

use Illuminate\Support\Facades\Storage;
use Modules\HR\Actions\AddEmployeeDocumentAction;
use Modules\HR\Actions\AddEmployeeMedicalVisitAction;
use Modules\HR\Actions\AddEmployeeTrainingAction;
use Modules\HR\Models\Employee;
use Modules\HR\Tests\TestCase;

it('adds employee documents, medical visits, and trainings with optional attachments', function (): void {
    // Arrange
    Storage::fake('public');
    Storage::disk('public')->put('media/employees/documents/doc.txt', 'doc');
    Storage::disk('public')->put('media/employees/medical-visits/visit.txt', 'visit');
    Storage::disk('public')->put('media/employees/trainings/training.txt', 'training');

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

    // Act
    $doc = app(AddEmployeeDocumentAction::class)->execute(
        employee: $employee,
        path: 'media/employees/documents/doc.txt',
        uploadedByUserId: null,
        title: 'Documento',
        kind: 'contract',
    );

    $visit = app(AddEmployeeMedicalVisitAction::class)->execute(
        employee: $employee,
        visitedAt: '2026-02-01',
        expiresAt: '2027-02-01',
        notes: 'ok',
        title: 'Visita annuale',
        attachmentPath: 'media/employees/medical-visits/visit.txt',
        uploadedByUserId: null,
    );

    $training = app(AddEmployeeTrainingAction::class)->execute(
        employee: $employee,
        name: 'Safety',
        completedAt: '2026-01-10',
        expiresAt: '2027-01-10',
        notes: 'ok',
        title: 'Corso sicurezza',
        attachmentPath: 'media/employees/trainings/training.txt',
        uploadedByUserId: null,
    );

    // Assert
    expect($doc->employee_id)->toBe($employee->id);
    expect($doc->media)->not()->toBeNull();
    expect($visit->employee_id)->toBe($employee->id);
    expect($visit->media)->not()->toBeNull();
    expect($visit->title)->toBe('Visita annuale');
    expect($visit->notes)->toBe('ok');
    expect($visit->visited_at?->toDateString())->toBe('2026-02-01');
    expect($visit->expires_at?->toDateString())->toBe('2027-02-01');
    expect($training->employee_id)->toBe($employee->id);
    expect($training->media)->not()->toBeNull();
    expect($training->title)->toBe('Corso sicurezza');
    expect($training->notes)->toBe('ok');
    expect($training->completed_at?->toDateString())->toBe('2026-01-10');
    expect($training->expires_at?->toDateString())->toBe('2027-01-10');
})->skip('D1: Document Media $table=media — laravel/docs/debt-document-media-conflict.md');
