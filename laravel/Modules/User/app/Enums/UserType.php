<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\User\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Traits\EnumTrait;
=======
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Traits\EnumTrait;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

// use Datomatic\LaravelEnumHelper\LaravelEnumHelper;

enum UserType: string implements HasColor, HasIcon, HasLabel
{
<<<<<<< HEAD
<<<<<<< HEAD
    use EnumTrait;

=======
>>>>>>> f548be94 (.)
=======
=======
    use EnumTrait;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    // //use LaravelEnumHelper;

    case MasterAdmin = 'master_admin';
    case BoUser = 'backoffice_user';
    case CustomerUser = 'customer_user';
    case System = 'system';
    case Technician = 'technician';

<<<<<<< HEAD
<<<<<<< HEAD
    private const string API = 'api';

    private const string WEB = 'web';
=======
    private const API = 'api';

    private const WEB = 'web';
>>>>>>> f548be94 (.)
=======
    private const API = 'api';

    private const WEB = 'web';
=======
    private const string API = 'api';

    private const string WEB = 'web';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function getDefaultGuard(): string
    {
        return match ($this) {
            self::MasterAdmin, self::System, self::CustomerUser, self::BoUser => self::WEB,
            self::Technician => self::API,
        };
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    public function getLabel(): string
    {
        return match ($this) {
            self::MasterAdmin => 'master_admin',
            self::BoUser => 'backoffice_user',
            self::CustomerUser => 'customer_user',
            self::System => 'system',
            self::Technician => 'technician',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MasterAdmin => 'success',
            self::BoUser => 'warning',
            self::CustomerUser => 'gray',
            self::System => 'blue',
            self::Technician => 'green',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::MasterAdmin => 'heroicon-m-pencil',
            self::BoUser => 'heroicon-m-pencil',
            self::CustomerUser => 'heroicon-m-pencil',
            self::System => 'heroicon-m-pencil',
            self::Technician => 'heroicon-m-pencil',
        };
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
