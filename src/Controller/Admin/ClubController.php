<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\Club1Type;
use App\Form\ClubType;
use App\Mapper\ClubMapper;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/club')]
final class ClubController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ClubMapper $clubMapper)
    {
    }

    #[Route(name: 'admin_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        $club = $this->entityManager->getRepository(Club::class)->findAll()[0];
        $clubDto = $this->clubMapper->fromEntity($club);
        return $this->render('admin/club/show.html.twig', [
            'club' => $clubDto,
        ]);
    }


    #[Route('/edit', name: 'admin_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $club = $this->entityManager->getRepository(Club::class)->findAll()[0];
        $clubDto = $this->clubMapper->fromEntity($club);
        $form = $this->createForm(ClubType::class, $clubDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $club = $this->clubMapper->toEntity($club, $clubDto);
            $entityManager->flush();

            return $this->redirectToRoute('admin_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

}
