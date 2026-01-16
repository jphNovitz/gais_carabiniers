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
    public string $year;

    public function __construct(
        private MeetingSnapshotRepository $snapshotRepository,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir
    )
    {
        $this->availableYears = $this->snapshotRepository->findAvailableYears();


        if (!in_array(2025, $this->availableYears, true)) {
            $this->availableYears[] = 2025;
            rsort($this->availableYears);
        }

        $this->year = $this->availableYears[0] ?? date('Y');
    }

    #[LiveProp]
    public function getStandings(): array
    {
        if ((int)$this->year === 2025) {
            $data = $this->getStaticStandings2025();

            if (empty($data)) {
                throw new \Exception('getStaticStandings2025() retourne un tableau vide !');
            }

            return $data;
        }
//        dd($this->snapshotRepository->findSeasonStandings((int)$this->year));
        return $this->snapshotRepository->findSeasonStandings((int)$this->year);
    }

    private function getStaticStandings2025(): array
    {
        $path = $this->projectDir . '/data/standings_2025.php';

        if (!file_exists($path)) {
            // Fallback sur le fichier exemple ou retour vide
            return [];
        }

        $data = require $path;

        return is_array($data) ? $data : [];
    }
}