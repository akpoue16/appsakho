<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('raisonSocial')
            ->add('contact')
            ->add('titre', ChoiceType::class, [
                'choices'  => [
                    'Mlle' => 'Mademoiselle',
                    'Mme' => 'Madame',
                    'Mr' => 'Monsieur',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Société' => 'Societe',
                    'Particulier' => 'Particulier',
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('cel')
            ->add('email')
            ->add('fax')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
