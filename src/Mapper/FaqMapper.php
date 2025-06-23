<?php

namespace App\Mapper;

use App\Entity\Faq;
use App\Entity\FaqCategory;
use App\Dto\FaqDto;
use App\Dto\FaqCategoryDto;

class FaqMapper
{
    public static function fromEntity(Faq $faq): FaqDto
    {
        return new FaqDto(
            id: $faq->getId(),
            question: $faq->getQuestion(),
            answer: $faq->getAnswer(),
            position: $faq->getPosition(),
            isPublished: $faq->isPublished(),
            updatedAt: $faq->getUpdatedAt(),
            category: $faq->getCategory() ? self::categoryFromEntity($faq->getCategory()) : null
        );
    }

    public static function toEntity(FaqDto $dto, ?Faq $faq = null, ?FaqCategory $category = null): Faq
    {
        if (!$faq) {
            $faq = new Faq();
        }
        $faq->setQuestion($dto->question);
        $faq->setAnswer($dto->answer);
        $faq->setPosition($dto->position);
        $faq->setIsPublished($dto->isPublished);
        $faq->setUpdatedAt($dto->updatedAt);
        if ($category) {
            $faq->setCategory($category);
        }
        return $faq;
    }

    public static function categoryFromEntity(FaqCategory $category): FaqCategoryDto
    {
        return new FaqCategoryDto(
            id: $category->getId(),
            name: $category->getName(),
            slug: $category->getSlug()
        );
    }
} 