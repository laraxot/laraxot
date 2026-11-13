<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\String;

use Spatie\QueueableAction\QueueableAction;

class GetPronounceablePasswordAction
{
    use QueueableAction;

    /**
<<<<<<< HEAD
     * Genera una password con lettere, cifra e simbolo usando casualità crittografica.
     *
     * @param  int  $length  Lunghezza richiesta (default: 12, minimo: 5)
     */
    public function execute(int $length = 12): string
    {
        $length = max(5, $length);
        $vowels = 'aeiou';
        $consonants = 'bcdfghjklmnprstvwxyz';
        $specials = '!#*-_=+:?';
        $password = '';

        for ($index = 0; $index < $length - 3; $index++) {
            $alphabet = $index % 2 === 0 ? $consonants : $vowels;
            $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $uppercase = strtoupper($password[random_int(0, strlen($password) - 1)]);
        $password .= $uppercase.random_int(0, 9).$specials[random_int(0, strlen($specials) - 1)];

        for ($index = strlen($password) - 1; $index > 0; $index--) {
            $swapIndex = random_int(0, $index);
            $character = $password[$index];
            $password[$index] = $password[$swapIndex];
            $password[$swapIndex] = $character;
        }

        return $password;
=======
     * Genera una password pronunciabile con caratteri speciali e numeri.
     *
     * @param  int  $length  Lunghezza minima della password (default: 12)
     * @return string Password generata
     */
    public function execute(int $length = 12): string
    {
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        $consonants = [
            'b',
            'c',
            'd',
            'f',
            'g',
            'h',
            'j',
            'k',
            'l',
            'm',
            'n',
            'p',
            'r',
            's',
            't',
            'v',
            'w',
            'x',
            'y',
            'z',
        ];

        $password = '';
        $useConsonant = true;

        // Costruisci la parte pronunciabile alternando consonanti e vocali
        while (strlen($password) < $length - 4) {
            $char = $useConsonant ? $consonants[array_rand($consonants)] : $vowels[array_rand($vowels)];
            $password .= $char;
            $useConsonant = ! $useConsonant;
        }

        // Verifica che la password non sia vuota prima di accedere agli offset
        if (strlen($password) === 0) {
            // Fallback: genera almeno una consonante e una vocale
            $password = $consonants[array_rand($consonants)].$vowels[array_rand($vowels)];
        }

        // Aggiungi almeno:
        // - 1 maiuscola
        // - 1 cifra
        // - 1 speciale
        $passwordLength = strlen($password);
        $randomIndex = rand(0, $passwordLength - 1);
        $uppercase = strtoupper($password[$randomIndex]);
        $digit = strval(rand(0, 9));
        $specials = '!#*-_=+:?';
        $special = $specials[rand(0, strlen($specials) - 1)];

        // Evita duplicazioni semplici: aggiungi un'altra minuscola casuale
        $password .= $uppercase.$digit.$special;

        // Shuffle finale per rendere la password meno prevedibile
        $shuffled = str_shuffle($password);

        return trim($shuffled);
>>>>>>> 77b1e2748 (first)
    }
}
