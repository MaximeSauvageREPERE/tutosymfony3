<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'placeholder' => 'Nom du produit',
                ],
            ])
            ->add('description', null, [
                'attr' => [
                    'placeholder' => 'Description du produit',
                ],
            ])
            ->add('prix', null, [
                'attr' => [
                    'placeholder' => 'Prix du produit',
                ],
            ])
            ->add('creationdate', null, [
                'attr' => [
                    'placeholder' => 'Date de création',
                ],
            ])
            ->add('active', null, [
                'required' => false,
            ])
            ->add('image', null, [
                'attr' => [
                    'placeholder' => 'URL de l\'image',
                ],
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
