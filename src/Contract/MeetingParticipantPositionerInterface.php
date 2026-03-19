<?php

namespace App\Contract;



use App\Entity\Meeting;
use App\Repository\MeetingParticipantRepository;
use App\Repository\MeetingRepository;

interface MeetingParticipantPositionerInterface
{
    public function __construct( MeetingRepository $meetingRepository);
    public function order( Meeting $meeting): void;
}
