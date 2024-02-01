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

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('propertyFeatures', CollectionType::class, [
                'entry_type' => TextType::class, // Vous pouvez utiliser un autre type ici selon vos besoins
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Caractéristiques supplémentaires',
            ])           
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre la propriété',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class, // Remplacez par le nom de votre classe d'entité
        ]);
    }



}
