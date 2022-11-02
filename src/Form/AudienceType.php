<?php

namespace App\Form;

use App\Entity\Audience;
use App\Entity\Personnel;
use App\Entity\Contentieux;
use App\Entity\Juridiction;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AudienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->contentieux = $options['contentieux'];
        //dd($this->contentieux);
        $builder
            ->add('code')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'input' => 'datetime_immutable',
            ])
            ->add('avocat', EntityType::class, [
                'class' => Personnel::class,
                'placeholder' => '--- Choisir un collaborateur ---',
                'choice_label' => 'fullName',
            ])
            ->add('juridiction', EntityType::class, [
                'class' => Juridiction::class,
                'placeholder' => '--- Choisir une Juridiction ---',
                'choice_label' => 'titre',
            ])
            ->add('conseil')
            ->add('motif')
            ->add('procedures')
            ->add('renvoyer', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'input' => 'datetime_immutable',
            ])
            ->add('nomPresident')
            ->add('nomGreffier')
            ->add('contentieux', EntityType::class, [
                'class' => Contentieux::class,
                'choice_label' => 'code',
                'placeholder' => '--- Choisir un contentieux ---',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.code', 'ASC');
                },
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $contentieux = $event->getData();
                $form = $event->getForm();

                if (!$contentieux) {
                    return;
                }
                $form->add('contentieux', EntityType::class, [
                    'class' => Contentieux::class,
                    'choice_label' => 'code',
                    'disabled' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.id = :contentieux')
                            ->setParameter("contentieux", $this->contentieux);
                    },
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Audience::class,
            'contentieux' => null,
        ]);
    }
}
