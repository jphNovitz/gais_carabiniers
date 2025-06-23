<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FaqCategoryDto
{
    public function __construct(
        public ?int $id = null,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public ?string $name = '',
        public ?string $slug = null,
    ) {}
} 