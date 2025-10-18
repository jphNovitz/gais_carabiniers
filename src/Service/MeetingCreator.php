<?php

namespace App\Service;

use App\Contract\MeetingCreatorInterface;
use App\Entity\Meeting;
use App\Enum\MeetingStatus;
use Doctrine\ORM\EntityManagerInterface;

class MeetingCreator implements MeetingCreatorInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function createMeeting(): int
    {
        $months = [
            '1' => 'janvier',
            '2' => 'février',
            '3' => 'mars',
            '4' => 'avril',
            '5' => 'mai',
            '6' => 'juin',
            '7' => 'juillet',
            '8' => 'août',
            '9' => 'septembre',
            '10' => 'octobre',
            '11' => 'novembre',
            '12' => 'décembre',
        ];

        $now = new \DateTimeImmutable();
        $monthNumber = (int) $now->format('n'); // 1-12 without leading zeros
        $monthName = $months[(string)$monthNumber] ?? '';

        $meeting = new Meeting();
        $meeting->setDate($now);
        $meeting->setStatus(MeetingStatus::DRAFT->value);
        $meeting->setLabel('Tir du mois de ' . $months[(new \DateTime())->format('n')]);
        $meeting->setDate(new \DateTimeImmutable());
        $meeting->setOpenedAt(new \DateTimeImmutable());

        $this->em->persist($meeting);
        $this->em->flush();

        return $meeting->getId();
    }
}
