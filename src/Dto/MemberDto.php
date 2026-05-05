<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class MemberDto
{
    public function __construct(
        public ?int                $id = null,

        #[Assert\Length(max: 255, maxMessage: 'The firstname cannot exceed 255 characters.')]
        public ?string             $firstName = '',
        #[Assert\NotBlank(message: 'The lastname cannot be blank.')]
        #[Assert\Length(max: 255, maxMessage: 'The lastname cannot exceed 255 characters.')]
        public ?string             $lastName = '',

        #[Assert\Email(message: 'The email is not valid.')]
        #[Assert\Length(max: 255, maxMessage: 'The email cannot exceed 255 characters.')]
        public ?string             $email = null,

        #[Assert\Length(max: 50, maxMessage: 'The phone cannot exceed 50 characters.')]
        public ?string             $phone = null,

        public ?string             $slug = null,

        public ?bool               $isActive = false,
        public ?bool               $usesSupport = false,

        // public ?string             $slug = null,
        public ?\DateTimeImmutable $createdAt = null,

        public ?\DateTimeImmutable $updatedAt = null,
    )
    {
    }

}
