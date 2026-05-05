<?php

namespace App\Controller\Admin\Club;

use App\Entity\Club;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Translation\TranslatableMessage;

final class ClubCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Club::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('meeting.label.meeting')
            ->setEntityLabelInPlural('meeting.label.meetings');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name')
            ->setLabel(new TranslatableMessage('form.name'))
            ->setColumns(16);
        yield SlugField::new('slug')
            ->setTargetFieldName('name')
            ->onlyOnForms();
        yield TextField::new('federationNumber', 'Federation #')
            ->setLabel(new TranslatableMessage('form.federation_number'));
        yield TextField::new('phoneNumber', 'Phone')
            ->setLabel(new TranslatableMessage('form.phone_number'));
        yield EmailField::new('email')->setColumns(16)
            ->setLabel(new TranslatableMessage('form.email'));
        yield TextField::new('street')->setColumns(16)
            ->setLabel(new TranslatableMessage('form.street'));
        yield TextField::new('streetNumber')
            ->setLabel(new TranslatableMessage('form.street_number'));
        yield TextField::new('postCode')
            ->setLabel(new TranslatableMessage('form.post_code'));
        yield TextField::new('city')
            ->setLabel(new TranslatableMessage('form.city'));
        yield TextareaField::new('description')->setColumns(16);
        // Image fields: adjust basePath/uploadDir if your project stores uploads elsewhere
        yield ImageField::new('logoName', 'Logo')
            ->setBasePath('uploads/logos')
            ->setUploadDir('public/uploads/logos')
            ->onlyOnIndex()
            ->setLabel(new TranslatableMessage('form.logo'));
        yield ImageField::new('imageName', 'Image')
            ->setBasePath('uploads/images')
            ->setUploadDir('public/uploads/images')
            ->onlyOnIndex();
        yield BooleanField::new('isOwner', 'Club propriétaire du site') ;
    }
}
