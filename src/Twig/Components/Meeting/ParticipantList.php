<?php

namespace App\Twig\Components\Meeting;

use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Repository\MeetingParticipantRepository;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ParticipantList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $meetingId;

    public function __construct(private readonly MeetingParticipantRepository $participantRepository,
                                private readonly MeetingRepository            $meetingRepository,
                                private readonly EntityManagerInterface       $em)
    {
    }

    public function mount(Meeting $meeting): void
    {
        $this->meetingId = $meeting->getId();
    }

    public function getParticipants(): array
    {
        $participants = $this->participantRepository->findBy(
            ['meeting' => $this->getMeeting()],
            ['position' => 'ASC']
        );

        return array_map(
            fn(MeetingParticipant $p) => [
                'id' => $p->getId(),
                'firstName' => $p->getShooter()->getFirstName(),
                'lastName' => $p->getShooter()->getLastName(),
                'position' => $p->getPosition(),
                'present' => $p->isPresent(),
            ],
            $participants
        );

    }


    public function getMeeting(): Meeting
    {

        return $this->meetingRepository->find($this->meetingId);
    }

    #[LiveAction]
    public function moveUp(#[LiveArg] int $id): void
    {
        $this->em->clear();
        $participants = $this->getMeeting()->getParticipants();

        $current = $participants->filter(
            fn($p) => $p->getId() === $id
        )->first();

        $above = $participants->filter(
            fn($p) => $p->getPosition() === $current->getPosition() - 1
        )->first();

        // Swap
        if ($above) {
            $oldCurrentPosition = $current->getPosition();
            $oldAbovePosition = $above->getPosition();

            $current->setPosition(-999);
            $this->participantRepository->save($current, true);

            $above->setPosition($oldCurrentPosition);
            $this->participantRepository->save($above, true);

            $current->setPosition($oldAbovePosition);
            $this->participantRepository->save($current, true);
            $this->meetingId = $this->meetingRepository->find($current->getMeeting()->getId())->getId();
        }
        $this->em->clear();
    }

    #[LiveAction]
    public function moveDown(#[LiveArg] int $id): void
    {
        // ✅ Clear au début
        $this->em->clear();

        $participants = $this->getMeeting()->getParticipants();

        $current = $participants->filter(
            fn($p) => $p->getId() === $id
        )->first();

        $below = $participants->filter(
            fn($p) => $p->getPosition() === $current->getPosition() + 1
        )->first();

        // Swap
        if ($below) {
            $oldCurrentPosition = $current->getPosition();
            $oldBelowPosition = $below->getPosition();

            $current->setPosition(-999);
            $this->participantRepository->save($current, true);

            $below->setPosition($oldCurrentPosition);
            $this->participantRepository->save($below, true);

            $current->setPosition($oldBelowPosition);
            $this->participantRepository->save($current, true);

            // ✅ Clear à la fin
            $this->em->clear();
        }
    }

}
