<?php

namespace App\Twig\Components\Post;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/post/related-posts.html.twig')]
class RelatedPosts
{
    public int $limit = 5;
    public array $relatedPosts = [];
    public ?Category $category = null;

    public function __construct(
        private PostRepository $postRepository,
    ) {}

    public function mount(?Post $post = null): void
    {
        if ($post === null) {
            return;
        }

        $this->category = $post->getCategory();

        if ($this->category === null) {
            return;
        }

        $this->relatedPosts = $this->postRepository->findRelated(
            category: $this->category,
            excludePost: $post,
            limit: $this->limit,
        );
    }
}
