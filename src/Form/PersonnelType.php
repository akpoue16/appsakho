<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PersonnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', ChoiceType::class, [
                'choices'  => [
                    'Mlle' => 'Mademoiselle',
                    'Mme' => 'Madame',
                    'Mr' => 'Monsieur',
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('tel', TelType::class, [
                'help'=> 'Format: 0102030405'
            ])
            ->add('cel', TelType::class, [
                'help'=> 'Format: 0102030405'
            ])
            ->add('email')
            ->add('image', FileType::class, [
                'label' => ' ',
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide. ',
                        'maxSizeMessage' => 'Le fichier est trop volumineux. La taille maximale autorisée est de {{ limit }}. '
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
        ]);
    }
}
