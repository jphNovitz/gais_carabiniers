<?php

namespace App\Controller\Admin;

use App\Entity\FacebookEvent;
use App\Mapper\FacebookEventMapper;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class FacebookEventCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly FacebookEventMapper $facebookEventMapper
    ) {}

    public static function getEntityFqcn(): string
    {
        return FacebookEvent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['@FOSCKEditor/Form/ckeditor_widget.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
            ->setEntityLabelInSingular('facebook_event')
            ->setEntityLabelInPlural('facebook_events')
            ->setDefaultSort(['date' => 'DESC'])
            ->setDateFormat('long');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title', 'Titre')->setColumns(12);
        yield DateTimeField::new('date', 'Date');
        yield UrlField::new('facebookLink', 'Lien Facebook')->hideOnIndex()->setColumns(12);
        yield TextEditorField::new('description')
            ->setFormType(CKEditorType::class)
            ->setColumns(12)
            ->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Créé le')->onlyOnIndex();
        yield DateTimeField::new('updatedAt', 'Mis à jour')->onlyOnIndex();
    }

    /**
     * Called when creating a new entity from EasyAdmin form.
     * Apply DTO/mapper logic before persisting.
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof FacebookEvent) {
            // build DTO from the entity (reflects form changes) and re-apply mapper rules
            $dto = $this->facebookEventMapper->fromEntity($entityInstance);
            $this->facebookEventMapper->toEntity($dto, $entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Called when updating an existing entity via EasyAdmin form.
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof FacebookEvent) {
            $dto = $this->facebookEventMapper->fromEntity($entityInstance);
            $this->facebookEventMapper->toEntity($dto, $entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
