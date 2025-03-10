<?php

namespace App\Controller\Admin;

use App\Dto\MemberDto;
use App\Entity\FacebookEvent;
use App\Entity\Member;
use App\Form\MemberType;
use App\Mapper\MemberMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/member')]
final class MemberController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MemberMapper $memberMapper)
    {
    }

    #[Route(name: 'admin_member_index', methods: ['GET'])]
    public function index(): Response
    {
        $members = $this->entityManager
            ->getRepository(Member::class)
            ->findAll();

        return $this->render('admin/member/index.html.twig', [
            'members' => $members,
        ]);
    }

    #[Route('/new', name: 'admin_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $memberDto = new MemberDto();

        $form = $this->createForm(MemberType::class, $memberDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $member = $this->memberMapper->toEntity($memberDto);
            $this->entityManager->persist($member);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_member_index', [], Response::HTTP_FOUND);
        }

        return $this->render('admin/member/new.html.twig', [
            'member' => $memberDto,
            'form' => $form,
        ]);
    }


    #[Route('/{slug}/edit', name: 'admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member): Response
    {
        $memberDto = $this->memberMapper->fromEntity($member);
        $form = $this->createForm(MemberType::class, $memberDto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member = $this->memberMapper->toEntity($memberDto, $member);

            $this->entityManager->flush();

            return $this->redirectToRoute('admin_member_index', [], Response::HTTP_FOUND);
        }

        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }
}
