<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\SuperHero;
use Doctrine\DBAL\Types\StringType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Date;

class SuperHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', StringType::class, [
                'label'=> 'Nom du Héro',
            ])
            ->add('alterEgo', StringType::class, [
                'label'=> 'Choix du alterEgo',
            ])
            ->add('available', StringType::class, [
                'label'=> 'disponiblilité du Héro',
            ])
            ->add('energyLevel', Integer::class, [
                'label'=> 'Niveau d\'énergie',
            ])
            ->add('biography', StringType::class, [
                'label'=> 'Biography',
            ])
            ->add('imageName', FileType::class, [
                'label' => 'Photo du Héro',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG ou GIF)',
                        ])
                ],
            ])
            ->add('createdAt', Date::class, [
                'label'=> 'Création de la fiche',
                'widget' => 'single_text',
            ])
            // ->add('teams', EntityType::class, [
            //     'class' => Team::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
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
