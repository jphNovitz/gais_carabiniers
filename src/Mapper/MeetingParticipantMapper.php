<?php

namespace App\Mapper;

use App\Dto\MeetingParticipantDto;
use App\Entity\MeetingParticipant;
use App\Entity\Meeting;
use App\Entity\Member;

class MeetingParticipantMapper
{
    public static function fromEntity(MeetingParticipant $participant): MeetingParticipantDto
    {
        return new MeetingParticipantDto(
            id: $participant->getId(),
            meeting: $participant->getMeeting() ? MeetingMapper::fromEntity($participant->getMeeting()) : null,
            shooter: $participant->getShooter() ? MemberMapper::fromEntity($participant->getShooter()) : null,
            position: $participant->getPosition(),
            present: (bool)$participant->isPresent(),
            createdAt: $participant->getCreatedAt(),
            updatedAt: $participant->getUpdatedAt(),
        );
    }

    public static function toEntity(
        MeetingParticipantDto $dto,
        ?MeetingParticipant $participant = null,
        ?Meeting $meeting = null,
        ?Member $shooter = null
    ): MeetingParticipant {
        if (!$participant) {
            $participant = new MeetingParticipant();
        }

        if ($meeting) {
            $participant->setMeeting($meeting);
        }
        if ($shooter) {
            $participant->setShooter($shooter);
        }

        if ($dto->position !== null) {
            $participant->setPosition($dto->position);
        }
        $participant->setPresent((bool)$dto->present);
        if ($dto->createdAt) {
            $participant->setCreatedAt($dto->createdAt);
        }
        if ($dto->updatedAt) {
            $participant->setUpdatedAt($dto->updatedAt);
        }

        return $participant;
    }
}
