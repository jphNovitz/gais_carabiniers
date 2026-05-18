<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Repository\MeetingParticipantRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MemberCrudController extends AbstractCrudController
{

    public function __construct(private readonly MeetingParticipantRepository $meetingParticipantRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Member::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName')->setLabel('member.firstname')->setColumns(6),
            TextField::new('lastName')->setLabel('member.lastname')->setColumns(6),
            TextField::new('email')->setLabel('member.email')->setColumns(6),
            Field::new('profileImageFile')->setLabel('member.profileImage')
                ->setFormType(VichImageType::class)
                ->onlyOnForms()
                ->setColumns(12),
            ImageField::new('profileImageName')->setLabel('member.profileImage')
                ->setBasePath('images/members')
                ->onlyOnIndex(),
            TextField::new('phone')->setLabel('member.phone')->setColumns(6)->hideOnIndex(),
            TextField::new('street')->setLabel('member.street')->setColumns(8)->hideOnIndex(),
            TextField::new('streetNumber')->setLabel('member.streetNumber')->setColumns(4)->hideOnIndex(),
            TextField::new('postalCode')->setLabel('member.postalCode')->setColumns(4)->hideOnIndex(),
            TextField::new('city')->setLabel('member.city')->setColumns(8)->hideOnIndex(),
            BooleanField::new('isActive')->setLabel('member.isActive')->setColumns(4),
            BooleanField::new('usesSupport')->setLabel('member.usesSupport')->setColumns(4),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $attendance = Action::new('attendance', 'member.attendances', 'fas fa-list')
            ->linkToRoute('admin_member_attendance', fn(Member $member) => ['id' => $member->getId()]);
        return $actions
            ->add(Crud::PAGE_INDEX, $attendance)
            ->add(Crud::PAGE_EDIT, $attendance);

    }

    #[Route('/admin/member/{id}/attendance', name: 'admin_member_attendance', methods: ['GET'])]
    public function attendance(Member $member): Response
    {
        $attendances = $this->meetingParticipantRepository->findMemberAttendance($member->getId());
        return $this->render('admin/member/member_attendance.html.twig', [
            'attendances' => $attendances,
            'member' => $member,
        ]);

    }

}
