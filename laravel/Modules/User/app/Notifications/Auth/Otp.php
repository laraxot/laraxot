<?php

declare(strict_types=1);

namespace Modules\User\Notifications\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\User\Datas\PasswordData;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Contracts\UserContract;

class Otp extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public UserContract $user,
        public string $code,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param  mixed  $_notifiable  L'entità da notificare
=======
     * @param  mixed  $_notifiable L'entità da notificare
>>>>>>> f548be94 (.)
=======
     * @param  mixed  $_notifiable L'entità da notificare
=======
     * @param  mixed  $_notifiable  L'entità da notificare
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<int, string>
     */
    public function via(mixed $_notifiable): array
    {
        return ['mail']; // Puoi aggiungere anche 'database', 'slack', ecc. se vuoi supportare altri canali.
    }

    /**
     * Get the mail representation of the notification.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function toMail(AnonymousNotifiable $notifiable): MailMessage
    {
        $pwd = PasswordData::make();
        $app_name = SafeStringCastAction::cast(config('app.name'));

        $mailMessage = new MailMessage;
        $mailMessage = $mailMessage->template('user::notifications.email');
        $mailMessage = $mailMessage->subject(SafeStringCastAction::cast(__('user::otp.mail.subject')));
        $mailMessage = $mailMessage->greeting(SafeStringCastAction::cast(__('user::otp.mail.greeting')));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line1', ['code' => $this->code])));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line2', ['minutes' => $pwd->otp_expiration_minutes])));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line3')));
        $mailMessage = $mailMessage->action('vai', url('/'));

        return $mailMessage
            ->salutation(SafeStringCastAction::cast(__('user::otp.mail.salutation', ['app_name' => $app_name])));
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param AnonymousNotifiable $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $pwd = PasswordData::make();
        /** @var string */
        $app_name = config('app.name');

        return new MailMessage()
            ->template('user::notifications.email')
            ->subject(__('user::otp.mail.subject'))
            ->greeting(__('user::otp.mail.greeting'))
            ->line(__('user::otp.mail.line1', ['code' => $this->code]))
            ->line(__('user::otp.mail.line2', ['minutes' => $pwd->otp_expiration_minutes]))
            ->line(__('user::otp.mail.line3'))
            ->action('vai', url('/'))
            ->salutation(__('user::otp.mail.salutation', ['app_name' => $app_name]));
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function toMail(AnonymousNotifiable $notifiable): MailMessage
    {
        $pwd = PasswordData::make();
        $app_name = SafeStringCastAction::cast(config('app.name'));

        $mailMessage = new MailMessage;
        $mailMessage = $mailMessage->template('user::notifications.email');
        $mailMessage = $mailMessage->subject(SafeStringCastAction::cast(__('user::otp.mail.subject')));
        $mailMessage = $mailMessage->greeting(SafeStringCastAction::cast(__('user::otp.mail.greeting')));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line1', ['code' => $this->code])));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line2', ['minutes' => $pwd->otp_expiration_minutes])));
        $mailMessage = $mailMessage->line(SafeStringCastAction::cast(__('user::otp.mail.line3')));
        $mailMessage = $mailMessage->action('vai', url('/'));

        return $mailMessage
            ->salutation(SafeStringCastAction::cast(__('user::otp.mail.salutation', ['app_name' => $app_name])));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the array representation of the notification.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<string, mixed>
     */
    public function toArray(UserContract $notifiable): array
=======
     * @return array
     */
    public function toArray(UserContract $notifiable)
>>>>>>> f548be94 (.)
=======
     * @return array
     */
    public function toArray(UserContract $notifiable)
=======
     * @return array<string, mixed>
     */
    public function toArray(UserContract $notifiable): array
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return [];
    }
}
