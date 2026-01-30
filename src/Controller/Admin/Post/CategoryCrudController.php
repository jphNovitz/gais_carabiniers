<?php

namespace App\Controller\Admin\Post;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Translation\TranslatableMessage;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setFormThemes(['@FOSCKEditor/Form/ckeditor_widget.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
            ->setEntityLabelInSingular('form.category')
            ->setEntityLabelInPlural('form.categories')
            ->setDateFormat('long');
        return $crud;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setColumns('20')
                ->setLabel(new TranslatableMessage('form.title')),
            TextField::new('seoTitle')
                ->setColumns('20')
                ->setLabel(new TranslatableMessage('form.seo_title'))
                ->setHelp('Max 65 caractères')
                ->setFormTypeOption('attr', ['maxlength' => 65]),
            TextField::new('slugTitle')->setColumns('20')
                ->setLabel(new TranslatableMessage('form.slug_title')),
            TextField::new('summary')->setColumns('20'),
            TextField::new('seoSummary', new TranslatableMessage('form.seo_description'))
                ->setColumns(12)
                ->setMaxLength(160),
            TextEditorField::new('content', new TranslatableMessage('form.category_content'))
                ->setFormType(CKEditorType::class)
                ->setColumns('20')
                ->hideOnIndex(),
            ImageField::new('image', new TranslatableMessage('Image'))
                ->setUploadDir('public/images/topic/')
                ->setBasePath('public/images/topic/'),
            AssociationField::new('parentCategory', new TranslatableMessage('form.parent_category'))
                ->setColumns(6)
                ->setFormTypeOption('choice_label', 'title')
                ->setFormTypeOption('placeholder', 'Select a topic')
                ->setRequired(false)        // champ pas obligatoire
                ->setFormTypeOption('required', false) // le FormType Symfony devient nullable
                ->setFormTypeOption('placeholder', '— Aucun —')
        ];
    }
}
