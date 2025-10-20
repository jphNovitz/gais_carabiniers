<?php

namespace App\Form;

use App\Dto\MeetingDto;
use App\Entity\Meeting;
use App\Entity\Member;
use App\Repository\MemberRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddParticipantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shooters', EntityType::class, [
            'class' => Member::class,
            'multiple' => true,
            'expanded' => true,
            'mapped' => false,
            'query_builder' => fn(MemberRepository $r) => $r->qbActifsNonParticipants($options['meeting']),
            'attr' => [
                'class' => 'w-full flex flex-wrap gap-4 space-y-4 bg-base-light dark:bg-base-dark border border-gray-300 text-content-primary-light dark:text-content-primary-dark text-sm rounded-lg focus:ring-content-secondary focus:border-content-secondary block p-8'
            ],
            'row_attr' => [
                'class' => 'shooter-row w-[50%]'
            ]

        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'meeting' => null,
        ]);
        $resolver->setAllowedTypes('meeting', [Meeting::class, 'null']);
    }
}

