<?php

namespace App\Form;

use App\Entity\Campus;
use App\Service\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class'=>Campus::class,
                'label'=>'Choix du campus:',
                'choice_label'=>'nom_campus',
                'placeholder'=>'Sélectionnez un campus',
                'required'=>false
            ])
            ->add('searchIndice', TextType::class, [
                'label'=>'Le nom de la sortie contient :',
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Recherchez une sortie'
                ],
                'required' => false
            ])
            ->add('searchDateDebut',DateType::class, [
                'label'=>'Entre :',
                'format'=>'dd-MM-yyyy',
                'placeholder'=>['year'=>'Année', 'month'=>'Mois', 'day'=>'Jours'],
                'required' => false
            ])
            ->add('searchDateFin',DateType::class, [
                'label'=>'Et :',
                'format'=>'dd-MM-yyyy',
                'placeholder'=>['year'=>'Année', 'month'=>'Mois', 'day'=>'Jours'],
                'required' => false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur','required' => false
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

}