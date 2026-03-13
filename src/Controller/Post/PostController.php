<?php

namespace App\Controller\Post;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\ClubRepository;
use App\Repository\FacebookEventRepository;
use App\Repository\MeetingSnapshotRepository;
use App\Repository\PostRepository;
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
        if ($post->getCategory()->getSlug() !== $categorySlug) {
            throw $this->createNotFoundException();
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
