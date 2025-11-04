<?php

namespace App\Contract;

use App\Entity\Meeting;

interface MeetingCloserInterface
{
    public function close(Meeting $meeting): bool;

}
