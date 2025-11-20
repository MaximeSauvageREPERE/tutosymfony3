<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'placeholder' => 'Nom du tag',
                ],
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nom',
                'group_by' => function($produit) {
                    return $produit->getCategorie() ? $produit->getCategorie()->getNom() : 'Sans catégorie';
                },
                'multiple' => true,
                'expanded' => true, // Affiche des cases à cocher
                'required' => false,
                'label_attr' => ['class' => 'd-block'],
                'attr' => ['class' => 'd-flex flex-column'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
