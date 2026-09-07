<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Modules\User\Models\Profile;
<<<<<<< HEAD
<<<<<<< HEAD

class ProfileResource extends BaseProfileResource
{
    protected static ?string $model = Profile::class;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class ProfileResource extends BaseProfileResource
{
    protected static null|string $model = Profile::class;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

class ProfileResource extends BaseProfileResource
{
    protected static ?string $model = Profile::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
