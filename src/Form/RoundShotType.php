<?php

namespace App\Form;

use App\Entity\MeetingParticipant;
use App\Entity\Round;
use App\Entity\RoundShot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoundShotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('leftHit', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => ''],
                'row_attr' => ['class' => 'b-2']
            ])
            ->add('leftTarget', ChoiceType::class, [
                'choices' => array_combine(range(1, 24), range(1, 24)),
                'required' => false,
                'placeholder' => '🎯',
                'attr' => [
                    'class' => 'w-12 disabled:opacity-30 disabled:cursor-not-allowed'
                ],
            ])
            ->add('rightHit', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => ''],
                'row_attr' => ['class' => 'b-2']
            ])
            ->add('rightTarget', ChoiceType::class, [
                'choices' => array_combine(range(1, 24), range(1, 24)),
                'required' => false,
                'placeholder' => '🎯',
                'attr' => [
                    'class' => 'w-12 disabled:opacity-30 disabled:cursor-not-allowed'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoundShot::class
        ]);
    }
}
