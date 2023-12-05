<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LivreType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre :',
                'required' => true,
                'attr' => [
                    'placeholder' => "Titre de votre livre",
                ]
            ])
            // ->add('categories', EntityType::class, [
            //     'class' => Categorie::class,
            //     'label' => 'Catégories : ',
            //     'choice_label' => 'Categorie',
            //     'multiple' => true,
            //     'required' => false,
            // ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégories :',
                'required' => false,
                'choice_label' => 'titre',
                'multiple' => true,
            ])

            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'label' => "Auteur : ",
                'choice_label' => 'nom',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
