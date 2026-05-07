<?php

namespace App\Contract;

use App\Entity\Meeting;

interface TargetManagerInterface
{
    public function getLastHitTargets(Meeting $meeting): array;
}
