<?php

namespace App\Twig\Components\Post;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Topic/RelatedPosts.html.twig')]
class RelatedChildPosts
{
    public Post $post;
    public int $limit = 5;
    public array $relatedPosts = [];
    public ?Category $category = null;

    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function mount(): void
    {
        $this->category = $this->post->getCategory();

        if ($this->category === null) {
            return;
        }

        $this->relatedPosts = $this->postRepository->findRelated(
            category: $this->category,
            excludePost: $this->post,
            limit: $this->limit,
        );
    }
}