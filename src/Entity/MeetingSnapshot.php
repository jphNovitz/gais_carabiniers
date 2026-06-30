<?php

namespace App\Entity;

use App\Enum\ShootingCategory;
use App\Repository\MeetingSnapshotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingSnapshotRepository::class)]
#[ORM\Table(name: 'meeting_snapshot')]
#[ORM\UniqueConstraint(name: 'uniq_meeting_participant', columns: ['meeting_id', 'participant_id'])]
#[ORM\Index(columns: ['meeting_id', 'meeting_position'], name: 'idx_meeting_position')]
class MeetingSnapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Meeting::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Meeting $meeting = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Member $participant = null;

    #[ORM\Column(type: 'integer')]
    private int $meetingPosition = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $score = 0;

    #[ORM\Column(type: 'string', enumType: ShootingCategory::class)]
    private ShootingCategory $shootingCategory = ShootingCategory::CLASSIC;

    // Gardé temporairement, supprimé quand l'entité Club sera en place
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clubName = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $computedAt;

    public function __construct()
    {
        $this->computedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getMeeting(): ?Meeting { return $this->meeting; }
    public function setMeeting(Meeting $meeting): static
    {
        $this->meeting = $meeting;
        return $this;
    }

    public function getParticipant(): ?Member { return $this->participant; }
    public function setParticipant(Member $participant): static
    {
        $this->participant = $participant;
        return $this;
    }

    public function getMeetingPosition(): int { return $this->meetingPosition; }
    public function setMeetingPosition(int $meetingPosition): static
    {
        $this->meetingPosition = $meetingPosition;
        return $this;
    }

    public function getScore(): int { return $this->score; }
    public function setScore(int $score): static
    {
        $this->score = $score;
        return $this;
    }

    public function getShootingCategory(): ShootingCategory
    {
        return $this->shootingCategory;
    }

    public function setShootingCategory(ShootingCategory $shootingCategory): static
    {
        $this->shootingCategory = $shootingCategory;
        return $this;
    }

    public function isUsesSupport(): bool
    {
        return $this->shootingCategory === ShootingCategory::SUPPORTED;
    }

    public function getClubName(): ?string { return $this->clubName; }
    public function setClubName(?string $clubName): static
    {
        $this->clubName = $clubName;
        return $this;
    }

    public function getComputedAt(): \DateTimeImmutable { return $this->computedAt; }
    public function setComputedAt(\DateTimeImmutable $computedAt): static
    {
        $this->computedAt = $computedAt;
        return $this;
    }
}
