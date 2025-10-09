<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RoundDto
{
    public function __construct(
        // Identifiers and references
        #[Assert\Blank(groups: ['create','update'])]
        public ?int $id = null,

        // Foreign key link to Meeting (use raw id in DTO to keep it light)
        #[Assert\NotNull(groups: ['create','update'])]
        public ?int $meetingId = null,

        // Status is short (<=10) and optional
        #[Assert\Length(max: 10, groups: ['create','update'])]
        public ?string $status = null,

        // Timestamps
        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $startedAt = null,

        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $endedAt = null,

        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $createdAt = null,

        #[Assert\Blank(groups: ['create','update'])]
        public ?\DateTimeImmutable $updatedAt = null,

        // Read-only for views: list of shots (flattened) belonging to this round
        #[Assert\Blank(groups: ['create','update'])]
        public array $shots = [], // array<RoundShotDto|array>
    ) {}

    #[Assert\Callback(groups: ['create','update'])]
    public function validateChrono(ExecutionContextInterface $ctx): void
    {
        if ($this->endedAt && $this->startedAt && $this->endedAt < $this->startedAt) {
            $ctx->buildViolation('endedAt must be on/after startedAt')->atPath('endedAt')->addViolation();
        }
    }
}
