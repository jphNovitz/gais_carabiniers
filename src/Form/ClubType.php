<?php

namespace App\Form;

use App\Dto\ClubDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'club.name',
            ])
            ->add('federationNumber', TextType::class, [
                'label' => 'club.federation_number',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'club.phone_number',
            ])
            ->add('description', TextType::class, [
                'label' => 'club.description',
            ])
            ->add('street', TextType::class, [
                'label' => 'club.street',
            ])
            ->add('streetNumber', TextType::class, [
                'label' => 'club.street_number',
            ])
            ->add('postCode', TextType::class, [
                'label' => 'club.post_code',
            ])
            ->add('city', TextType::class, [
                'label' => 'club.city',
            ])
            ->add('email', TextType::class, [
                'label' => 'club.email',
            ])
            ->add('logoFile', VichImageType::class, [
                'required' => false,
                'label' => 'store.logo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClubDto::class,
            'translation_domain' => 'messages',
        ]);
    }
}
