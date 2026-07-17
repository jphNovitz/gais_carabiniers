<?php

namespace App\Twig\Components\Meeting;

use App\Enum\ShootingCategory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\YearSnapshotRepository; // ← changé

#[AsLiveComponent]
final class Standing
{
    use DefaultActionTrait;

    public array $availableYears = [];

    #[LiveProp(writable: true)]
    public ?int $year = null;

    public string $category = 'standard';

    public function __construct(
        private YearSnapshotRepository $snapshotRepository, // ← changé
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir
    ) {
        $this->availableYears = array_map('intval', $this->snapshotRepository->findAvailableYears());

        $path2025 = $this->projectDir . '/data/standings_2025.php';
        if (is_file($path2025) && !in_array(2025, $this->availableYears, true)) {
            $this->availableYears[] = 2025;
        }

        rsort($this->availableYears);

        $this->year ??= $this->availableYears[0] ?? (int) date('Y');
    }

    public function getStandings(): array
    {
        $shootingCategory = $this->shootingCategory();

        if ($this->year === 2025) {
            $data = $this->getStaticStandings2025();
            return empty($data) ? [] : $this->filterAndRankStaticStandings($data, $shootingCategory);
        }

        return $this->snapshotRepository->findSeasonStandings($this->year ?? (int) date('Y'), $shootingCategory);
    }

    public function getTitle(): string
    {
        return $this->shootingCategory() === ShootingCategory::SUPPORTED
            ? 'Classement annuel appuyé'
            : 'Classement annuel standard';
    }

    private function getStaticStandings2025(): array
    {
        $path = $this->projectDir . '/data/standings_2025.php';
        if (!is_file($path)) {
            return [];
        }
        $data = require $path;
        return is_array($data) ? $data : [];
    }

    private function shootingCategory(): ShootingCategory
    {
        return $this->category === 'appuye' ? ShootingCategory::SUPPORTED : ShootingCategory::CLASSIC;
    }

    private function filterAndRankStaticStandings(array $standings, ShootingCategory $shootingCategory): array
    {
        $filtered = array_values(array_filter(
            $standings,
            static fn (array $row): bool => ((bool) ($row['usesSupport'] ?? false)) === ($shootingCategory === ShootingCategory::SUPPORTED)
        ));

        $ranked = [];
        $rank = 0;
        $lastScore = null;

        foreach ($filtered as $row) {
            $score = $row['totalScore'] ?? null;

            if ($lastScore === null || $score !== $lastScore) {
                $rank++;
                $lastScore = $score;
            }

            $row['rank'] = $rank;
            $row['previousRank'] = null;
            $ranked[] = $row;
        }

        return $ranked;
    }
}
