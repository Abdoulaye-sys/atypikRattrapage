<?php

namespace App\Form;

use App\Entity\Hebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateArrive', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull(),
                    new Expression([
                        'expression' => 'value < this.getParent()["DateDepart"].getData()',
                        'message' => 'La date d\'arrivée doit être supérieure à la date d\'aujourd\'hui et inférieure à la date de départ.',
                    ]),
                ],
            ])
            ->add('DateDepart', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull(),
                    new GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de départ doit être supérieure à aujourd\'hui.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider et Payer',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->add('pid', HiddenType::class, [
                'mapped' => false, // Ne mappez pas ce champ à l'entité
            ])
            ->add('PrixInitial', HiddenType::class, [
            'required' => true,
        ]);
    }

    // ...
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
            'prix_initial' => null,
        ]);
    }
}