<?php

namespace App\Form;

use App\Entity\SuperHero;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'Équipe',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de l\'équipe ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom de l\'équipe doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de l\'équipe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Équipe Active',
                'required' => false,
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de Création',
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de création est requise.',
                    ]),
                ],
            ])
            ->add('leader', EntityType::class, [
                'class' => SuperHero::class,
                'choice_label' => 'name',
                'label' => 'Chef d\'Équipe',
                'required' => true,
                'placeholder' => 'Sélectionnez un chef d\'équipe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Un chef d\'équipe doit être sélectionné.',
                    ]),
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.available = :available')
                        ->setParameter('available', true);
                },
            ])
            ->add('members', EntityType::class, [
                'class' => SuperHero::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Membres de l\'Équipe (hors chef)',
                'required' => false,
                'help' => 'Les membres de l\'équipe en plus du chef sélectionné.',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.available = :available')
                        ->setParameter('available', true);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
