<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Power;
use App\Entity\SuperHero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SuperHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du Héro',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du héro ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('alterEgo', TextType::class, [
                'label' => 'Choix du alterEgo',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'L\'alter ego ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('available', CheckboxType::class, [
                'label' => 'Disponibilité du Héro',
                'required' => false,
            ])
            ->add('energyLevel', IntegerType::class, [
                'label' => 'Niveau d\'énergie',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le niveau d\'énergie ne peut pas être vide.',
                    ]),
                    new Range([
                        'min' => 0,
                        'max' => 100,
                        'notInRangeMessage' => 'Le niveau d\'énergie doit être compris entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            ->add('biography', TextType::class, [
                'label' => 'Biographie',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'La biographie ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('imageName', FileType::class, [
                'label' => 'Photo du Héro',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG ou GIF).',
                    ])
                ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Création de la fiche',
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de création ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('powers', EntityType::class, [
                'class' => Power::class,
                'label' => 'Pouvoirs',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false,  // Requis pour une relation ManyToMany
            ])
            // Si vous souhaitez ajouter la relation avec les équipes (teams) ultérieurement :
            // ->add('teams', EntityType::class, [
            //     'class' => Team::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuperHero::class,
        ]);
    }
}
