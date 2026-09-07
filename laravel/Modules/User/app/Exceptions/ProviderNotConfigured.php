<?php

declare(strict_types=1);

namespace Modules\User\Exceptions;

<<<<<<< HEAD
<<<<<<< HEAD
final class ProviderNotConfigured extends \LogicException
{
    public static function make(string $provider): static
    {
        return new self('Provider "'.
            $provider.
            '" is not configured. tips: add '.
            $provider.
=======
=======
>>>>>>> 87273113 (.)
use LogicException;

final class ProviderNotConfigured extends LogicException
{
    public static function make(string $provider): static
    {
        return new self('Provider "' .
            $provider .
            '" is not configured. tips: add ' .
            $provider .
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
final class ProviderNotConfigured extends \LogicException
{
    public static function make(string $provider): static
    {
        return new self('Provider "'.
            $provider.
            '" is not configured. tips: add '.
            $provider.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ' to config/services.php');
    }
}
