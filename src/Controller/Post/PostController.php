<?php

namespace App\Controller\Post;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;


class PostController extends AbstractController
{
    #[Route('/{categorySlug}/{slug}', name: 'app_post_show')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function show(string $categorySlug, Post $post): Response
    {
        $category = $post->getCategory();

        if ($category === null) {
            throw $this->createNotFoundException();
        }

        $urlCategory = $category->getParentCategory() ?? $category;

        if ($urlCategory->getSlug() !== $categorySlug) {
            throw $this->createNotFoundException();
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
