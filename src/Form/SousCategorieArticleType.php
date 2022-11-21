<?php

namespace App\Form;

use App\Entity\CategorieArticle;
use App\Entity\SousCategorieArticle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousCategorieArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('Categorie',EntityType::class, [
                'required' => false,
                'class' => CategorieArticle::class,
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategorieArticle::class,
        ]);
    }
}
