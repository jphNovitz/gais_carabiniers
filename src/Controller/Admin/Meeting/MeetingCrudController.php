<?php

namespace App\Controller\Admin\Meeting;

use App\Contract\MeetingParticipantPositionerInterface;
use App\Contract\MeetingParticipantShufflerInterface;
use App\Contract\RoundManagerInterface;
use App\Entity\Meeting;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Form\MeetingParticipantType;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Contracts\Translation\TranslatorInterface;

class MeetingCrudController extends AbstractCrudController
{
    public function __construct(private readonly MeetingParticipantShufflerInterface $participantShuffler,
                                private readonly AdminContextProvider       $contextProvider,
                                private readonly MeetingRepository          $meetingRepository,
                                private readonly TranslatorInterface        $translator,
                                private MeetingParticipantPositionerInterface $participantPositioner,)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Meeting::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_DETAIL, fn(Meeting $m) => $m->getLabel() . " (" . count($m->getRounds()) . " passes)")
            ->setPageTitle(Crud::PAGE_INDEX, 'Séances')
            ->setPageTitle(Crud::PAGE_NEW, 'Nouvelle séance')
            ->setPageTitle(Crud::PAGE_EDIT, fn(Meeting $m) => 'Modifier — ' . $m->getLabel())
            ->setEntityLabelInSingular('')
            ->setEntityLabelInPlural('Clubs');
    }

    public function configureActions(Actions $actions): Actions
    {
        $meeting = $this->contextProvider->getContext()?->getEntity()?->getInstance();

        $reorder = Action::new('reorder', 'Réordonner', 'fas fa-sort')
            ->linkToRoute('admin_meeting_reorder', fn(Meeting $m) => ['id' => $m->getId()])
//            ->addCssClass('btn btn-secondary')
            ->displayIf(fn(Meeting $m) => in_array($m->getStatus(), [
                MeetingStatus::DRAFT,
                MeetingStatus::READY
            ]));

        $close = Action::new('close', 'Clôturer', 'fas fa-lock')
            ->linkToRoute('admin_meeting_close', fn(Meeting $m) => ['id' => $m->getId()])
            ->addCssClass('text-warning')
            ->displayIf(fn(Meeting $m) => $m->getStatus() === MeetingStatus::IN_PROGRESS);

        $play = Action::new('play', 'Démarrer', 'fas fa-play')
            ->linkToRoute('admin_round_all_shots', fn(Meeting $m) => [
                'meeting' => $m->getId(),
            ])
            ->addCssClass('text-success')
            ->setLabel(fn(Meeting $m) => $m->getRounds()->isEmpty() ? 'Démarrer' : 'Nouveau round')
            ->displayIf(fn(Meeting $m) => in_array($m->getStatus(), [
                MeetingStatus::READY,
                MeetingStatus::IN_PROGRESS,
            ]));



        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $play)
            ->add(Crud::PAGE_INDEX, $reorder)
            ->add(Crud::PAGE_EDIT, $reorder)
            ->add(Crud::PAGE_INDEX, $close)
            ->add(Crud::PAGE_EDIT, $close)
            ->add(Crud::PAGE_INDEX, $play)
            ->add(Crud::PAGE_EDIT, $play)
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn(Action $a) => $a
                ->displayIf(fn(Meeting $m) => !in_array($m->getStatus(), [
                    MeetingStatus::CLOSED,
                    MeetingStatus::ARCHIVED,
                ]))
            )
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn(Action $a) => $a
                ->displayIf(fn(Meeting $m) => !in_array($m->getStatus(), [
                    MeetingStatus::CLOSED,
                    MeetingStatus::ARCHIVED,
                ]))
            );
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('label', 'form.meeting.label.label');
        yield DateField::new('date', 'Date')->setFormat('dd/MM/yyyy');
        yield ChoiceField::new('type', 'form.meeting.label.type')
            ->setTranslatableChoices([
                MeetingType::COMPETITION->value => $this->translator->trans('form.meeting.type.competition'),
                MeetingType::PUBLIC->value      => $this->translator->trans('form.meeting.type.public'),
                MeetingType::OTHER->value       => $this->translator->trans('form.meeting.type.other'),
            ])
            ->setFormTypeOptions([
                'choice_value' => fn($choice) => $choice instanceof MeetingType ? $choice->value : $choice,
                'setter' => fn(Meeting $meeting, $value) => $meeting->setType(MeetingType::from($value)),
            ]);
