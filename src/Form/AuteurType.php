<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuteurType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :',
                'required' => true,
                'attr' => [
                        'placeholder' => "Prénom de votre auteur",
                    ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom :',
                'required' => true,
                'attr' => [
                        'placeholder' => "Nom de votre auteur",
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
