<?php

namespace App\Form;

use App\Entity\Adversaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdversaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('raisonSocieal')
            ->add('contact')
            ->add('titre', ChoiceType::class, [
                'choices'  => [
                    'Mlle' => 'Mademoiselle',
                    'Mme' => 'Madame',
                    'Mr' => 'Monsieur',
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('cel')
            ->add('email');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adversaire::class,
        ]);
    }
}
