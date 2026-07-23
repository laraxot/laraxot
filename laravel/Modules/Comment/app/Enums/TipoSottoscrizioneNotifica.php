<?php

declare(strict_types=1);

namespace Modules\Comment\Enums;

enum TipoSottoscrizioneNotifica: string
{
    case Nessuna = 'none';
    case Partecipante = 'participating';
    case Proprietario = 'owner';
    case Tutti = 'all';

    public function label(): string
    {
        return match ($this) {
            self::Nessuna => 'Nessuna notifica',
            self::Partecipante => 'Solo quando partecipo',
            self::Proprietario => 'Come proprietario',
            self::Tutti => 'Tutte le attività',
        };
    }
}