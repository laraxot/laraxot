<?php

declare(strict_types=1);

namespace Modules\User\Enums;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

enum SocialProviderEnum: string implements HasLabel
{
    use EnumTrait;
=======
enum SocialProviderEnum: string
{
>>>>>>> f548be94 (.)
=======
enum SocialProviderEnum: string
{
=======
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

enum SocialProviderEnum: string implements HasLabel
{
    use EnumTrait;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    case GOOGLE = 'google';
    case AUTH0 = 'auth0';
}
