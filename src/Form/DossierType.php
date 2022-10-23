<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Dossier;
use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class)
            ->add('nom', TextType::class)
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
            
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],

                'input' => 'datetime_immutable',
            ])
            ->add('updateAt', DateType::class, [
                'widget' => 'single_text',
            
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,
            
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],

                'input' => 'datetime_immutable',
                
                'required'   => false,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'placeholder' => '--- Choisir un client ---',
                'choice_label' => 'fullName',
            ])
            ->add('personnel', EntityType::class, [
                'class' => Personnel::class,
                'placeholder' => '--- Choisir un collaborateur ---',
                'choice_label' => 'fullName',
            ])
            ->add('commentaire', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
