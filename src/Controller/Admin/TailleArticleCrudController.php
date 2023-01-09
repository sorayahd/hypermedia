<?php

namespace App\Controller\Admin;

use App\Entity\TailleArticle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TailleArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TailleArticle::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         //IdField::new('id'),
    //         AssociationField::new('articls'),
    //         AssociationField::new('taille'),
    //     ];
    // }
    
}
