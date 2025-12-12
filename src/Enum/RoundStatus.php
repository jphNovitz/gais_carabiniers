<?php

namespace App\Enum;

enum RoundStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';  // Optionnel, si vous gérez l'annulation

    // Méthodes helper (optionnelles mais utiles)
    public function label(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'En cours',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'badge-warning',
            self::COMPLETED => 'badge-success',
            self::CANCELLED => 'badge-secondary',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::IN_PROGRESS => '⏳',
            self::COMPLETED => '✅',
            self::CANCELLED => '❌',
        };
    }
}
