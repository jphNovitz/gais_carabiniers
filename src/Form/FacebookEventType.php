<?php

namespace App\Form;

use App\Dto\FacebookEventDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacebookEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'event.name',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'event.date',
            ])
            ->add('facebookLink', TextType::class, [
                'label' => 'event.link',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FacebookEventDto::class,
            'translation_domain' => 'messages',
        ]);
    }
}
