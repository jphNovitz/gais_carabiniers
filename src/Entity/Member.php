<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
#[Vich\Uploadable]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $streetNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[Vich\UploadableField(mapping: 'member_profile', fileNameProperty: 'profileImageName', size: 'profileImageSize')]
    private ?File $profileImageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $profileImageSize = null;

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column(type: 'boolean')]
    private ?bool $usesSupport = false;

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

    /**
     * @var Collection<int, ClubMembership>
     */
    #[ORM\OneToMany(mappedBy: 'shooter', targetEntity: ClubMembership::class)]
    private Collection $clubMemberships;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->club = new ArrayCollection();
        $this->memberships = new ArrayCollection();
        $this->clubMemberships = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function setProfileImageFile(?File $profileImageFile = null): void
    {
        $this->profileImageFile = $profileImageFile;

        if (null !== $profileImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getProfileImageFile(): ?File
    {
        return $this->profileImageFile;
    }

    public function getProfileImageName(): ?string
    {
        return $this->profileImageName;
    }

    public function setProfileImageName(?string $profileImageName): static
    {
        $this->profileImageName = $profileImageName;

        return $this;
    }

    public function getProfileImageSize(): ?int
    {
        return $this->profileImageSize;
    }

    public function setProfileImageSize(?int $profileImageSize): static
    {
        $this->profileImageSize = $profileImageSize;

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

//    /**
//     * @return Collection<int, MeetingParticipant>
//     */
//    public function getMeetingParticipants(): Collection
//    {
//        return $this->meetingParticipants;
//    }
//
//    public function addMeetingParticipant(MeetingParticipant $meetingParticipant): static
//    {
//        if (!$this->meetingParticipants->contains($meetingParticipant)) {
//            $this->meetingParticipants->add($meetingParticipant);
//            $meetingParticipant->setShooter($this);
//        }
//
//        return $this;
//    }
//
//    public function removeMeetingParticipant(MeetingParticipant $meetingParticipant): static
//    {
//        if ($this->meetingParticipants->removeElement($meetingParticipant)) {
//            // set the owning side to null (unless already changed)
//            if ($meetingParticipant->getShooter() === $this) {
//                $meetingParticipant->setShooter(null);
//            }
//        }
//
//        return $this;
//    }

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

    public function isUsesSupport(): ?bool
    {
        return $this->usesSupport;
    }

    public function setUsesSupport(bool $usesSupport): static
    {
        $this->usesSupport = $usesSupport;

        return $this;
    }

    /**
     * @return Collection<int, ClubMembership>
     */
    public function getClubMemberships(): Collection
    {
        return $this->clubMemberships;
    }

    public function addClubMembership(ClubMembership $clubMembership): static
    {
        if (!$this->clubMemberships->contains($clubMembership)) {
            $this->clubMemberships->add($clubMembership);
            $clubMembership->setShooter($this);
        }

        return $this;
    }

    public function removeClubMembership(ClubMembership $clubMembership): static
    {
        if ($this->clubMemberships->removeElement($clubMembership)) {
            // set the owning side to null (unless already changed)
            if ($clubMembership->getShooter() === $this) {
                $clubMembership->setShooter(null);
            }
        }

        return $this;
    }
}
