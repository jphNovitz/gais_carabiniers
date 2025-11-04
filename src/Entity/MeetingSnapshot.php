<?php

namespace App\Entity;

use App\Repository\MeetingSnapshotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingSnapshotRepository::class)]
#[ORM\Table(name: 'meeting_snapshot')]
#[ORM\UniqueConstraint(name: 'uniq_meeting_shooter', columns: ['meeting_id', 'shooter_id'])]
#[ORM\Index(columns: ['year', 'points'], name: 'idx_points')]
#[ORM\Index(columns: ['meeting_id', 'meeting_position'], name: 'idx_meeting_meeting_position')]
class MeetingSnapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Contexte — relations stables
    #[ORM\ManyToOne(targetEntity: Meeting::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Meeting $meeting = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Member $shooter = null;

    // Données de classement
    #[ORM\Column(type: 'integer')]
    private int $meetingPosition = 0;

    #[ORM\Column(type: 'float', nullable: true)]
    private float $points = 0.0;

    #[ORM\Column(type: 'float')]
    private float $totalScore;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $computedAt;

    // Historisation d'affichage (recommandé)
    #[ORM\Column(length: 255)]
    private string $shooterName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clubName = null;

    #[ORM\Column(length: 255)]
    private string $meetingLabel;

    // Pratique pour requêtes saisonnières (évite la jointure)
    #[ORM\Column(type: 'integer')]
    private int $year;
    public function __construct()
    {
        $this->computedAt = new \DateTimeImmutable();
    }

    // --- Getters / Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(Meeting $meeting): self
    {
        $this->meeting = $meeting;
        return $this;
    }


    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function getComputedAt(): \DateTimeImmutable
    {
        return $this->computedAt;
    }

    public function setComputedAt(\DateTimeImmutable $computedAt): self
    {
        $this->computedAt = $computedAt;
        return $this;
    }

    public function getShooterName(): string
    {
        return $this->shooterName;
    }

    public function setShooterName(string $shooterName): self
    {
        $this->shooterName = $shooterName;
        return $this;
    }

    public function getClubName(): ?string
    {
        return $this->clubName;
    }

    public function setClubName(?string $clubName): self
    {
        $this->clubName = $clubName;
        return $this;
    }

    public function getMeetingLabel(): string
    {
        return $this->meetingLabel;
    }

    public function setMeetingLabel(string $meetingLabel): self
    {
        $this->meetingLabel = $meetingLabel;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getTotalScore(): ?float
    {
        return $this->totalScore;
    }

    public function setTotalScore(float $totalScore): static
    {
        $this->totalScore = $totalScore;

        return $this;
    }

    public function getShooter(): ?Member
    {
        return $this->shooter;
    }

    public function setShooter(?Member $shooter): static
    {
        $this->shooter = $shooter;

        return $this;
    }

    public function getMeetingPosition(): ?int
    {
        return $this->meetingPosition;
    }

    public function setMeetingPosition(int $meetingPosition): static
    {
        $this->meetingPosition = $meetingPosition;

        return $this;
    }



}
