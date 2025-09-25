<?php

namespace App\Controller\Admin\Meeting;

use App\Contract\MeetingCreatorInterface;
use App\Repository\MeetingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/meeting')]
final class MeetingController extends AbstractController
{
    #[Route(name: 'admin_meeting_index', methods: ['GET'])]
    public function index(MeetingRepository $meetingRepository): Response
    {
        return $this->render('admin/meeting/index.html.twig', [
            'meetings' => $meetingRepository->findAll()
        ]);
    }

    #[Route('/create', name: 'admin_meeting_create', methods: ['POST'])]
    public function create(MeetingCreatorInterface $meetingCreator): Response
    {
        $meetingCreator->createMeeting();
        die;
        return $this->redirectToRoute('admin_meeting_index', [], Response::HTTP_FOUND);
    }
}
