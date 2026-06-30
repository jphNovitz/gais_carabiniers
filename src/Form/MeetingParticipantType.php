<?php

namespace App\Form;

use App\Entity\MeetingParticipant;
use App\Entity\Member;
use App\Enum\ShootingCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('meeting', EntityType::class, [
//                'class' => Meeting::class,
//                'choice_label' => 'id',
//            ])
//            ->add('shooter', CollectionType::class, [
//                'entry_type' => EntityType::class,
//                'entry_options' => [
//                    'class' => Member::class,
//                    'choice_label' => function (Member $member) {
//                        return $member->getFirstName() . ' ' . $member->getLastName();
//                    },
//                    'label' => 'Shooter',
//                    'placeholder' => 'Select a shooter',
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,
//                'label' => 'Participants',
//                'attr' => [
//                    'class' => 'participants-collection'
//                ]
//            ])
//        ;
            ->add('shooter', EntityType::class, [
                'class' => Member::class,
                'choice_label' => fn(Member $m) => $m->getFirstName() . ' ' . $m->getLastName(),
//                'expanded' => true,
                'placeholder' => 'Sélectionner un tireur',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('shootingCategory', EnumType::class, [
                'class' => ShootingCategory::class,
                'label' => 'form.meeting.participant.shooting_category',
                'choice_label' => fn(ShootingCategory $choice) => match ($choice) {
                    ShootingCategory::CLASSIC => 'meeting.shooting_category.classic',
                    ShootingCategory::SUPPORTED => 'meeting.shooting_category.supported',
                },
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
//            ->add('shooter', EntityType::class, [
//                'class' => Member::class,
//                'choice_label' => function (Member $member) {
//                    return $member->getFirstName() . ' ' . $member->getLastName();
//                },
//                'label' => 'Shooter',
//                'placeholder' => 'Select a shooter',
//            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeetingParticipant::class,
            'ea_crud_form_type' => false
//            'data_class' => MeetingParticipantDto::class,
        ]);
    }
}
