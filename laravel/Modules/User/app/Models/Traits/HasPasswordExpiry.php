<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Modules\User\Datas\PasswordData;

/** @phpstan-ignore trait.unused */
trait HasPasswordExpiry
{
    public static function bootHasPasswordExpiry(): void
    {
        $pwd = PasswordData::make();

        static::creating(static function (Model $model) use ($pwd): void {
            if (filled($model->getAttribute('password'))) {
                $model->setAttribute('password_expires_at', now()->addDays($pwd->expires_in));
            }
        });

        static::updating(static function (Model $model) use ($pwd): void {
            if ($model->isDirty('password') && filled($model->getAttribute('password'))) {
                $model->setAttribute('password_expires_at', now()->addDays($pwd->expires_in));
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Datas\PasswordData;
use Modules\Xot\Actions\Model\HasColumnAction;

trait HasPasswordExpiry
{
    /**
     * Summary of bootHasPasswordExpiry.
     *
     * @return void
     */
    public static function bootHasPasswordExpiry()
    {
        // if (! app(HasColumnAction::class)->execute(auth()->user(), 'password_expires_at')) {
        //    dddx('a');
        // }
        $pwd = PasswordData::make();
        static::creating(function ($model) use ($pwd) {
            if (filled($model->password)) {
                $model->password_expires_at = now()->addDays($pwd->expires_in);
            }
        });

        static::updating(function ($model) use ($pwd) {
            if ($model->isDirty('password') && filled($model->password)) {
                $model->password_expires_at = now()->addDays($pwd->expires_in);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Datas\PasswordData;

/** @phpstan-ignore trait.unused */
trait HasPasswordExpiry
{
    public static function bootHasPasswordExpiry(): void
    {
        $pwd = PasswordData::make();

        static::creating(static function (Model $model) use ($pwd): void {
            if (filled($model->getAttribute('password'))) {
                $model->setAttribute('password_expires_at', now()->addDays($pwd->expires_in));
            }
        });

        static::updating(static function (Model $model) use ($pwd): void {
            if ($model->isDirty('password') && filled($model->getAttribute('password'))) {
                $model->setAttribute('password_expires_at', now()->addDays($pwd->expires_in));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
        });
    }
}
