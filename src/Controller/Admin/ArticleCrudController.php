<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            MoneyField::new('prix')->setCurrency('EUR'),
            MoneyField::new('promotion')->setCurrency('EUR'),
            
            TextEditorField::new('description'),
            AssociationField::new('categorie'),
            AssociationField::new('sexe'),
            ImageField::new('image')->setBasePath('uploads/article/')
                                    ->setUploadDir('public/uploads/article/')
                                    ->setUploadedFileNamePattern('[randomhash].[extension]')
        ];
    }
    
}
