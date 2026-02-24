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
            ->setEntityLabelInSingular('Facebook Event')
            ->setEntityLabelInPlural('Facebook Events')
            ->setDefaultSort(['date' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title', 'Titre');
        yield SlugField::new('slug')->setTargetFieldName('title');
        yield DateTimeField::new('date', 'Date');
        yield UrlField::new('facebookLink', 'Lien Facebook')->hideOnIndex();
        yield TextEditorField::new('description');
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
