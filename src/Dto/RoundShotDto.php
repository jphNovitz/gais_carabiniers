<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RoundShotDto
{
    public function __construct(
        #[Assert\Blank(groups: ['create','update'])]
        public ?int $id = null,

        // Foreign keys by id to keep DTOs light/coupled loosely
        #[Assert\NotNull(groups: ['create'])]
        public ?int $roundId = null,

        #[Assert\NotNull(groups: ['create'])]
        public ?int $meetingParticipantId = null,

        // Score for this shot (nullable to match entity)
        public ?int $score = null,

        // Timestamps are system-managed
        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $createdAt = null,

        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
