<?php

namespace App\Controller\Admin;

use App\Entity\FacebookEvent;
use App\Dto\FacebookEventDto;
use App\Form\FacebookEventType;
use App\Mapper\FacebookEventMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/event')]
final class FacebookEventController extends AbstractController
{
    public function __construct(
        private readonly FacebookEventMapper $facebookEventMapper
    ) {}

    #[Route(name: 'admin_facebook_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $facebookEvents = $entityManager
            ->getRepository(FacebookEvent::class)
            ->findAll();

        return $this->render('admin/facebook_event/index.html.twig', [
            'facebook_events' => $facebookEvents,
        ]);
    }

    #[Route('/new', name: 'admin_facebook_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facebookEventDto = new FacebookEventDto();

        $form = $this->createForm(FacebookEventType::class, $facebookEventDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // die('ok');
            $facebookEvent = $this->facebookEventMapper->toEntity($facebookEventDto);
            $entityManager->persist($facebookEvent);
            $entityManager->flush();

            return $this->redirectToRoute('admin_facebook_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/facebook_event/new.html.twig', [
            // 'facebook_event' => $facebookEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_facebook_event_show', methods: ['GET'])]
    public function show(FacebookEvent $facebookEvent): Response
    {
        $facebookEventDto = $this->facebookEventMapper->fromEntity($facebookEvent);

        return $this->render('admin/facebook_event/show.html.twig', [
            'facebook_event' => $facebookEventDto,
        ]);
    }

    #[Route('/{slug}/edit', name: 'admin_facebook_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FacebookEvent $facebookEvent, EntityManagerInterface $entityManager): Response
    {
        $facebookEventDto = $this->facebookEventMapper->fromEntity($facebookEvent);
        $form = $this->createForm(FacebookEventType::class, $facebookEventDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $facebookEvent = $this->facebookEventMapper->toEntity($facebookEventDto, $facebookEvent);
            $entityManager->flush();

            return $this->redirectToRoute('admin_facebook_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/facebook_event/edit.html.twig', [
            'facebook_event' => $facebookEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_facebook_event_delete', methods: ['POST'])]
    public function delete(Request $request, FacebookEvent $facebookEvent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facebookEvent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($facebookEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_facebook_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
