<?php

namespace App\Form;

use App\Entity\Audience;
use App\Entity\ResultatAudience;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ResultatAudienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->audience = $options['audience'];

        //dd($options['audience']);
        $builder
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                //'format' => 'yyyy-MM-ddThh:mm',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'input' => 'datetime_immutable',
            ])
            ->add('resultat',  CKEditorType::class)
            ->add('audience', EntityType::class, [
                'class' => Audience::class,
                'choice_label' => function ($audience) {
                    return $audience->getCode() . ' ' . $audience->getContentieux()->getCode();
                },
                'placeholder' => '--- Choisir une audience ---',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.code', 'ASC');
                },
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $audience = $this->audience;
                //dd($audience);
                $form = $event->getForm();

                if ($audience == null) {
                    //$audience = $event->getData()->getAudience()->getId();
                    return;
                } else {
                    $form->add('audience', EntityType::class, [
                        'class' => Audience::class,
                        'choice_label' => 'code',
                        'disabled' => true,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->where('u.id = :audience')
                                ->setParameter("audience", $this->audience);
                        },
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResultatAudience::class,
            'audience' => null,
        ]);
    }
}
