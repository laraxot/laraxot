<?php

declare(strict_types=1);

namespace Modules\Comment\Providers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;
use Modules\Comment\Policies\CommentPolicy;
use Modules\Comment\Policies\ReactionPolicy;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

/**
 * Boot unificato motore commenti Laraxot (strangler fig da packages/spatie).
 */
class CommentEngineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->resolveCommentatorModel();
        $this->registerBladeNamespace();
        $this->registerViewComposers();

        $this->app->booted(function (): void {
            $this->registerPolicies();
        });
    }

    protected function registerPolicies(): void
    {
        $config = CommentConfigData::make();
        $commentPolicy = $config->policies['comment'];
        $reactionPolicy = $config->policies['reaction'];
        $commentModel = $config->models['comment'];
        $reactionModel = $config->models['reaction'];

        Assert::string($commentPolicy);
        Assert::string($reactionPolicy);
        Assert::string($commentModel);
        Assert::string($reactionModel);

        Gate::define('createComment', [$commentPolicy, 'create']);
        Gate::policy($commentModel, $commentPolicy);
        Gate::policy($reactionModel, $reactionPolicy);
    }

    protected function registerBladeNamespace(): void
    {
        Blade::componentNamespace('Modules\\Comment\\View\\Components', 'comment');
    }

    protected function registerViewComposers(): void
    {
        View::composer('comment::*', static function (ViewContract $view): void {
            $view->with('commentConfig', CommentConfigData::make());
        });
    }

    protected function resolveCommentatorModel(): void
    {
        $configured = CommentConfigData::make()->models['commentator'] ?? null;
        if (is_string($configured) && $configured !== '') {
            return;
        }

        try {
            $userClass = XotData::make()->getUserClass();
        } catch (\Throwable) {
            return;
        }

        if (! is_subclass_of($userClass, CanComment::class)) {
            return;
        }

        config(['comments.models.commentator' => $userClass]);
    }
}
