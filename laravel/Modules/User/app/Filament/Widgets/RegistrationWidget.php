<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Features\SupportRedirects\Redirector;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Webmozart\Assert\Assert;

class RegistrationWidget extends XotBaseSchemaWidget
{
    public string $type = '';

    /** @var class-string */
    public string $resource;

    /** @var class-string<Model> */
    public string $model = Model::class;

    public string $action = '';

    public Model $record;

    protected int|string|array $columnSpan = 'full';

    public function mount(string $type = ''): void
    {
        parent::mount();
        $this->type = $type;
        $resourceClass = XotData::make()->getUserResourceClassByType($type);
        Assert::classExists($resourceClass);
        $this->resource = $resourceClass;

        /** @var class-string<Model> $modelClass */
        $modelClass = $resourceClass::getModel();
        Assert::subclassOf($modelClass, Model::class);
        $this->model = $modelClass;

=======
=======
>>>>>>> 87273113 (.)
use Override;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Webmozart\Assert\Assert;

class RegistrationWidget extends XotBaseWidget
{
    public null|array $data = [];
    protected int|string|array $columnSpan = 'full';
    public string $type;
    public string $resource;
    public string $model;
    public string $action;
    public Model $record;

    /**
     * @phpstan-var class-string
     * @phpstan-ignore-next-line
     */
    protected string $view = 'pub_theme::filament.widgets.registration';

    public function mount(string $type, Request $_request): void
    {
        $this->type = $type;
        $this->resource = XotData::make()->getUserResourceClassByType($type);
        $this->model = $this->resource::getModel();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Features\SupportRedirects\Redirector;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Webmozart\Assert\Assert;

class RegistrationWidget extends XotBaseSchemaWidget
{
    public string $type = '';

    /** @var class-string */
    public string $resource;

    /** @var class-string<Model> */
    public string $model = Model::class;

    public string $action = '';

    public Model $record;

    protected int|string|array $columnSpan = 'full';

    public function mount(string $type = ''): void
    {
        parent::mount();
        $this->type = $type;
        $resourceClass = XotData::make()->getUserResourceClassByType($type);
        Assert::classExists($resourceClass);
        $this->resource = $resourceClass;

        /** @var class-string<Model> $modelClass */
        $modelClass = $resourceClass::getModel();
        Assert::subclassOf($modelClass, Model::class);
        $this->model = $modelClass;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $this->action = Str::of($this->model)
            ->replace('\\Models\\', '\\Actions\\')
            ->append('\\RegisterAction')
            ->toString();
        $record = $this->getFormModel();
        $data = $this->getFormFill();
<<<<<<< HEAD
<<<<<<< HEAD

        $this->data = $data;
        $this->form->fill($this->data);
=======
        $this->data = $data;
        $this->form->fill($data);
>>>>>>> f548be94 (.)
=======
        $this->data = $data;
        $this->form->fill($data);
=======

        $this->data = $data;
        $this->form->fill($this->data);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $this->form->model($record);
        $this->record = $record;
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getFormModel(): Model
    {
        $data = request()->all();
        $email = Arr::get($data, 'email');
        $token = Arr::get($data, 'token');

<<<<<<< HEAD
<<<<<<< HEAD
        $user = is_string($email)
            ? $this->model::firstWhere('email', $email)
            : null;
        if (! $user instanceof Model) {
            $model = app($this->model);
            Assert::isInstanceOf($model, Model::class);

            return $model;
        }

        $rememberToken = $user->getAttribute('remember_token');
        if (is_string($token) && '' !== $token) {
            $user->setAttribute('remember_token', $token);
            $user->save();
            $this->record = $user;

            return $user;
        }

        if (is_string($rememberToken) && $rememberToken === $token) {
            $this->record = $user;

            return $user;
        }

        $model = app($this->model);
        Assert::isInstanceOf($model, Model::class);

        return $model;
    }

    /**
     * @return array<string, mixed>
     */
    // @override
    public function getFormFill(): array
    {
        /** @var array<string, mixed> $data */
        $data = SafeArrayCastAction::cast(parent::getFormFill());
=======
=======
>>>>>>> 87273113 (.)
        $user = $this->model::firstWhere('email', $email);
        if ($user === null) {
            return app($this->model);
        }

        $remember_token = $user->remember_token;
        if ($remember_token === null) {
            $user->remember_token = Str::uuid()->toString();
            $user->save();
        }

        if ($remember_token === $token) {
            $this->record = $user;
            return $user;
        }

        return app($this->model);
    }

    #[Override]
    public function getFormFill(): array
    {
        $data = parent::getFormFill();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $user = is_string($email)
            ? $this->model::firstWhere('email', $email)
            : null;
        if (! $user instanceof Model) {
            $model = app($this->model);
            Assert::isInstanceOf($model, Model::class);

            return $model;
        }

        $rememberToken = $user->getAttribute('remember_token');
        if (is_string($token) && '' !== $token) {
            $user->setAttribute('remember_token', $token);
            $user->save();
            $this->record = $user;

            return $user;
        }

        if (is_string($rememberToken) && $rememberToken === $token) {
            $this->record = $user;

            return $user;
        }

        $model = app($this->model);
        Assert::isInstanceOf($model, Model::class);

        return $model;
    }

    /**
     * @return array<string, mixed>
     */
    // @override
    public function getFormFill(): array
    {
        /** @var array<string, mixed> $data */
        $data = SafeArrayCastAction::cast(parent::getFormFill());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $data['type'] = $this->type;

        return $data;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return self::normalizeFormSchema($this->resource::getFormSchemaWidget());
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function getFormSchema(): array
    {
        return $this->resource::getFormSchemaWidget();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return self::normalizeFormSchema($this->resource::getFormSchemaWidget());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * @see https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component
     */
<<<<<<< HEAD
<<<<<<< HEAD
    // @override
    public function register(): RedirectResponse|Redirector
    {
        $data = $this->form->getState();
        /** @var array<string, mixed> $initialData */
        $initialData = $this->data ?? [];
        $data = array_merge($initialData, $data);
        $record = $this->record;

        $actionInstance = app($this->action);
        if (! \is_object($actionInstance) || ! method_exists($actionInstance, 'execute')) {
            throw new \RuntimeException(\sprintf('Registration action [%s] must expose an execute method.', $this->action));
        }
        \call_user_func([$actionInstance, 'execute'], $record, $data);

        $lang = app()->getLocale();
        $route = route('pages.view', ['slug' => $this->type.'_register_complete']);
        $route = LaravelLocalization::localizeUrl($route, $lang);

        // return redirect()->route('pages.view', ['slug' => $this->type . '_register_complete','lang'=>$lang]);
        return redirect($route);
    }

    /**
     * @return array<int|string, Component>
     */
    private static function normalizeFormSchema(mixed $schema): array
    {
        if (! \is_array($schema)) {
            return [];
        }

        $normalized = [];
        foreach ($schema as $key => $component) {
            if (! $component instanceof Component) {
                return [];
            }

            $normalized[$key] = $component;
        }

        return $normalized;
    }
=======
=======
>>>>>>> 87273113 (.)
    public function register(): RedirectResponse|Redirector
    {
        $lang = app()->getLocale();

        $data = $this->form->getState();

        $data = array_merge($this->data ?? [], $data);
        $record = $this->record;

        $user = app($this->action)->execute($record, $data);

        $lang = app()->getLocale();
        $route = route('pages.view', ['slug' => $this->type . '_register_complete']);
        $route = LaravelLocalization::localizeUrl($route, $lang);

        //return redirect()->route('pages.view', ['slug' => $this->type . '_register_complete','lang'=>$lang]);
        return redirect($route);
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    // @override
    public function register(): RedirectResponse|Redirector
    {
        $data = $this->form->getState();
        /** @var array<string, mixed> $initialData */
        $initialData = $this->data ?? [];
        $data = array_merge($initialData, $data);
        $record = $this->record;

        $actionInstance = app($this->action);
        if (! \is_object($actionInstance) || ! method_exists($actionInstance, 'execute')) {
            throw new \RuntimeException(\sprintf('Registration action [%s] must expose an execute method.', $this->action));
        }
        \call_user_func([$actionInstance, 'execute'], $record, $data);

        $lang = app()->getLocale();
        $route = route('pages.view', ['slug' => $this->type.'_register_complete']);
        $route = LaravelLocalization::localizeUrl($route, $lang);

        // return redirect()->route('pages.view', ['slug' => $this->type . '_register_complete','lang'=>$lang]);
        return redirect($route);
    }

    /**
     * @return array<int|string, Component>
     */
    private static function normalizeFormSchema(mixed $schema): array
    {
        if (! \is_array($schema)) {
            return [];
        }

        $normalized = [];
        foreach ($schema as $key => $component) {
            if (! $component instanceof Component) {
                return [];
            }

            $normalized[$key] = $component;
        }

        return $normalized;
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
