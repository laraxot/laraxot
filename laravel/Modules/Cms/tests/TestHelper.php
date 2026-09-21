<?php

declare(strict_types=1);

namespace Modules\Cms\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Modules\Cms\Models\Module;
use Modules\User\Models\User as CmsUser;
use Modules\Xot\Actions\Filament\GetModulesNavigationItems;

abstract class TestHelper extends BaseTestCase
{
    public ?CmsUser $super_admin_user = null;

    public ?CmsUser $no_super_admin_user = null;

    public function getSuperAdminUser(): ?CmsUser
    {
        /** @var CmsUser|null $user */
        $user = CmsUser::role('super-admin')->first();

        return $user;
    }

    public function getNoSuperAdminUser(): ?CmsUser
    {
        /** @var CmsUser|null $user */
        $user = CmsUser::all()
            ->first(fn (CmsUser $item): bool => ! $item->hasRole('super-admin'));

        return $user;
    }

    /**
     * @return list<string>
     */
    public function getModuleNameLists(): array
    {
        /** @var list<string> $names */
        $names = collect(app(Module::class)->getRows())->pluck('name')->all();

        return $names;
    }

    /**
     * @return Collection<int, string|null>
     */
    public function getMainAdminNavigationUrlItems(): Collection
    {
        return collect(app(GetModulesNavigationItems::class)->execute())
            ->map(fn (mixed $item): ?string => $item->getUrl());
    }

    /**
     * @return Collection<int, string>
     */
    public function getUserNavigationItemUrlRoles(CmsUser $user): Collection
    {
        /** @var Collection<int, string> $urls */
        $urls = $user
            ->getRoleNames()
            ->map(function (mixed $item): ?string {
                if (! is_string($item) || 'super-admin' === $item) {
                    return null;
                }

                return '/'.mb_substr($item, 0, -7).'/admin';
            })
            ->filter(fn (?string $value): bool => ! is_null($value))
            ->values();

        return $urls;
    }
}
