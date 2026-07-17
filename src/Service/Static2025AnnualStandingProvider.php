<?php

namespace App\Service;

use App\Enum\ShootingCategory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class Static2025AnnualStandingProvider
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {}

    public function isAvailable(): bool
    {
        return is_file($this->path());
    }

    public function findByCategory(ShootingCategory $shootingCategory): array
    {
        $data = $this->load();

        if ($data === []) {
            return [];
        }

        $filtered = array_values(array_filter(
            $data,
            static fn (array $row): bool => ((bool) ($row['usesSupport'] ?? false)) === ($shootingCategory === ShootingCategory::SUPPORTED)
        ));

        return $this->rank($filtered);
    }

    private function load(): array
    {
        if (!$this->isAvailable()) {
            return [];
        }

        $data = require $this->path();

        return is_array($data) ? $data : [];
    }

    private function rank(array $standings): array
    {
        $ranked = [];
        $rank = 0;
        $lastScore = null;

        foreach ($standings as $row) {
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

    private function path(): string
    {
        return $this->projectDir . '/data/standings_2025.php';
    }
}
