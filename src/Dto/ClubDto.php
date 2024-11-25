<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ClubDto
{
    public function __construct(
        public ?int                $id = null,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public ?string             $name = null,

        #[Assert\Length(max: 20)]
        public ?string             $federationNumber = null,

        #[Assert\Regex(pattern: '/^\+?\d{10,15}$/', message: 'Invalid phone number.')]
        public ?string             $phoneNumber = null,

        public ?string             $description = null,

// Logo details
        public ?string             $logoName = null,
        public ?int                $logoSize = null,

// Image details
        public ?string             $imageName = null,
        public ?int                $imageSize = null,

        #[Assert\NotNull]
        public ?\DateTimeImmutable $createdAt = null,

        public ?\DateTimeImmutable $updatedAt = null,

// Address details
        public ?string             $street = null,
        public ?string             $streetNumber = null,

        #[Assert\Regex(pattern: '/^\d{4,5}$/', message: 'Invalid postal code.')]
        public ?string             $postCode = null,

        public ?string             $city = null,

        #[Assert\Email]
        public ?string             $email = null,

        #[Assert\Regex(pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Invalid slug format.')]
        public ?string             $slug = null,
    )
    {
    }
}
