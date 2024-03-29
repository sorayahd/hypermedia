<?php

namespace App\Form;

use App\Entity\SearchByTaille;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByTailleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minTaille',null, [
                'label' => false,
               ])

             ->add('MaxTaille',null, [
             'label'=> false,
             ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchByTaille::class,
        ]);
    }
}
