<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Translation\TranslatableMessage;


class PostCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setFormThemes(['@FOSCKEditor/Form/ckeditor_widget.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setDateFormat('long');
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', new TranslatableMessage('form.title'))->setColumns(12),
            TextField::new('seoTitle')
                ->setColumns('20')
                ->setLabel(new TranslatableMessage('form.seo_title'))
                ->setHelp('Max 65 caractères')
                ->setFormTypeOption('attr', ['maxlength' => 65]),
            TextField::new('slugTitle')
                ->setColumns('20')
                ->setLabel(new TranslatableMessage('form.slug_title')),
            TextField::new('summary', new TranslatableMessage('form.summary'))
                ->setColumns(12)
                ->setMaxLength(255),
            TextField::new('seoSummary', new TranslatableMessage('form.seo_description'))->setColumns(12)
                ->setMaxLength(160),
            TextEditorField::new('content', new TranslatableMessage('form.content'))
                ->setFormType(CKEditorType::class)
                ->setColumns('20')
                ->hideOnIndex()
                ->setTrixEditorConfig([
                    'blockAttributes' => [
                        'default' => ['tagName' => 'p'],
                        'heading1' => ['tagName' => 'h3']
                    ]
                ]),
            BooleanField::new('pin', new TranslatableMessage('form.pin')),
            BooleanField::new('published', new TranslatableMessage('form.published')),
            ImageField::new('image', new TranslatableMessage('Image'))
                ->setUploadDir('public/images/blog/')
                ->setBasePath('public/images/blog/'),
            AssociationField::new('category', new TranslatableMessage('form.category'))
                ->setCrudController(CategoryCrudController::class),
        ];
    }
}
