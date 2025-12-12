<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['firstName', 'lastName'])]
    private ?string $slug = null;


    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, MeetingParticipant>
     */
    #[ORM\OneToMany(mappedBy: 'shooter', targetEntity: MeetingParticipant::class)]
    private Collection $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, MeetingParticipant>
     */
    public function getMeetingParticipants(): Collection
    {
        return $this->meetingParticipants;
    }

    public function addMeetingParticipant(MeetingParticipant $meetingParticipant): static
    {
        if (!$this->meetingParticipants->contains($meetingParticipant)) {
            $this->meetingParticipants->add($meetingParticipant);
            $meetingParticipant->setShooter($this);
        }

        return $this;
    }

    public function removeMeetingParticipant(MeetingParticipant $meetingParticipant): static
    {
        if ($this->meetingParticipants->removeElement($meetingParticipant)) {
            // set the owning side to null (unless already changed)
            if ($meetingParticipant->getShooter() === $this) {
                $meetingParticipant->setShooter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MeetingParticipant>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(MeetingParticipant $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setShooter($this);
        }

        return $this;
    }

    public function removeParticipation(MeetingParticipant $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getShooter() === $this) {
                $participation->setShooter(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
