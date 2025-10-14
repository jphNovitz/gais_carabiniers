<?php

namespace App\Form;

use App\Entity\MeetingParticipant;
use App\Entity\Round;
use App\Entity\RoundShot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoundShotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('score')
            ->add('round', EntityType::class, [
                'class' => Round::class,
                'choice_label' => 'id',
            ])
            ->add('meetingParticipant', EntityType::class, [
                'class' => MeetingParticipant::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoundShot::class,
        ]);
    }
}
