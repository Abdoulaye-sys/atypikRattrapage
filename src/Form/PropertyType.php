<?php
// src/Form/PropertyType.php
namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\PropertyFeature;
use Doctrine\DBAL\Types\IntegerType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('pcontent', TextareaType::class, [
                'required' => true,
            ])
            // Ajoutez d'autres champs selon votre formulaire
            ->add('pimage', FileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ]),
                    new NotBlank([
                        'message' => 'Please upload an image file',
                    ]),
                ]
            ])
            ->add('type', TextType::class, [
                'required' => true,
            ])
            ->add('bedroom', TextType::class, [
                'required' => true,
            ])
            ->add('bathroom', TextType::class, [
                'required' => true,
            ])
            ->add('size', TextType::class, [
                'required' => true,
            ])
            ->add('price', TextType::class, [
                'required' => true,
            ])
            ->add('location', TextType::class, [
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('state', TextType::class, [
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ]) 
            // Modifiez votre formulaire pour utiliser le nouveau type imbriqué
            ->add('propertyFeatures', CollectionType::class, [
                'entry_type' => PropertyFeatureType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                // Autres options...
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider la proposition', // Modifier le libellé ici
                'attr' => [
                    'class' => 'btn btn-success btn-lg d-none', // Ajouter la classe d-none pour cacher le bouton
                    'style' => 'border-radius: 30px;',
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Property::class, // Remplacez par le nom de votre classe d'entité
        ]);
    }



}
