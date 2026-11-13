<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Links\RecordAnchor;

abstract class XotBaseCreateRecord extends FilamentCreateRecord
{
    protected function getRecordReturnUrl(string $fallback): string
    {
        $record = $this->getRecord();
        $returnTo = request()->query(RecordAnchor::RETURN_TO_PARAMETER);
        $returnTo = is_string($returnTo) ? $returnTo : RecordAnchor::previousWithinResource($this->previousUrl, static::getResource()::getUrl('index'));

        if (! $record instanceof Model || (! is_int($record->getKey()) && ! is_string($record->getKey()))) {
            return $fallback;
        }

        $returnTo = RecordAnchor::resolveReturnTo($returnTo, $record->getKey());

        return $returnTo ?? $fallback;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getRecordReturnUrl(parent::getRedirectUrl());
    }

=======

abstract class XotBaseCreateRecord extends FilamentCreateRecord
{
>>>>>>> 77b1e2748 (first)
    /**
     * Get default form data.
     *
     * @return array<string, mixed>
     */
    protected function getDefaultFormData(): array
    {
        return [];
    }
}
