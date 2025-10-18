<?php

namespace App\Dto;
class MeetingStandingDto
{
    public function __construct(
        public readonly int $meetingId,
        public readonly string $label,
        public readonly \DateTimeInterface $date,
        public readonly string $status,
        /** @var ParticipantStandingDTO[] */
        public readonly array $participants
    ) {}
}


