<?php

namespace App\Form;

use App\Dto\FaqDto;
use App\Dto\FaqCategoryDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'Question',
            ])
            ->add('answer', TextareaType::class, [
                'label' => 'Réponse',
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'required' => false,
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => $options['categories'],
                'choice_label' => function ($category) {
                    return $category instanceof FaqCategoryDto ? $category->name : (string)$category;
                },
                'choice_value' => function ($category) {
                    return $category instanceof FaqCategoryDto ? $category->id : $category;
                },
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FaqDto::class,
            'categories' => [],
            'translation_domain' => 'messages',
        ]);
    }
} 