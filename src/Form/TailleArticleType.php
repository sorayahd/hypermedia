<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Taille;
use App\Entity\TailleArticle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TailleArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('articls',EntityType::class, [
                'required' => true,
                'class' => Article::class,
                ])
            ->add('taille',EntityType::class, [
                'required' => true,
                'class' => Taille::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TailleArticle::class,
        ]);
    }
}
