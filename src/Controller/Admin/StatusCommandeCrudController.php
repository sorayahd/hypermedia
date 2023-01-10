<?php

namespace App\Controller\Admin;

use App\Entity\StatusCommande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StatusCommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StatusCommande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('status'),
           //TextEditorField::new('description'),
        ];
    }
    
}
