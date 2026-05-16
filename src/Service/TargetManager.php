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
            $lastHitTargets = ['right'=> 0, 'left' => 0];

            foreach ($orderedRoundShots as $shot) {
                if ($shot->getRightTarget() !== null) {
                    if ($shot->getRightTarget() > $lastHitTargets['right'])     {
                        $lastHitTargets['right'] = $shot->getRightTarget();
                    }
                }
                if ($shot->getLeftTarget() !== null) {
                    if ($shot->getLeftTarget() > $lastHitTargets['left'])     {
                        $lastHitTargets['left'] = $shot->getLeftTarget();
                    }
                }
            }
            
        } else {
            $lastHitTargets = ['right'=> 0, 'left' => 0];
        }
        return $lastHitTargets;
    }


}
