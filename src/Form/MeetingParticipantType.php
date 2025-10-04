<?php

namespace App\Form;

use App\Dto\MeetingParticipantDto;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('shooter', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Member::class,
                    'choice_label' => function (Member $member) {
                        return $member->getFirstName() . ' ' . $member->getLastName();
                    },
                    'label' => 'Shooter',
                    'placeholder' => 'Select a shooter',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Participants',
                'attr' => [
                    'class' => 'participants-collection'
                ]
            ])
        ;
//        $builder->add('participants', CollectionType::class, [
//            'entry_type' => MeetingParticipantType::class,
//            'entry_options' => ['label' => false],
//            'allow_add' => true,
//            'allow_delete' => true,
//            'by_reference' => false,
//            'label' => 'Participants',
//            'attr' => [
//                'class' => 'participants-collection'
//            ]
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeetingParticipantDto::class,
        ]);
    }
}
