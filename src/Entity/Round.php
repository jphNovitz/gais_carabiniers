<?php

namespace App\Entity;

use App\Enum\RoundStatus;
use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RoundRepository::class)]
class Round
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;
    #[ORM\ManyToOne(inversedBy: 'rounds')]
    private ?Meeting $meeting = null;

    #[ORM\Column(type: 'string', enumType: RoundStatus::class)]
    private RoundStatus $status = RoundStatus::IN_PROGRESS;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, RoundShot>
     */
    #[ORM\OneToMany(mappedBy: 'round', targetEntity: RoundShot::class)]
    private Collection $roundShots;

    public function __construct()
    {
        $this->startedAt = new \DateTimeImmutable();
        $this->roundShots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): static
    {
        $this->meeting = $meeting;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

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

    /**
     * @return Collection<int, RoundShot>
     */
    public function getRoundShots(): Collection
    {
        return $this->roundShots;
    }

    public function addRoundShot(RoundShot $roundShot): static
    {
        if (!$this->roundShots->contains($roundShot)) {
            $this->roundShots->add($roundShot);
            $roundShot->setRound($this);
        }

        return $this;
    }

    public function removeRoundShot(RoundShot $roundShot): static
    {
        if ($this->roundShots->removeElement($roundShot)) {
            // set the owning side to null (unless already changed)
            if ($roundShot->getRound() === $this) {
                $roundShot->setRound(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?RoundStatus
    {
        return $this->status;
    }

    public function setStatus(RoundStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
