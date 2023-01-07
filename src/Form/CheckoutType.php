<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];
        $builder
            ->add('transporteur', EntityType::class, [
                'class' => Transporteur::class,
                 'expanded' => true


            ])
                // ->add('adresse');
                //     ->add('user')
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'required' => true,
                'choices' => $user->getAdresses(),
                //'multiple' => false,
                // 'expanded' => true //design un checkbox
            ]);
            //  ->add ('confirmer',SubmitType::class);
           
        
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
