<?php

namespace App\Entity;

use App\Repository\ClubMembershipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubMembershipRepository::class)]
class ClubMembership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'club')]
    private ?Member $shooter = null;

    #[ORM\ManyToOne(inversedBy: 'clubMemberships')]
    private ?Club $club = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }
}
