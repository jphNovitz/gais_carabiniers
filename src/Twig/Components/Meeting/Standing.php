<?php

namespace App\Twig\Components\Meeting;

use App\Enum\ShootingCategory;
use App\Repository\YearSnapshotRepository;
use App\Service\Static2025AnnualStandingProvider;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Standing
{
    use DefaultActionTrait;

    public array $availableYears = [];

    #[LiveProp(writable: true)]
    public ?int $year = null;

    #[LiveProp]
    public string $category = 'standard';

    public function __construct(
        private YearSnapshotRepository $snapshotRepository,
        private Static2025AnnualStandingProvider $static2025AnnualStandingProvider,
    ) {
        $this->availableYears = array_map('intval', $this->snapshotRepository->findAvailableYears());

        if ($this->static2025AnnualStandingProvider->isAvailable() && !in_array(2025, $this->availableYears, true)) {
            $this->availableYears[] = 2025;
        }

        rsort($this->availableYears);

        $this->year ??= $this->availableYears[0] ?? (int) date('Y');
    }

    public function getStandings(): array
    {
        $shootingCategory = $this->shootingCategory();

        if ((int) $this->year === 2025) {
            return $this->static2025AnnualStandingProvider->findByCategory($shootingCategory);
        }

        return $this->snapshotRepository->findSeasonStandings($this->year ?? (int) date('Y'), $shootingCategory);
    }

    public function getTitle(): string
    {
        return $this->shootingCategory() === ShootingCategory::SUPPORTED
            ? 'Classement annuel appuyé'
            : 'Classement annuel standard';
    }

    private function shootingCategory(): ShootingCategory
    {
        return $this->category === 'appuye' ? ShootingCategory::SUPPORTED : ShootingCategory::CLASSIC;
    }
}
