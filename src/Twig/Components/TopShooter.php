<?php

namespace App\Twig\Components;

use App\Dto\MeetingStandingDto;
use App\Repository\MeetingRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('TopShooter')]
class TopShooter
{
    public int $meetingId;

    public function __construct(
        private MeetingRepository $repo
    ) {}

    public function getTopShooter(): MeetingStandingDto
    {
        return $this->repo->findWithScores($this->meetingId, 1);
    }
}
