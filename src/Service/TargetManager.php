<?php

namespace App\Service;

use App\Contract\TargetManagerInterface;
use App\Entity\Meeting;
use App\Repository\RoundRepository;
use App\Repository\RoundShotRepository;

class TargetManager implements TargetManagerInterface
{
    public function __construct(
        private RoundRepository     $roundRepository,
        private RoundShotRepository $roundShotRepository
    )
    {
    }

    public function getLastHitTargets(Meeting $meeting): array
    {
        $lastRound = $this->roundRepository->findLastCompletedRound($meeting);
        if ($lastRound === null) {
            return ['right' => 0, 'left' => 0];
        }

        $orderedRoundShots = $this->roundShotRepository->findRoundShotsOrderedFromLastHit($lastRound);
        $lastHitTargets = ['right' => 0, 'left' => 0];

        foreach ($orderedRoundShots as $shot) {
            if ($lastHitTargets['right'] === 0 && $shot->getRightTarget() !== null) {
                $lastHitTargets['right'] = $shot->getRightTarget();
            }

            if ($lastHitTargets['left'] === 0 && $shot->getLeftTarget() !== null) {
                $lastHitTargets['left'] = $shot->getLeftTarget();
            }

            if ($lastHitTargets['right'] !== 0 && $lastHitTargets['left'] !== 0) {
                break;
            }
        }

        return $lastHitTargets;
    }


}
