<?php

namespace App\Entity;

use App\Repository\RoundShotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RoundShotRepository::class)]
class RoundShot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roundShots')]
    private ?Round $round = null;

    #[ORM\ManyToOne(inversedBy: 'score')]
    private ?MeetingParticipant $meetingParticipant = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $leftHit = false;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $leftTarget = null;

    #[ORM\Column(type: 'boolean')]
    private bool $rightHit = false;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $rightTarget = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function getMeetingParticipant(): ?MeetingParticipant
    {
        return $this->meetingParticipant;
    }

    public function setMeetingParticipant(?MeetingParticipant $meetingParticipant): static
    {
        $this->meetingParticipant = $meetingParticipant;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getScore(): int
    {
        return ($this->leftHit ? 1 : 0) + ($this->rightHit ? 1 : 0);
    }


    public function isLeftHit(): ?bool
    {
        return $this->leftHit;
    }

    public function setLeftHit(bool $leftHit): static
    {
        $this->leftHit = $leftHit;

        return $this;
    }



    public function isRightHit(): ?bool
    {
        return $this->rightHit;
    }

    public function setRightHit(bool $rightHit): static
    {
        $this->rightHit = $rightHit;

        return $this;
    }

    public function getRightTarget(): ?int
    {
        return $this->rightTarget;
    }

    public function setRightTarget(?int $rightTarget): static
    {
        $this->rightTarget = $rightTarget;

        return $this;
    }

    public function getLeftTarget(): ?int
    {
        return $this->leftTarget;
    }

    public function setLeftTarget(?int $leftTarget): static
    {
        $this->leftTarget = $leftTarget;

        return $this;
    }
}
