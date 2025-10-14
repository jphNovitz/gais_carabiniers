<?php
namespace App\Contract;

use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Round;
interface RoundManagerInterface
{
    public function createNextRound(Meeting $meeting): Round;
    public function closeRound(Round $round): void;
    public function recordShot(Round $round, MeetingParticipant $participant, int $score): void;
}