//        yield ChoiceField::new('type', 'form.meeting.label.type')
//            ->setFormType(EnumType::class, [
//                'class' => MeetingType::class,
//                'choice_label' => fn($case) => $this->translator->trans('])form.meeting.type.' . $case->value)
//                    ])
//            ->setFormTypeOptions([
//                'class' => MeetingType::class,
//            ])
//            ->setTranslatableChoices([
//                MeetingType::COMPETITION->value => $this->translator->trans('form.meeting.type.competition'),
//                MeetingType::PUBLIC->value      => $this->translator->trans('form.meeting.type.public'),
//                MeetingType::OTHER->value       => $this->translator->trans('form.meeting.type.other'),
//            ]);
//            ->setFormTypeOptions([
//                'class' => MeetingType::class,
//                'choice_label' => fn($case) => $this->translator->trans('form.meeting.type.' . $case->value),
//            ]);
//        yield AssociationField::new('participants', 'Participants')
//            ->setFormTypeOptions([
//                'by_reference' => false,
//            ])
//
//            ->onlyOnForms();
        yield CollectionField::new('participants', 'Participants')
            ->setEntryType(MeetingParticipantType::class)
            ->renderExpanded(true)
            ->allowAdd()
            ->allowDelete()
            ->onlyOnForms();

        yield ChoiceField::new('status', 'Statut')
            ->setTranslatableChoices([
                MeetingStatus::DRAFT->value => $this->translator->trans('form.meeting.status.draft'),
                MeetingStatus::READY->value => $this->translator->trans('form.meeting.status.ready'),
                MeetingStatus::IN_PROGRESS->value => $this->translator->trans('form.meeting.status.in_progress'),
                MeetingStatus::CLOSED->value => $this->translator->trans('form.meeting.status.closed'),
                MeetingStatus::ARCHIVED->value => $this->translator->trans('form.meeting.status.archived'),
            ])
            ->renderAsBadges([
                MeetingStatus::DRAFT->value => 'primary',
                MeetingStatus::READY->value => 'primary',
                MeetingStatus::IN_PROGRESS->value => 'success',
                MeetingStatus::CLOSED->value => 'danger',
                MeetingStatus::ARCHIVED->value => 'dark',
            ])->hideOnForm();

        if ($pageName === Crud::PAGE_DETAIL) {
            $meeting = $this->contextProvider->getContext()->getEntity()->getInstance();

            if (count($meeting->getRounds()) > 0) {
                $meetingWithScores = $this->meetingRepository->findWithScores($meeting->getId());
                yield Field::new('participants', 'Classement')
                    ->setTemplatePath('admin/meeting/_standing.html.twig')
                    ->setCustomOption('meetingWithScores', $meetingWithScores)
                    ->onlyOnDetail();
            } else {
                $participants = $meeting->getParticipants();
                yield Field::new('participants', 'Classement')
                    ->setTemplatePath('admin/meeting/_participants.html.twig')
                    ->setCustomOption('participants', $participants)
                    ->onlyOnDetail();
            }
        }

    }

    public function createEntity(string $entityFqcn): Meeting
    {
        $meeting = new Meeting();
        $meeting->setDate(new \DateTimeImmutable('today'));
        return $meeting;
    }

    public function persistEntity(EntityManagerInterface $em, mixed $entityInstance): void
    {
        $this->participantPositioner->order($entityInstance);

        $em->persist($entityInstance);
        $em->flush();

        parent::persistEntity($em, $entityInstance);
//        $participants = $entityInstance->getParticipants()
//            ->map(fn($p) => $p->getShooter())
//            ->filter(fn($shooter) => $shooter !== null) // ← ajout
//            ->toArray();
//
//        $entityInstance->getParticipants()->clear();
//
//        if (!empty($participants)) {
//            $this->participantShuffler->addMany($entityInstance, $participants);
//        }
//
//        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, mixed $entityInstance): void
    {
        $this->participantPositioner->order($entityInstance);
        parent::updateEntity($em, $entityInstance);
    }

}
