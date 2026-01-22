<?php

namespace App\Dto;

class ParticipantStandingDto
{
    public function __construct(
        public readonly int $participantId,
        public readonly int $shooterId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $position,
        public readonly int $totalScore,
        public readonly int $roundsPlayed,
        public readonly int $rank, // Position dans le classement

    ) {}
}