<?php

namespace App\Form;

use App\Entity\Meeting;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingLabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'form.meeting.label.status',
                'choices' => [
                    'form.meeting.status.draft' => MeetingStatus::DRAFT,
                    'form.meeting.status.ready' => MeetingStatus::READY,
                    'form.meeting.status.in_progress' => MeetingStatus::IN_PROGRESS,
                    'form.meeting.status.closed' => MeetingStatus::CLOSED,
//                    'form.meeting.status.archived' => MeetingStatus::ARCHIVED,
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'form.meeting.label.type',
                'choices' => [
                    'form.meeting.type.competition' => MeetingType::COMPETITION,
                    'form.meeting.type.public' => MeetingType::PUBLIC,
                    'form.meeting.type.other' => MeetingType::OTHER,
                    ]
            ])
            ->add('label', TextType::class, [
                'label' => 'form.meeting.label.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
            'translation_domain' => 'messages',
        ]);
    }
}
