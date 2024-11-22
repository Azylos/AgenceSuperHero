<?php

namespace App\Form;

use App\Entity\Power;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class PowerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Pouvoir',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du pouvoir ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom du pouvoir doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom du pouvoir ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du Pouvoir',
                'required' => false,
            ])
            ->add('level', IntegerType::class, [
                'label' => 'Niveau du Pouvoir (1 à 5)',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le niveau du pouvoir est requis.',
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Le niveau du pouvoir doit être compris entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Power::class,
        ]);
    }
}