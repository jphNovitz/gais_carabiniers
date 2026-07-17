<?php

namespace App\Entity;

use App\Enum\ShootingCategory;
use App\Repository\YearSnapshotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearSnapshotRepository::class)]
#[ORM\Table(name: 'year_snapshot')]
#[ORM\UniqueConstraint(name: 'uniq_year_participant_category', columns: ['year', 'participant_id', 'shooting_category'])]
#[ORM\Index(columns: ['year', 'year_position'], name: 'idx_year_position')]
#[ORM\Index(columns: ['year', 'total_score'], name: 'idx_year_score')]
#[ORM\Index(columns: ['year', 'shooting_category', 'year_position'], name: 'idx_year_category_position')]
class YearSnapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Member $participant = null;

    #[ORM\ManyToOne(targetEntity: Meeting::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Meeting $lastMeeting = null;

    #[ORM\Column(type: 'integer')]
    private int $year;

    #[ORM\Column(type: 'string', enumType: ShootingCategory::class)]
    private ShootingCategory $shootingCategory = ShootingCategory::CLASSIC;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $yearPosition = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $yearPrevPosition = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $totalScore = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $meetingCount = 0;

    #[ORM\Column(type: 'decimal', precision: 6, scale: 2, options: ['default' => 0])]
    private float $averageHits = 0;

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

    public function getParticipant(): ?Member { return $this->participant; }
    public function setParticipant(Member $participant): static
    {
        $this->participant = $participant;
        return $this;
    }

    public function getLastMeeting(): ?Meeting { return $this->lastMeeting; }
    public function setLastMeeting(?Meeting $lastMeeting): static
    {
        $this->lastMeeting = $lastMeeting;
        return $this;
    }

    public function getYear(): int { return $this->year; }
    public function setYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getShootingCategory(): ShootingCategory { return $this->shootingCategory; }
    public function setShootingCategory(ShootingCategory $shootingCategory): static
    {
        $this->shootingCategory = $shootingCategory;
        return $this;
    }

    public function getYearPosition(): int { return $this->yearPosition; }
    public function setYearPosition(int $yearPosition): static
    {
        $this->yearPosition = $yearPosition;
        return $this;
    }

    public function getYearPrevPosition(): ?int { return $this->yearPrevPosition; }
    public function setYearPrevPosition(?int $yearPrevPosition): static
    {
        $this->yearPrevPosition = $yearPrevPosition;
        return $this;
    }

    public function getTotalScore(): int { return $this->totalScore; }
    public function setTotalScore(int $totalScore): static
    {
        $this->totalScore = $totalScore;
        return $this;
    }

    public function getMeetingCount(): int { return $this->meetingCount; }
    public function setMeetingCount(int $meetingCount): static
    {
        $this->meetingCount = $meetingCount;
        return $this;
    }

    public function getAverageHits(): float { return $this->averageHits; }
    public function setAverageHits(float $averageHits): static
    {
        $this->averageHits = $averageHits;
        return $this;
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
