<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre :',
                'required' => true,
                'attr' => [
                    'placeholder' => "Titre de votre catégorie",
                ]
            ])
            ->add('livres', EntityType::class, [
                'class' => Livre::class,
                'label' => "Livres de cette catégorie : ",
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}