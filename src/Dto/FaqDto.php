<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FaqDto
{
    public function __construct(
        public ?int $id = null,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public ?string $question = '',
        #[Assert\NotBlank]
        public ?string $answer = '',
        public int $position = 0,
        public bool $isPublished = false,
        public ?\DateTimeImmutable $updatedAt = null,
        public ?FaqCategoryDto $category = null,
    ) {}
} 