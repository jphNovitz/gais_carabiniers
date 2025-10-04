<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MeetingDto
{
public function __construct(
#[Assert\Blank(groups: ['create','update'])] public ?int $id = null,

#[Assert\NotNull(groups: ['create','update'])]
public ?\DateTimeImmutable $date = null,

#[Assert\NotBlank(groups: ['create','update'])]
#[Assert\Choice(['DRAFT','OPEN','CLOSED'], groups: ['create','update'])]
#[Assert\Length(max: 16, groups: ['create','update'])]
public ?string $status = 'DRAFT',

#[Assert\Length(max: 120, groups: ['create','update'])]
public ?string $title = null,

#[Assert\Blank(groups: ['create','update'])] public ?string $slug = null,
#[Assert\Blank(groups: ['create','update'])] public ?\DateTimeImmutable $openedAt = null,
#[Assert\Blank(groups: ['create','update'])] public ?\DateTimeImmutable $closedAt = null,
#[Assert\Blank(groups: ['create','update'])] public ?\DateTimeImmutable $createdAt = null,
#[Assert\Blank(groups: ['create','update'])] public ?\DateTimeImmutable $updatedAt = null,

// Read-only for views: list of participants as simple arrays [{position:int, name:string, present:bool}]
#[Assert\Blank(groups: ['create','update'])] public array $participants = [],
) {}

#[Assert\Callback(groups: ['create','update'])]
public function validateChrono(ExecutionContextInterface $ctx): void {
if ($this->openedAt && $this->date && $this->openedAt < $this->date) {
$ctx->buildViolation('openedAt must be on/after date')->atPath('openedAt')->addViolation();
}
if ($this->closedAt && $this->openedAt && $this->closedAt < $this->openedAt) {
$ctx->buildViolation('closedAt must be on/after openedAt')->atPath('closedAt')->addViolation();
}
}
}
