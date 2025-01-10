<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FacebookEventDto
{
    public function __construct(
        public ?int                $id = null,

        #[Assert\NotBlank(message: 'The event title cannot be blank.')]
        #[Assert\Length(max: 255, maxMessage: 'The event title cannot exceed 255 characters.')]
        public ?string             $title = null,

        #[Assert\NotBlank(message: 'The event date cannot be blank.')]
        public ?\DateTime          $date = null,

        #[Assert\NotBlank(message: 'The Facebook link cannot be blank.')]
        #[Assert\Url(message: 'The Facebook link must be a valid URL.')]
        public ?string             $facebookLink = null,

        public ?string             $description = null,

        // public ?string             $slug = null,
        public ?\DateTimeImmutable $createdAt = null,

        public ?\DateTimeImmutable $updatedAt = null,
    )
    {
    }

}
