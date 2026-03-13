<?php

namespace App\Entity;

use App\Repository\MeetingParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MeetingParticipantRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_meeting_shooter', columns: ['meeting_id','shooter_id'])]
#[ORM\UniqueConstraint(name: 'uniq_meeting_position', columns: ['meeting_id','position'])]
class MeetingParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Meeting $meeting = null;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'shooter_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private ?Member $shooter=null ;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\Column]
    private ?bool $present = true;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, RoundShot>
     */
    #[ORM\OneToMany(mappedBy: 'meetingParticipant', targetEntity: RoundShot::class)]
    private Collection $roundShots;

    public function __construct()
    {
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

    public function getShooter(): ?Member
    {
        return $this->shooter;
    }

    public function setShooter(?Member $shooter): static
    {
        $this->shooter = $shooter;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isPresent(): ?bool
    {
        return $this->present;
    }

    public function setPresent(bool $present): static
    {
        $this->present = $present;

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
            $roundShot->setMeetingParticipant($this);
        }

        return $this;
    }

    public function removeRoundShot(RoundShot $roundShot): static
    {
        if ($this->roundShots->removeElement($roundShot)) {
            // set the owning side to null (unless already changed)
            if ($roundShot->getMeetingParticipant() === $this) {
                $roundShot->setMeetingParticipant(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->shooter
            ? $this->shooter->getFirstName() . ' ' . $this->shooter->getLastName()
            : 'Nouveau participant';
    }
}
