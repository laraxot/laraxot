<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Facades\Process;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Classe per eseguire comandi Artisan in modo sicuro.
 */
class ExecuteArtisanCommandAction
{
    use QueueableAction;

    /**
     * Lista dei comandi consentiti per motivi di sicurezza.
     *
     * @var array<int, string>
     */
    private array $allowedCommands = [
        'migrate',
        'filament:upgrade',
        'filament:optimize',
        'view:cache',
        'config:cache',
        'route:cache',
        'event:cache',
        'queue:restart',
        'passport:install --uuids',
        'passport:keys',
        'passport:purge',
        'passport:hash',
        'notify:migrate-themes-to-mail-templates',
    ];

    /**
     * Esegue un comando Artisan e restituisce i risultati.
     *
<<<<<<< HEAD
     * @param  string  $command  Il comando Artisan da eseguire (senza "php artisan")
=======
<<<<<<< .merge_file_hZ4KPP
     * @param  string  $command  Il comando Artisan da eseguire (senza "php artisan")
=======
     * <<<<<<< HEAD
     *
     * @param string $command Il comando Artisan da eseguire (senza "php artisan")
     *                        =======
     *                        <<<<<<< HEAD
     * @param string $command Il comando Artisan da eseguire (senza "php artisan")
     * @param string $command Il comando Artisan da eseguire (senza "php artisan")
     *                        =======
     * @param string $command Il comando Artisan da eseguire (senza "php artisan")
     * @param string $command Il comando Artisan da eseguire (senza "php artisan")
     *                        >>>>>>> laraxot/dev
     *
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
     *
     * =======
     * <<<<<<< HEAD
     * <<<<<<< HEAD
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
     *
     * >>>>>>> laraxot/dev
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
     *                           =======
     *                           >>>>>>> laraxot/dev
     *
     * =======
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
     *                           >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
     *                           =======
     *                           <<<<<<< HEAD
     *                           =======
     *                           <<<<<<< HEAD
     *                           <<<<<<< HEAD
     *                           =======
     *                           >>>>>>> laraxot/dev
     *                           >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_a970nv
>>>>>>> 77b1e2748 (first)
     * @return array{
     *     command: string,
     *     output: list<string>,
     *     status: 'completed'|'failed',
     *     exitCode: int
     * } Array con informazioni sull'esecuzione del comando
<<<<<<< HEAD
     *
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
=======
<<<<<<< .merge_file_hZ4KPP
     *
     * @throws \RuntimeException Se il comando non è consentito o si verifica un errore
=======
     * <<<<<<< HEAD
>>>>>>> .merge_file_a970nv
>>>>>>> 77b1e2748 (first)
     */
    public function execute(string $command): array
    {
        Assert::stringNotEmpty($command, 'Il comando non può essere vuoto');

        if (! $this->isCommandAllowed($command)) {
            throw new \RuntimeException("Comando non consentito: {$command}");
        }

        /** @var list<string> $output */
        $output = [];
<<<<<<< HEAD
=======
        $status = 'running';
>>>>>>> 77b1e2748 (first)

        try {
            $process = Process::path(base_path())
                ->command("php artisan {$command}")
                ->timeout(300)
                ->start();

            // Cattura l'output man mano che il processo produce dati; non e'
            // "tempo reale" lato browser (questa chiamata resta bloccante
            // dentro un'unica richiesta Livewire sincrona), ma evita di
            // rileggere tutto solo alla fine se il processo e' lungo.
            while ($process->running()) {
                $data = $process->latestOutput();
                if (! empty($data)) {
                    $formattedData = trim($data);
                    if (! empty($formattedData)) {
                        $output[] = $formattedData;
                    }
                }

                $errorData = $process->latestErrorOutput();
                if (! empty($errorData)) {
                    $formattedError = trim($errorData);
                    if (! empty($formattedError)) {
                        $output[] = '[ERROR] '.$formattedError;
                    }
                }

                usleep(50000); // 50ms di pausa per evitare sovraccarico della CPU
            }

            $result = $process->wait();

            // Cattura qualsiasi output residuo
            $finalOutput = trim($result->output());
            if (! empty($finalOutput)) {
                $output[] = $finalOutput;
            }

            $finalErrorOutput = trim($result->errorOutput());
            if (! empty($finalErrorOutput)) {
                $output[] = '[ERROR] '.$finalErrorOutput;
            }

            $status = $result->successful() ? 'completed' : 'failed';

            return [
                'command' => $command,
                'output' => $output,
                'status' => $status,
                'exitCode' => $result->exitCode() ?? 0,
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException("Errore durante l'esecuzione del comando {$command}: {$e->getMessage()}", (int) $e->getCode(), $e);
        }
    }

    /**
     * Verifica se un comando è presente nella lista dei comandi consentiti.
     *
<<<<<<< HEAD
     * @param  string  $command  Il comando da verificare
=======
<<<<<<< .merge_file_hZ4KPP
     * @param  string  $command  Il comando da verificare
=======
     * <<<<<<< HEAD
     *
     * @param string $command Il comando da verificare
     *                        =======
     *                        <<<<<<< HEAD
     * @param string $command Il comando da verificare
     *                        =======
     *                        <<<<<<< HEAD
     *                        <<<<<<< HEAD
     * @param string $command Il comando da verificare
     *                        =======
     * @param string $command Il comando da verificare
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string $command Il comando da verificare
     *                        >>>>>>> laraxot/dev
     *                        >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_a970nv
>>>>>>> 77b1e2748 (first)
     * @return bool True se il comando è consentito, false altrimenti
     */
    private function isCommandAllowed(string $command): bool
    {
        Assert::stringNotEmpty($command, 'Il comando non può essere vuoto');

        return in_array($command, $this->allowedCommands, true);
    }
}
