<?php

namespace App\Contract;

use App\Entity\Meeting;
use App\Entity\Member;

interface MeetingParticipantAdderInterface
{
    /**
     * Add the given members as participants to the meeting.
     * Implementations should ensure each participant has a unique position within the meeting.
     *
     * @param Meeting $meeting The meeting to add participants to
     * @param Member[] $members The members to add as participants
     */
    public function addMany(Meeting $meeting, iterable $members): int;
}
