<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Support\Contracts\HasLabel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Xot\Actions\Cast\SafeObjectCastAction;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Actions\Cast\SafeObjectCastAction;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Symfony\Component\Console\Input\InputOption;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Support\Contracts\HasLabel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Xot\Actions\Cast\SafeObjectCastAction;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * Command to change user type based on project configuration.
 *
 * This command allows administrators to change the type of a user
 * by selecting from available child types in the system.
 */
class ChangeTypeCommand extends Command
{
    /**
     * The name and signature of the console command.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var string
>>>>>>> f548be94 (.)
=======
     *
     * @var string
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $name = 'user:change-type';

    /**
     * The console command description.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var string
>>>>>>> f548be94 (.)
=======
     *
     * @var string
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $description = 'Change user type based on project configuration';

    /**
     * Create a new command instance.
<<<<<<< HEAD
<<<<<<< HEAD
     */

    /**
     * Execute the console command.
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return void
     */
    

    /**
     * Execute the console command.
     *
     * @return void
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */

    /**
     * Execute the console command.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function handle(): void
    {
        $xot = XotData::make();
        $email = text('User email?');

        /** @var UserContract $user */
        $user = XotData::make()->getUserByEmail($email);

<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return;
        }
        if (! method_exists($user, 'getChildTypes')) {
            $this->error('User model does not have childTypes method.');

=======
=======
>>>>>>> 87273113 (.)
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return;
        }
        if (!method_exists($user, 'getChildTypes')) {
            $this->error('User model does not have childTypes method.');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return;
        }
        if (! method_exists($user, 'getChildTypes')) {
            $this->error('User model does not have childTypes method.');

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        $childTypes = $xot->getUserChildTypes();
<<<<<<< HEAD
<<<<<<< HEAD

        // Get type label - BackedEnum needs HasLabel implementation
        $typeLabel = 'None';
        if (isset($user->type) && \is_object($user->type) && method_exists($user->type, 'getLabel')) {
            $enumType = $user->type;
            /** @var string|Htmlable|mixed */
            $label = $enumType->getLabel();
            if (\is_string($label)) {
                $typeLabel = $label;
            } elseif ($label instanceof Htmlable) {
                $typeLabel = $label->toHtml();
            } elseif (\is_scalar($label) || $label instanceof \Stringable) {
                $typeLabel = (string) $label;
            }
        }

        Assert::string($typeLabel);
        $this->info('Current user type: '.$typeLabel);
=======
=======
>>>>>>> 87273113 (.)
        /** @phpstan-ignore nullsafe.neverNull */
        $typeLabel = $user->type?->getLabel() ?? 'None';
        $typeLabelString = is_string($typeLabel) ? $typeLabel : $typeLabel->toHtml();
        $this->info("Current user type: " . $typeLabelString);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

        // Get type label - BackedEnum needs HasLabel implementation
        $typeLabel = 'None';
        if (isset($user->type) && \is_object($user->type) && method_exists($user->type, 'getLabel')) {
            $enumType = $user->type;
            /** @var string|Htmlable|mixed */
            $label = $enumType->getLabel();
            if (\is_string($label)) {
                $typeLabel = $label;
            } elseif ($label instanceof Htmlable) {
                $typeLabel = $label->toHtml();
            } elseif (\is_scalar($label) || $label instanceof \Stringable) {
                $typeLabel = (string) $label;
            }
        }

        Assert::string($typeLabel);
        $this->info('Current user type: '.$typeLabel);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $typeClass = $xot->getUserChildTypeClass();
        /** @var array<string, string> */
        $options = [];
        foreach ($childTypes as $key => $item) {
            if (
<<<<<<< HEAD
<<<<<<< HEAD
                \is_object($item)
                    && method_exists($item, 'getLabel')
                    && app(SafeObjectCastAction::class)->hasNonNullProperty($item, 'value')
            ) {
                $value = app(SafeObjectCastAction::class)
                    ->getStringProperty($item, 'value', '');
                $label = $item->getLabel();
                $options[$value] = \is_scalar($label) || $label instanceof \Stringable ? (string) $label : 'Unknown';
=======
=======
>>>>>>> 87273113 (.)
                is_object($item) &&
                    method_exists($item, 'getLabel') &&
                    app(SafeObjectCastAction::class)->hasNonNullProperty($item, 'value')
            ) {
                $value = app(SafeObjectCastAction::class)
                    ->getStringProperty($item, 'value', '');
                $options[$value] = (string) $item->getLabel();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                \is_object($item)
                    && method_exists($item, 'getLabel')
                    && app(SafeObjectCastAction::class)->hasNonNullProperty($item, 'value')
            ) {
                $value = app(SafeObjectCastAction::class)
                    ->getStringProperty($item, 'value', '');
                $label = $item->getLabel();
                $options[$value] = \is_scalar($label) || $label instanceof \Stringable ? (string) $label : 'Unknown';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            } else {
                $options[(string) $key] = 'Unknown';
            }
        }

        $newType = select('Select new user type:', $options);

        $newTypeEnum = $typeClass::tryFrom($newType);
<<<<<<< HEAD
<<<<<<< HEAD
        if ($newTypeEnum === null) {
            throw new \InvalidArgumentException('Invalid user type selected.');
        }
        Assert::isInstanceOf($newTypeEnum, HasLabel::class);
        Assert::isInstanceOf($newTypeEnum, \BackedEnum::class);

        /* @var \BackedEnum&HasLabel $newTypeEnum */
        $user->type = (string) $newTypeEnum->value;
        $user->save();

        $label = $newTypeEnum->getLabel();
        $labelString = '';
        if (\is_string($label)) {
            $labelString = $label;
        } elseif ($label instanceof Htmlable) {
            $labelString = $label->toHtml();
        } else {
            $labelString = (string) $label;
        }
        $this->info("User type changed to '{$labelString}' for {$email}");
=======
=======
>>>>>>> 87273113 (.)
        Assert::notNull($newTypeEnum);

        $user->type = $newTypeEnum;
        $user->save();

        $this->info("User type changed to '{$newTypeEnum->getLabel()}' for {$email}");
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if ($newTypeEnum === null) {
            throw new \InvalidArgumentException('Invalid user type selected.');
        }
        Assert::isInstanceOf($newTypeEnum, HasLabel::class);
        Assert::isInstanceOf($newTypeEnum, \BackedEnum::class);

        /* @var \BackedEnum&HasLabel $newTypeEnum */
        $user->type = (string) $newTypeEnum->value;
        $user->save();

        $label = $newTypeEnum->getLabel();
        $labelString = '';
        if (\is_string($label)) {
            $labelString = $label;
        } elseif ($label instanceof Htmlable) {
            $labelString = $label->toHtml();
        } else {
            $labelString = (string) $label;
        }
        $this->info("User type changed to '{$labelString}' for {$email}");
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
