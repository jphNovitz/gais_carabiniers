<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MeetingParticipantDto
{
public function __construct(
#[Assert\Blank(groups: ['create','update'])]
public ?int $id = null,

// Optionnel en body si l'ID est dans l'URL ; sinon NotNull + Valid
#[Assert\Valid]
public ?MeetingDto $meeting = null,

#[Assert\NotNull(groups: ['create'])]
#[Assert\Valid]
public ?MemberDto $shooter = null,

#[Assert\NotNull(groups: ['create','update'])]
#[Assert\GreaterThanOrEqual(1, groups: ['create','update'])]
#[Assert\LessThanOrEqual(32767, groups: ['create','update'])]
public ?int $position = 1,

public bool $present = true,

#[Assert\Blank(groups: ['create','update'])]
public ?\DateTimeImmutable $createdAt = null,

#[Assert\Blank(groups: ['create','update'])]
public ?\DateTimeImmutable $updatedAt = null,
) {}
}
