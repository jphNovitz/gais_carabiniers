<?php

namespace App\Form;

use App\Entity\Round;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roundShots', CollectionType::class, [
                'entry_type' => RoundShotType::class,
                'entry_options' => [
                    'label' => false,
                    'last_hit_targets' => $options['last_hit_targets']
                ],
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['last_hit_targets'] = $options['last_hit_targets'];
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Round::class,
            'last_hit_targets' => [],
        ]);
    }
}