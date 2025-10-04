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

