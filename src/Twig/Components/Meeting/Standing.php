<?php

namespace App\Twig\Components\Meeting;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

use App\Repository\MeetingSnapshotRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Standing
{
    use DefaultActionTrait;

    public array $availableYears = [];

    #[LiveProp(writable: true)]
    public string $year;

    public function __construct(
        private MeetingSnapshotRepository $snapshotRepository,
    ) {
        $this->availableYears = $this->snapshotRepository->findAvailableYears();
        $this->year = $this->availableYears[0] ?? date('Y');
    }

    public function getStandings(): array
    {
        return $this->snapshotRepository->findSeasonStandings((int) $this->year);
    }
}