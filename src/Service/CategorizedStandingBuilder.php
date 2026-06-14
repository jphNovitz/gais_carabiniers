<?php

namespace App\Service;

use App\Contract\CategorizedStandingBuilderInterface;

final class CategorizedStandingBuilder implements CategorizedStandingBuilderInterface
{
    public function categorizeMeetingStanding(array $standing): array
    {
        $categorized = [
            'standard' => [],
            'supported' => [],
        ];

        foreach ($standing as $line) {
            if ($line->getParticipant()?->isUsesSupport()) {
                $categorized['supported'][] = $line;
                continue;
            }

            $categorized['standard'][] = $line;
        }

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
}
