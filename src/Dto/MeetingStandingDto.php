<?php

namespace App\Dto;
use App\Enum\MeetingStatus;

class MeetingStandingDto
{
    public function __construct(
        public readonly int $meetingId,
        public readonly string $label,
        public readonly \DateTimeInterface $date,
        public ?MeetingStatus $status = MeetingStatus::DRAFT,
        /** @var ParticipantStandingDTO[] */
        public readonly array $participants
    ) {}
}


