<?php

namespace App\Form;

use App\Dto\ClubDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('federationNumber')
            ->add('phoneNumber')
            ->add('description')
            ->add('logoName')
            ->add('logoSize')
            ->add('imageName')
            ->add('imageSize')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('street')
            ->add('streetNumber')
            ->add('postCode')
            ->add('city')
            ->add('email')
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClubDto::class,
        ]);
    }
}
