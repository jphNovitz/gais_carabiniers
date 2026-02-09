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


class PostControllerController extends AbstractController
{
    #[Route('/articles', name: 'app_post_index')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function index(PostRepository     $postRepository,
                          CategoryRepository $categoryRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/article/{slug}', name: 'app_post_show')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function show(Post $post=null): Response
    {
        if (!$post) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
