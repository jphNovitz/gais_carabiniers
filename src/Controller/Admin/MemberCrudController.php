<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MemberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Member::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
         return [
            TextField::new('firstName')->setLabel('member.firstname')->setColumns(6),
            TextField::new('lastName')->setLabel('member.lastname')->setColumns(6),
            BooleanField::new('isactive')->setLabel('member.isActive')->setColumns(4),
            BooleanField::new('usesSupport')->setLabel('member.usesSupport')->setColumns(4),
                 
       ];
     }
     
}
