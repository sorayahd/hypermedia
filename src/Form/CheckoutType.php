<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; //injection des données de l'utilisateur

        $builder //construction du formulaire
            ->add('address', EntityType::class, [
                'class'=> Adresse::class,
                'required'=>true,
                'choices'=>$user->getAdresses(),
                'multiple'=>false,
                'expanded'=>true //design un checkbox
             ])//adress est le nom de la table
            ->add('transporteurs', EntityType::class, [
                'class'=> Transporteur::class,
                'required'=>true,
                'multiple'=>false,
                'expanded'=>true
            ])//options du champs, requis, choix multiple = non un seul choix, étendu = oui
           ;
        
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
