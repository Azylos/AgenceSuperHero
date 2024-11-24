<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Team;
use App\Enum\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la Mission',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre de la mission ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Le titre doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la Mission',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la Mission',
                'choices' => [
                    'En Attente' => Statut::PENDING,
                    'En Cours' => Statut::IN_PROGRESS,
                    'Terminé' => Statut::COMPLETED,
                    'Échoué' => Statut::FAILED,
                ],
                'required' => true,
                'expanded' => false, // false pour liste déroulante, true pour boutons radio
                'multiple' => false, // Choix unique
            ])
            ->add('startAt', null, [
                'label' => 'Date de Début',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('endAt', null, [
                'label' => 'Date de Fin',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La localisation de la mission est requise.',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'La localisation ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('dangerLevel', IntegerType::class, [
                'label' => 'Niveau de Danger (1 à 5)',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le niveau de danger est requis.',
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Le niveau de danger doit être compris entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('assignedTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'label' => 'Équipe Assignée',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
