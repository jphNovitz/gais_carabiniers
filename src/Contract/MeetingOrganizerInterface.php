<?php

namespace App\Contract;

interface MeetingOrganizerInterface
{
    public function getMeetingsGroupedByYear(): array;
}