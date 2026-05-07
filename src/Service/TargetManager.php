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
        if ($lastRound !== null) {
            $orderedRoundShots = $this->roundShotRepository->findRoundShotsOrderedFromLastHit($lastRound);
            $lastHitTargets = [];
            foreach ($orderedRoundShots as $shot) {
                if ($shot->getRightTarget() !== null) {
                    $lastHitTargets['right'] = $shot->getRightTarget();
                }
                if ($shot->getLeftTarget() !== null) {
                    $lastHitTargets['left'] = $shot->getLeftTarget();
                }
                if (count($lastHitTargets) === 2) break;
            }
            if (count($lastHitTargets) < 2) {
                if (!isset($lastHitTargets['right'])) {
                    $lastHitTargets['right'] = 0;
                }
                if (!isset($lastHitTargets['left'])) {
                    $lastHitTargets['left'] = 0;
                }
            }
        } else {
            $lastHitTargets = ['right'=> 0, 'left' => 0];
        }
        return $lastHitTargets;
    }


}
