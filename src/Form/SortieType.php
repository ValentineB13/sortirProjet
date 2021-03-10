<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de l\'événement ', 'required' => true])

            ->add('dateDebut', DateType::class,
                [
                    'label' => 'Date',
                    'widget' => 'single_text',
                    'required' => true,

                ]
            )
            ->add('duree', IntegerType::class,
                [
                    'label' => 'Durée',
                    'required' => true,
                    'empty_data' => 0,
                ])
            ->add('dateCloture',DateType::class,
                [
                    'label' => 'Date limite d\'inscription',
                    'widget' => 'single_text',
                    'required' => true,

                ])
            ->add('nbInscriptionsMax',IntegerType::class,
                [
                    'label' => 'Nombre maximum de participants ',
                    'required' => true,
                    'empty_data' => 0,
                ])
            ->add('descriptionInfos', TextType::class, ['label' => 'Informations complémentaires ', 'required' => false])
            ->add(
                'ville',
                EntityType::class,
                [
                    'class' => Ville::class,
                    'choice_label' => function ($ville) {
                        return $ville->getNomVille().' '.$ville->getCodePostal();
                    },
                    'mapped' => false,
                    'placeholder' => 'Choisir la ville',
                    'attr' => [
                        'class' => 'select2',

                    ],
                ]
            )
            ->add(
                'lieuSortie',
                EntityType::class,
                [
                    'class' => Lieu::class,
                    'label' => 'Lieu',
                    'choice_label' => function ($lieu) {
                       return $lieu->getNomLieu();
                    },
                    'placeholder'=> 'Choisir le lieu',

                ]
            );



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }


}
