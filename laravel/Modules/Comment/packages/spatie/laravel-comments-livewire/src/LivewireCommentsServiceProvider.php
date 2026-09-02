<?php

namespace Spatie\LivewireComments;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Livewire\Drawer\Utils;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LivewireComments\Livewire\CommentComponent;
use Spatie\LivewireComments\Livewire\CommentsComponent;
use Spatie\LivewireComments\Livewire\MentionSearchComponent;
use Spatie\LivewireComments\Support\Config;

class LivewireCommentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-comments')
            ->hasViews('comments');
    }

    public function packageBooted()
    {
        $this
            ->registerComponents()
            ->registerPolicies();

        Route::get('_laravel-comments-livewire/scripts.js', function () {
            return Utils::pretendResponseIsFile(__DIR__.'/../resources/dist/comments.js');
        })->name('laravel-comments-livewire.scripts');

        Route::get('_laravel-comments-livewire/styles.css', function () {
            return Utils::pretendResponseIsFile(__DIR__.'/../resources/dist/comments.css', 'text/css');
        })->name('laravel-comments-livewire.styles');

        Blade::directive('laravelCommentsLivewireScripts', function () {
            $version = InstalledVersions::getPrettyVersion('spatie/laravel-comments-livewire');

            $path = route('laravel-comments-livewire.scripts');

            return <<<HTML
                <script src="{$path}?v={$version}"></script>
            HTML;
        });

        Blade::directive('laravelCommentsLivewireStyles', function () {
            $version = InstalledVersions::getPrettyVersion('spatie/laravel-comments-livewire');

            $path = route('laravel-comments-livewire.styles');

            return <<<HTML
                <link rel="stylesheet" href="{$path}?v={$version}">
            HTML;
        });
    }

    protected function registerComponents(): self
    {
        Blade::componentNamespace('Spatie\\LivewireComments\\View\\Components', 'comments');

        Livewire::component('comments', CommentsComponent::class);
        Livewire::component('comments-comment', CommentComponent::class);
        Livewire::component('comments-mention-search', MentionSearchComponent::class);

        return $this;
    }

    public function registerPolicies(): self
    {
        Gate::define('createComment', [Config::commentPolicyClass(), 'create']);

        Gate::policy(Config::commentModelClass(), Config::commentPolicyClass());
        Gate::policy(Config::reactionModelClass(), Config::reactionPolicyClass());

        return $this;
    }
}
