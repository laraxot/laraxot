<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\BaseProfileResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
use Filament\Tables\Filters\BaseFilter;
use Override;
use Exception;
use Modules\Xot\Contracts\UserContract;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
=======
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
>>>>>>> f548be94 (.)
=======
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
=======
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\BaseProfileResource;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * .
 */
class ListProfiles extends XotBaseListRecords
{
    protected static string $resource = BaseProfileResource::class;

    /**
     * @return array<string, Tables\Columns\Column>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getTableColumns(): array
    {
        return [
            'user.name' => TextColumn::make('user.name')
                ->sortable()
                ->searchable()
                ->default(function ($record) {
<<<<<<< HEAD
<<<<<<< HEAD
                    if (! is_object($record)) {
                        return '--';
                    }

                    // PHPStan Level 10: isset() invece di property_exists() per Eloquent relations/attributes
                    $userValue = $record->user ?? null;

                    if ($userValue === null) {
                        $emailValue = $record->email ?? null;

                        if ($emailValue === null) {
                            if (method_exists($record, 'update')) {
                                $record->update(['email' => fake()->email()]);
                            }
                            $emailValue = $record->email ?? '';
                        }

                        if (! is_string($emailValue)) {
                            return '--';
                        }

                        try {
                            $userValue = XotData::make()->getUserByEmail($emailValue);
                        } catch (\Exception $e) {
                            return '--';
                        }
                    }

                    if (! is_object($userValue)) {
                        return '--';
                    }

                    // PHPStan Level 10: isset() per magic properties di User model
                    $userId = $userValue->id ?? null;

                    if ($userId !== null && method_exists($record, 'update')) {
                        $record->update(['user_id' => $userId]);
                    }

                    $userName = $userValue->name ?? '--';

                    return is_string($userName) ? $userName : '--';
=======
=======
>>>>>>> 87273113 (.)
                    $user = $record->user;
                    $user_class = XotData::make()->getUserClass();
                    if ($user === null) {
                        if ($record->email === null) {
                            $record->update(['email' => fake()->email()]);
                        }
                        try {
                            /** @var UserContract */
                            $user = XotData::make()->getUserByEmail($record->email);
                        } catch (Exception $e) {
                            return '--';
                        }
                    }
                    if ($user === null) {
                        $data = $record->toArray();
                        $user_data = Arr::except($data, ['id']);
                        /** @var UserContract */
                        $user = $user_class::create($user_data);
                    }
                    $record->update(['user_id' => $user->id]);

                    return $user->name;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                    if (! is_object($record)) {
                        return '--';
                    }

                    // PHPStan Level 10: isset() invece di property_exists() per Eloquent relations/attributes
                    $userValue = $record->user ?? null;

                    if ($userValue === null) {
                        $emailValue = $record->email ?? null;

                        if ($emailValue === null) {
                            if (method_exists($record, 'update')) {
                                $record->update(['email' => fake()->email()]);
                            }
                            $emailValue = $record->email ?? '';
                        }

                        if (! is_string($emailValue)) {
                            return '--';
                        }

                        try {
                            $userValue = XotData::make()->getUserByEmail($emailValue);
                        } catch (\Exception $e) {
                            return '--';
                        }
                    }

                    if (! is_object($userValue)) {
                        return '--';
                    }

                    // PHPStan Level 10: isset() per magic properties di User model
                    $userId = $userValue->id ?? null;

                    if ($userId !== null && method_exists($record, 'update')) {
                        $record->update(['user_id' => $userId]);
                    }

                    $userName = $userValue->name ?? '--';

                    return is_string($userName) ? $userName : '--';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                }),
            'first_name' => TextColumn::make('first_name')->sortable()->searchable(),
            'last_name' => TextColumn::make('last_name')->sortable()->searchable(),
            'email' => TextColumn::make('email')->sortable()->searchable(),
            'is_active' => IconColumn::make('is_active')->boolean(),
            'photo' => SpatieMediaLibraryImageColumn::make('photo')->collection('profile'),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getTableFilters(): array
    {
        return [
            'is_active' => TernaryFilter::make('is_active')
                ->placeholder(static::trans('filters.is_active.all'))
                ->trueLabel(static::trans('filters.is_active.active'))
                ->falseLabel(static::trans('filters.is_active.inactive'))
                ->queries(
<<<<<<< HEAD
<<<<<<< HEAD
                    true: static fn (Builder $query) => $query->where('is_active', '=', true),
                    false: static fn (Builder $query) => $query->where('is_active', '=', false),
=======
                    true: static fn(Builder $query) => $query->where('is_active', '=', true),
                    false: static fn(Builder $query) => $query->where('is_active', '=', false),
>>>>>>> f548be94 (.)
=======
                    true: static fn(Builder $query) => $query->where('is_active', '=', true),
                    false: static fn(Builder $query) => $query->where('is_active', '=', false),
=======
                    true: static fn (Builder $query) => $query->where('is_active', '=', true),
                    false: static fn (Builder $query) => $query->where('is_active', '=', false),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ),
        ];
    }
}
