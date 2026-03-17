<?php

namespace App\Twig\Components\Category;

use App\Entity\Category;
use App\Repository\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/category/related-posts.html.twig')]
class RelatedPosts
{
    public int $limit = 5;
    public array $relatedPosts = [];
    public ?Category $parentCategory = null;
    public array $childCategories = [];

    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function mount(?Category $category=null): void
    {
        if ($category === null) {
            return;
        }

        $this->parentCategory = $category->getParentCategory();
        $this->childCategories = $category->getChildrenCategories()->toArray();
        $this->relatedPosts = $this->postRepository->findBy(
            ['category' => $category],
            ['createdAt' => 'DESC'],
            $this->limit,
        );
    }
}