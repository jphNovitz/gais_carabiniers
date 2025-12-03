<?php

namespace App\Service;

use App\Enum\MeetingType;
use App\Repository\MeetingRepository;

readonly class MeetingOrganizer implements \App\Contract\MeetingOrganizerInterface
{
    public function __construct(
        private MeetingRepository $meetingRepository
    ) {}

    public function getMeetingsGroupedByYear(): array
    {
        // Le service va chercher lui-même les données
        $dtos = $this->meetingRepository->findRawDtos();

        $sorted = [];

        // Votre logique optimisée (en une seule passe)
        foreach ($dtos as $dto) {
            $year = (int)$dto->date->format('Y');

            $typeKey = match ($dto->type) {
                MeetingType::COMPETITION => 'competition',
                MeetingType::PUBLIC => 'public',
                default => 'other',
            };

            $sorted[$year][$typeKey][] = $dto;
        }

        krsort($sorted);
        foreach ($sorted as &$yearGroup) {
            ksort($yearGroup);
        }

        return $sorted;
    }
}