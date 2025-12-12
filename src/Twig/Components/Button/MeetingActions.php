<?php

namespace App\Twig\Components\Button;

use App\Enum\MeetingStatus;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class MeetingActions
{
    public int $id;
    public ?MeetingStatus $status = null;
    public ?int $loopIndex = null;

    public function getUid(): string
    {
        return $this->id ?? $this->loopIndex ?? uniqid();
    }
}
