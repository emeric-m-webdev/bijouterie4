<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Matiere;
use App\Filter\ProduitFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProduitFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recherche', TextType::class, [
                "required" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Recherche..."
                ]
            ])

            ->add("min", MoneyType::class, [
                "required" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Prix minimum"
                ]
            ])

            ->add("max", MoneyType::class, [
                "required" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Prix maximum"
                ]
            ])


            ->add("order", ChoiceType::class, [
                "required" => false,
                "label" => false,
                "placeholder" => "Trier par :",
                "choices" => [
                    "Prix croissant" => 1,
                    "Prix décroissant" => 2,
                    "Titre croissant" => 3,
                    "Titre décroissant" => 4
                ]
            ])

            ->add("categories", EntityType::class, [
                "class" => Categorie::class,
                "choice_label" => "nom",
                "multiple" => true,
                "expanded" => true,
                "required" => false
            ])

            ->add("marques", EntityType::class, [
                "class" => Marque::class,
                "choice_label" => "nom",
                "multiple" => true,
                "expanded" => true,
                "required" => false
            ])

            ->add("matieres", EntityType::class, [
                "class" => Matiere::class,
                "choice_label" => "nom",
                "multiple" => true,
                "expanded" => true,
                "required" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => ProduitFilter::class
        ]);
    }
}
