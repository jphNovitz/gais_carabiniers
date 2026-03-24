<?php

namespace App\Controller\Post;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'app_category_show')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function show(string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category instanceof Category) {
            throw $this->createNotFoundException('Category not found.');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'posts' => $category->getPosts(),
        ]);
    }
}
