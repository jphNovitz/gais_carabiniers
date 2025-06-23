<?php

namespace App\Controller\Admin;

use App\Dto\FaqDto;
use App\Dto\FaqCategoryDto;
use App\Entity\Faq;
use App\Entity\FaqCategory;
use App\Form\FaqType;
use App\Mapper\FaqMapper;
use App\Repository\FaqCategoryRepository;
use App\Repository\FaqRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/faq')]
final class FaqController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FaqRepository $faqRepository,
        private FaqCategoryRepository $faqCategoryRepository
    ) {}

    #[Route(name: 'admin_faq_index', methods: ['GET'])]
    public function index(): Response
    {
        $faqs = $this->faqRepository->findAll();
        return $this->render('admin/faq/index.html.twig', [
            'faqs' => $faqs,
        ]);
    }

    #[Route('/new', name: 'admin_faq_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $faqDto = new FaqDto();
        $categories = array_map(
            fn(FaqCategory $cat) => FaqMapper::categoryFromEntity($cat),
            $this->faqCategoryRepository->findAll()
        );
        $form = $this->createForm(FaqType::class, $faqDto, ['categories' => $categories]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $faqDto->category?->id ? $this->faqCategoryRepository->find($faqDto->category->id) : null;
            $faq = FaqMapper::toEntity($faqDto, null, $category);
            $this->entityManager->persist($faq);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_faq_index');
        }
        return $this->render('admin/faq/new.html.twig', [
            'faq' => $faqDto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_faq_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Faq $faq): Response
    {
        $faqDto = FaqMapper::fromEntity($faq);
        $categories = array_map(
            fn(FaqCategory $cat) => FaqMapper::categoryFromEntity($cat),
            $this->faqCategoryRepository->findAll()
        );
        $form = $this->createForm(FaqType::class, $faqDto, ['categories' => $categories]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $faqDto->category?->id ? $this->faqCategoryRepository->find($faqDto->category->id) : null;
            FaqMapper::toEntity($faqDto, $faq, $category);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_faq_index');
        }
        return $this->render('admin/faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_faq_delete', methods: ['POST'])]
    public function delete(Request $request, Faq $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($faq);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('admin_faq_index');
    }
} 