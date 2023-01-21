<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Float_;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->HideOnForm(),
            TextField::new('reference'),
            IntegerField::new('nbArticle'),
            NumberField::new('Total'),
            AssociationField::new('user'),
            AssociationField::new('transporteur'),
            AssociationField::new('adresse'),

            AssociationField::new('status'),
            
        ];
    }
    
}
