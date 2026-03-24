<?php

namespace App\Contract;

use App\Entity\Meeting;

interface MeetingParticipantShufflerInterface
{
    public function addMany(Meeting $meeting, iterable $members): int;

    public function syncParticipants(Meeting $meeting, iterable $selectedMembers): void;
}
