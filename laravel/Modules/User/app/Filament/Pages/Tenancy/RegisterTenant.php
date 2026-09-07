<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Tenancy;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Contracts\TenantContract;
use Modules\User\Models\BaseTenant;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseRegisterTenant;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;

class RegisterTenant extends XotBaseRegisterTenant
{
    use TransTrait;

    /**
     * @var class-string|null
     */
    private ?string $resourceClass = null;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Contracts\TenantContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;

class RegisterTenant extends BaseRegisterTenant
{
    use TransTrait;

    public string $resource;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Contracts\TenantContract;
use Modules\User\Models\BaseTenant;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseRegisterTenant;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;

class RegisterTenant extends XotBaseRegisterTenant
{
    use TransTrait;

    /**
     * @var class-string|null
     */
    private ?string $resourceClass = null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public static function getLabel(): string
    {
        $tenantClass = XotData::make()->getTenantClass();
        $func = Str::of(__FUNCTION__)->snake()->toString();
        if (Str::startsWith($func, 'get_')) {
            $func = Str::of($func)->after('get_')->toString();
        }
<<<<<<< HEAD
<<<<<<< HEAD
        $key = Str::of(class_basename(self::class))
            ->snake()
            ->prepend('actions.')
            ->append('.'.$func)
            ->toString();

        return static::transClass($tenantClass, $key);
    }

    public function schema(Schema $schema): Schema
    {
        /** @var array<Component> $components */
        $components = $this->getFormSchema();

        return $schema->components($components);
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        $resourceClass = $this->resolveResourceClass();
        $schema = $resourceClass::getFormSchema();
        Assert::isArray($schema);

        $components = [];
        foreach ($schema as $key => $component) {
            Assert::isInstanceOf($component, Component::class);
            $components[$key] = $component;
        }

        return $components;
    }

    /**
     * @param  array<string, string|int|bool|null>  $data
=======
=======
>>>>>>> 87273113 (.)
        $key = Str::of(class_basename(__CLASS__))
            ->snake()
            ->prepend('actions.')
            ->append('.' . $func)
            ->toString();
        $str = static::transClass($tenantClass, $key);

        return $str;
    }

    public function form(Schema $schema): Schema
    {
        $tenantClass = XotData::make()->getTenantClass();
        $resource = Str::of($tenantClass)
            ->replace('\Models\\', '\Filament\Resources\\')
            ->append('Resource')
            ->toString();
        $this->resource = $resource;
        return $schema->components($this->getFormSchema());
    }

    public function getFormSchema(): array
    {
        return $this->resource::getFormSchema();
    }

    /**
     * @param  array<string, mixed>  $data
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $key = Str::of(class_basename(self::class))
            ->snake()
            ->prepend('actions.')
            ->append('.'.$func)
            ->toString();

        return static::transClass($tenantClass, $key);
    }

    public function schema(Schema $schema): Schema
    {
        /** @var array<Component> $components */
        $components = $this->getFormSchema();

        return $schema->components($components);
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        $resourceClass = $this->resolveResourceClass();
        $schema = $resourceClass::getFormSchema();
        Assert::isArray($schema);

        $components = [];
        foreach ($schema as $key => $component) {
            Assert::isInstanceOf($component, Component::class);
            $components[$key] = $component;
        }

        return $components;
    }

    /**
     * @param  array<string, string|int|bool|null>  $data
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function handleRegistration(array $data): Model
    {
        $tenantClass = XotData::make()->getTenantClass();

        $tenant = $tenantClass::create($data);
        Assert::implementsInterface($tenant, TenantContract::class);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::isInstanceOf($tenant, BaseTenant::class);

        return $tenant;
    }

    /**
     * @return class-string
     */
    private function resolveResourceClass(): string
    {
        if ($this->resourceClass !== null) {
            return $this->resourceClass;
        }

        $tenantClass = XotData::make()->getTenantClass();
        $resourceClass = Str::of($tenantClass)
            ->replace('\\Models\\', '\\Filament\\Resources\\')
            ->append('Resource')
            ->toString();

        Assert::classExists($resourceClass);

        /** @var class-string $resolved */
        $resolved = $resourceClass;
        $this->resourceClass = $resolved;

        return $resolved;
    }
=======
=======
>>>>>>> 87273113 (.)

        $tenant->users()->attach(auth()->user());

        return $tenant;
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::isInstanceOf($tenant, BaseTenant::class);

        return $tenant;
    }

    /**
     * @return class-string
     */
    private function resolveResourceClass(): string
    {
        if ($this->resourceClass !== null) {
            return $this->resourceClass;
        }

        $tenantClass = XotData::make()->getTenantClass();
        $resourceClass = Str::of($tenantClass)
            ->replace('\\Models\\', '\\Filament\\Resources\\')
            ->append('Resource')
            ->toString();

        Assert::classExists($resourceClass);

        /** @var class-string $resolved */
        $resolved = $resourceClass;
        $this->resourceClass = $resolved;

        return $resolved;
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
