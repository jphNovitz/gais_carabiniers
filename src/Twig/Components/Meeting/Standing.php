<?php

namespace App\Twig\Components\Meeting;


use App\Repository\MeetingSnapshotRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Standing
{
    public string $title = 'Season Standings toto';

    public function __construct(
        private MeetingSnapshotRepository $snapshotRepository,
    ) {
    }

    public function getStandings(): array
    {
        return $this->snapshotRepository->findSeasonStandings(2025);
    }


}
