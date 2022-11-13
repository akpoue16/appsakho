<?php

namespace App\Form;

use App\Entity\Diligence;
use App\Entity\Contentieux;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class DiligenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => [
                    'class' => 'js-datepicker',
                ],
                'input' => 'datetime_immutable',
                'required' => false
            ])
            ->add('motif')
            ->add('debutTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('finTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('observation')
            ->add('contentieux', EntityType::class, [
                'class' => Contentieux::class,
                'choice_label' => function ($contentieux) {
                    return $contentieux->getCode() . ' : Pour ' . $contentieux->getClient()->getNom() . '  ' . $contentieux->getClient()->getPrenom() . ' contre ' . $contentieux->getAdversaire()->getNom() . '  ' . $contentieux->getAdversaire()->getPrenom();
                },
                'placeholder' => '--- Choisir un contentieux ---',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.code', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Diligence::class,
        ]);
    }
}
