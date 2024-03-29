<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Nature;
use App\Entity\Qualite;
use App\Entity\Confrere;
use App\Entity\Personnel;
use App\Entity\QualiteAd;
use App\Entity\Adversaire;
use App\Entity\Contentieux;
use App\Entity\Juridiction;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContentieuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('rg')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'input' => 'datetime_immutable',
            ])
            ->add('objet')
            ->add('commentaire', TextareaType::class)
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'placeholder' => '--- Choisir un client ---',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },

                'choice_label' => 'fullName',
            ])
            ->add('avocat', EntityType::class, [
                'class' => Personnel::class,
                'placeholder' => '--- Choisir un avocat ---',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },

                'choice_label' => 'fullName',
            ])
            ->add('qualite', EntityType::class, [
                'class' => Qualite::class,
                'placeholder' => '--- Choisir une qualité ---',
                'choice_label' => 'titre',
            ])
            ->add('qualiteAd', EntityType::class, [
                'class' => QualiteAd::class,
                'placeholder' => '--- Choisir une qualité ---',
                'choice_label' => 'titre',
            ])
            ->add('confrere', EntityType::class, [
                'class' => Confrere::class,
                'placeholder' => '--- Choisir un avocat adverse ---',
                'choice_label' => function ($confrere) {
                    return $confrere->getNom() . ' ' . $confrere->getPrenom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },

            ])
            ->add('juridiction', EntityType::class, [
                'class' => Juridiction::class,
                'placeholder' => '--- Choisir une juridiction ---',
                'choice_label' => function ($juridiction) {
                    return $juridiction->getTitre() . ' - ' . $juridiction->getLieu();
                }
            ])
            ->add('nature', EntityType::class, [
                'class' => Nature::class,
                'placeholder' => '--- Choisir une nature ---',
                'choice_label' => 'titre',
            ])
            ->add('adversaire', EntityType::class, [
                'class' => Adversaire::class,
                'placeholder' => '--- Choisir un client adverse ---',
                'choice_label' => function ($confrere) {
                    return $confrere->getNom() . ' ' . $confrere->getPrenom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contentieux::class,
        ]);
    }
}
