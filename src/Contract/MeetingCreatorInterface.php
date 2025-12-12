<?php

namespace App\Contract;

use App\Enum\MeetingType;

interface MeetingCreatorInterface
{
    public function createMeeting( MeetingType $type): int;
}
