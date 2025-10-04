<?php

namespace App\Mapper;

use App\Dto\MeetingDto;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;

class MeetingMapper
{
    public static function fromEntity(Meeting $meeting): MeetingDto
    {
        // Build participants list sorted by position ASC
        $participants = [];
        foreach ($meeting->getParticipants() as $mp) {
            if (!$mp instanceof MeetingParticipant) { continue; }
            $name = (string) $mp->getShooter();
            $participants[] = [
                'position' => $mp->getPosition(),
                'name' => $name,
                'present' => (bool) $mp->isPresent(),
            ];
        }
        usort($participants, static function(array $a, array $b) {
            return ($a['position'] <=> $b['position']);
        });

        return new MeetingDto(
            id: $meeting->getId(),
            date: $meeting->getDate(),
            status: $meeting->getStatus(),
            title: $meeting->getLabel(),
            slug: $meeting->getSlug(),
            openedAt: $meeting->getOpenedAt(),
            closedAt: $meeting->getClosedAt(),
            createdAt: $meeting->getCreatedAt(),
            updatedAt: $meeting->getUpdatedAt(),
            participants: $participants,
        );
    }

    public static function toEntity(MeetingDto $dto, ?Meeting $meeting = null): Meeting
    {
        if (!$meeting) {
            $meeting = new Meeting();
        }

        if ($dto->date) {
            $meeting->setDate($dto->date);
        }
        if ($dto->status !== null) {
            $meeting->setStatus($dto->status);
        }
        // DTO uses `title` while entity uses `label`
        $meeting->setLabel($dto->title);
        // Slug is generally handled by Gedmo, but keep symmetry with other mappers
        if ($dto->slug !== null) {
            $meeting->setSlug($dto->slug);
        }
        $meeting->setOpenedAt($dto->openedAt);
        $meeting->setClosedAt($dto->closedAt);
        $meeting->setCreatedAt($dto->createdAt);
        $meeting->setUpdatedAt($dto->updatedAt);

        return $meeting;
    }
}
