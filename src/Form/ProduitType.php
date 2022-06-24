<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Matiere;
use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder



            ->add('titre', TextType::class, [
                "label" => "Titre du produit*",
                "required" => false,
                "help" => "Saisir un titre entre .. et .. caractères",
                "attr" => [ // tableau des attributs de la balise input/textarea/select
                    "placeholder" => "Saisir un titre",
                    "class" => "border border-danger bg-light",
                    // "style" => "box-shadow: 2px 2px 4px blue",
                    // "value" => "blabla"
                ],
                "label_attr" => [ // tableau des attributs de la balise label
                    "class" => "text-primary"
                ],
                "row_attr" => [ // tableau des attributs de la balise div contenant label/input
                    "id" => "titreBlock"
                ],
                // "constraints" => [
                //     new NotBlank([
                //         "message" => "SAISIR UN TITRE !!!!"
                //     ]),
                //     new Length([
                //         "min" => 4,
                //         "max" => 15,
                //         "minMessage" => "4 min",
                //         "maxMessage" => "15 max"
                //     ])
                // ]
            ])





             ->add('stock', IntegerRype::class, [
                 "required" => false 
            ])

            ->add('prix', MoneyType::class, [
                "currency" => "USD",
                "required" => false,
                "label" => "Prix du produit*",
                "help" => "Prix en euro avec possibilités de 2 chiffres après la virgule",
                "attr" => [
                    "placeholder" => "Saisir un prix",
                    "class" => "border border-warning bg-light",
                ],
                "label_attr" => [ 
                    "class" => "text-success"
                ]
            ])





            ->add('description', TextareaType::class, [
                "help" => "Description facultative",
                "required" => false,
                "attr" => [
                    "rows" => 8,
                    "class" => "border border-info bg-light",
                ],
                "label_attr" => [ 
                    "class" => "text-danger"
                ]
            ])

            ->add('categorie', EntityType::class, [ // EntityType => relation
                "class" => Categorie::class, // nom de la class (entity)
                 "choice_label" => "nom", // nom de la propriété
                 "placeholder" => "Sélectionner une catégorie",
                 "required" => false,
                 "label" => "Catégorie*",
                 //"expanded" => true, // radio/checkbox
                 
            ])

            ->add('marque', EntityType::class, [
                "class" => Marque::class,
                'choice_label' => "nom", 
                "required" => false,
                "placeholder" => "Sélectionner une marque",
                "label" => "Marque*",
            ])

            ->add("matiere", EntityType::class, [
                "class" => Matiere::class,
                "choice_label" => "nom",
                "required" => FALSE,
                "multiple" => true, // obligatoire pour la relation ManyToMany (car on peut sélectionner plusieurs matières)
               // "expanded" => true // permet d'afficher des checkboxs
               "label" => "Matière(s)*",
               "attr" => [
                   "class" => "col-12"
               ]
            ])

        ;

            // ->add("Ajouter", SubmitType::class, [
            //     "attr" => [
            //         "class" => "btn btn-danger col-12"
            //     ]
            // ])

        if($options['ajouter'])
        {
            $builder->add('image', FileType::class, [
                "required" => false,
               // "data_class" => null,
                "attr" => [
                    'onchange' => "loadFile(event)"
                ]
            ]);
        }
        if($options['modifier'])
        {
            $builder->add('imageUpdate', FileType::class, [
                "required" => false,
                "mapped" => false, // qui n'est pas dans l'entity
               // "data_class" => null,
                "attr" => [
                    'onchange' => "loadFile(event)"
                ]
            ]);
        }

            
  
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'ajouter' => false,
            "modifier" => false
        ]);
    }
}
