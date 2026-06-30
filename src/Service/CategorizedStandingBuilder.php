<?php

namespace App\Service;

use App\Contract\CategorizedStandingBuilderInterface;
use App\Enum\ShootingCategory;

final class CategorizedStandingBuilder implements CategorizedStandingBuilderInterface
{
    public function categorizeMeetingStanding(array $standing): array
    {
        $categorized = [
            'overall' => [],
            'standard' => [],
            'supported' => [],
        ];

        foreach ($standing as $line) {
            $categorized['overall'][] = $line;

            if ($this->usesSupport($line)) {
                $categorized['supported'][] = $line;
                continue;
            }

            $categorized['standard'][] = $line;
        }

        $categorized['overall'] = $this->rankCategory($categorized['overall']);
        $categorized['standard'] = $this->rankCategory($categorized['standard']);
        $categorized['supported'] = $this->rankCategory($categorized['supported']);

        return $categorized;
    }

    private function rankCategory(array $standing): array
    {
        $rankedStanding = [];
        $rank = 0;
        $lastScore = null;

        foreach ($standing as $line) {
            $score = $line->getScore();

            if ($lastScore === null || $score !== $lastScore) {
                $rank++;
                $lastScore = $score;
            }

            $rankedStanding[] = [
                'rank' => $rank,
                'line' => $line,
            ];
        }

        return $rankedStanding;
    }

    private function usesSupport(object $line): bool
    {
        if (method_exists($line, 'getShootingCategory')) {
            return $line->getShootingCategory() === ShootingCategory::SUPPORTED;
        }

        return (bool) $line->getParticipant()?->isUsesSupport();
    }
}
