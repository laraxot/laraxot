<?php

declare(strict_types=1);

namespace Modules\User\Notifications\Auth;

use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Illuminate\Notifications\Notification;
>>>>>>> f548be94 (.)
=======
use Illuminate\Notifications\Notification;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Facades\Lang;
use Webmozart\Assert\Assert;

class ResetPassword extends BaseNotification
{
    public string $url;

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }

    /**
     * Get the reset password notification mail message for the given URL.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    protected function buildMailMessage($url): MailMessage
    {
        Assert::string($url, 'URL must be a string');
        Assert::string($subject = Lang::get('user::email.password_reset_subject'));
        Assert::string($action = Lang::get('user::email.reset_password'));

        $mailMessage = new MailMessage();
        $mailMessage = $mailMessage->subject($subject);
        $mailMessage = $mailMessage->line(Lang::get('user::email.password_cause_of_email'));
        $mailMessage = $mailMessage->action($action, $url);

        // $mailMessage = $mailMessage->line(Lang::get('user::email.password_reset_expiration', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]));
        return $mailMessage->line(Lang::get('user::email.password_if_not_requested'));
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param  string $url
     * @return MailMessage
     */
    protected function buildMailMessage($url)
    {
        Assert::string($subject = Lang::get('user::email.password_reset_subject'));
        Assert::string($action = Lang::get('user::email.reset_password'));

        return new MailMessage()
            ->subject($subject)
            ->line(Lang::get('user::email.password_cause_of_email'))
            ->action($action, $url)
            // ->line(Lang::get('user::email.password_reset_expiration', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('user::email.password_if_not_requested'));
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    protected function buildMailMessage($url): MailMessage
    {
        Assert::string($url, 'URL must be a string');
        Assert::string($subject = Lang::get('user::email.password_reset_subject'));
        Assert::string($action = Lang::get('user::email.reset_password'));

        $mailMessage = new MailMessage();
        $mailMessage = $mailMessage->subject($subject);
        $mailMessage = $mailMessage->line(Lang::get('user::email.password_cause_of_email'));
        $mailMessage = $mailMessage->action($action, $url);

        // $mailMessage = $mailMessage->line(Lang::get('user::email.password_reset_expiration', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]));
        return $mailMessage->line(Lang::get('user::email.password_if_not_requested'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
