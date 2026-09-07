<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Tenancy;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Schemas\Schema;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseEditTenantProfile;
use Webmozart\Assert\Assert;

class EditTenantProfile extends XotBaseEditTenantProfile
=======
=======
>>>>>>> 87273113 (.)
use Filament\Pages\Tenancy\EditTenantProfile as BaseEditTenantProfile;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;
use Filament\Schemas\Schema;

class EditTenantProfile extends BaseEditTenantProfile
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Schemas\Schema;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseEditTenantProfile;
use Webmozart\Assert\Assert;

class EditTenantProfile extends XotBaseEditTenantProfile
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    public static function getLabel(): string
    {
        return __('user::tenancy.navigation.edit');
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function schema(Schema $schema): Schema
    {
        $resource = XotData::make()->getTenantResourceClass();

        Assert::isInstanceOf($res = $resource::schema($schema), Schema::class);
=======
=======
>>>>>>> 87273113 (.)
    public function form(Schema $schema): Schema
    {
        $resource = XotData::make()->getTenantResourceClass();

        Assert::isInstanceOf($res = $resource::form($schema), Schema::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function schema(Schema $schema): Schema
    {
        $resource = XotData::make()->getTenantResourceClass();

        Assert::isInstanceOf($res = $resource::schema($schema), Schema::class);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return $res;

        /*
         * return $form
         * ->schema([
         * TextInput::make('name')
         * ->required()
         * ->translateLabel(),
         * TextInput::make('phone')
         * ->required()
         * ->tel()
         * ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
         * ->translateLabel(),
         * TextInput::make('email')
         * ->required()
         * ->email()
         * ->translateLabel(),
         * ]);
         */
    }
}
