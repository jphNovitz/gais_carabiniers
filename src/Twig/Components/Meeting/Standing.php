<?php

namespace App\Twig\Components\Meeting;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\MeetingSnapshotRepository;

#[AsLiveComponent]
final class Standing
{
    use DefaultActionTrait;

    public array $availableYears = [];

    #[LiveProp(writable: true)]
    public ?int $year = null;

    public function __construct(
        private MeetingSnapshotRepository $snapshotRepository,
        #[Autowire('%kernel.project_dir%')]
        private string                    $projectDir
    )
    {
        $this->availableYears = array_map('intval', $this->snapshotRepository->findAvailableYears());

        $path2025 = $this->projectDir . '/data/standings_2025.php';
        if (is_file($path2025) && !in_array(2025, $this->availableYears, true)) {
            $this->availableYears[] = 2025;
        }

        rsort($this->availableYears);

        $this->year ??= $this->availableYears[0] ?? (int)date('Y');
    }

    public function getStandings(): array
    {
        if ($this->year === 2025) {
            $data = $this->getStaticStandings2025();
            if (empty($data)) {
                // en prod: mieux vaut fallback que crash
                return [];
            }
            return $data;
        }

        return $this->snapshotRepository->findSeasonStandings($this->year ?? (int)date('Y'));
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
}

