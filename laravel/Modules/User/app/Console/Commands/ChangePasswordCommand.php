<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Exception;
>>>>>>> f548be94 (.)
=======
use Exception;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Modules\User\Datas\PasswordData;
use Modules\User\Events\NewPasswordSet;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

<<<<<<< HEAD
<<<<<<< HEAD
class ChangePasswordCommand extends Command
{
    protected $signature = 'user:change-password {--email= : Email dell\'utente}';
=======
=======
>>>>>>> 87273113 (.)
use function Laravel\Prompts\password;

class ChangePasswordCommand extends Command
{
    protected $signature = 'user:change-password';
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
class ChangePasswordCommand extends Command
{
    protected $signature = 'user:change-password {--email= : Email dell\'utente}';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    protected $description = 'Change user password';

    public function handle(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $emailInput = $this->option('email') ?? $this->ask('Enter the user email:');
        Assert::string($emailInput);

        $email = strtolower(trim($emailInput));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email non valida: '.$emailInput);
=======
=======
>>>>>>> 87273113 (.)
        Assert::string($email = $this->ask('Enter the user email:'));
        try {
            $user = XotData::make()->getUserByEmail($email);
        } catch (Exception $e) {
            $this->error($e->getMessage());
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $emailInput = $this->option('email') ?? $this->ask('Enter the user email:');
        Assert::string($emailInput);

        $email = strtolower(trim($emailInput));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email non valida: '.$emailInput);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $user = XotData::make()->findUserByEmail($email);

        if (null === $user) {
            $this->error("Utente non trovato per email: {$email}");
=======
=======
>>>>>>> 87273113 (.)
        // Ensure we fetched a persisted user and not a transient instance to avoid accidental insert
        if (!$user->exists()) {
            Assert::false(
                $user->exists(),
                __FILE__ . ':' . __LINE__ . ' - ' . class_basename(__CLASS__) . ' - User model should exist in database before password change'
            );
            $this->error('User not found or not persisted. Please create the user first (name, email, type, etc.).');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $user = XotData::make()->findUserByEmail($email);

        if (null === $user) {
            $this->error("Utente non trovato per email: {$email}");
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return;
        }

        Assert::string($password = $this->secret('Enter the new password:'));
        $confirmPassword = $this->secret('Confirm the new password:');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match!');

            return;
        }
<<<<<<< HEAD
<<<<<<< HEAD

        $pwdData = PasswordData::make();
        $passwordExpiryDateTime = now()->addDays($pwdData->expires_in);

=======
=======
>>>>>>> 87273113 (.)
        $pwd_data = PasswordData::make();
        $passwordExpiryDateTime = now()->addDays($pwd_data->expires_in);
        /*
         * $user->is_otp = false;
         * $user->password = Hash::make($password);
         * $user->save();
         */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

        $pwdData = PasswordData::make();
        $passwordExpiryDateTime = now()->addDays($pwdData->expires_in);

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $user = tap($user)->update([
            'password_expires_at' => $passwordExpiryDateTime,
            'is_otp' => false,
            'password' => Hash::make($password),
        ]);

        event(new NewPasswordSet($user));

        $this->info('Password changed successfully!');
    }
}
