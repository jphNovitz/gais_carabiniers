<?php

namespace App\Controller\Admin\Faq;

use App\Entity\Faq;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

final class FaqCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faq::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['@FOSCKEditor/Form/ckeditor_widget.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
            ->setEntityLabelInSingular('FAQ')
            ->setEntityLabelInPlural('FAQ')
            ->setDefaultSort(['position' => 'ASC', 'question' => 'ASC'])
            ->setDateFormat('long');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('question', 'Question')->setColumns(12);
        yield TextEditorField::new('answer', 'Réponse')
            ->setFormType(CKEditorType::class)
            ->setColumns(12)
            ->hideOnIndex();
        yield AssociationField::new('category', 'Catégorie')
            ->setFormTypeOption('choice_label', 'name')
            ->setFormTypeOption('placeholder', '— Aucune —')
            ->setRequired(false);
        yield IntegerField::new('position', 'Position');
        yield BooleanField::new('isPublished', 'Publiée');
        yield DateTimeField::new('updatedAt', 'Mise à jour')->onlyOnIndex();
    }
}
