<?php

namespace App\Controller\Faq;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FaqController extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function index(FaqRepository $faqRepository): Response
    {
        $faqs = $faqRepository->findBy(['isPublished' => true], ['position' => 'ASC', 'id' => 'ASC']);
        
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqs,
        ]);
    }
} 