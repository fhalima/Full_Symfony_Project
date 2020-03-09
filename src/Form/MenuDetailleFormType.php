<?php

namespace App\Form;

use App\Entity\MenuDetaille;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuDetailleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer un titre pour ce menu.']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le nom doit contenir au moin 8 caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le nom ne doit pas contenir plus de 100 caractères.',

                    ])

                ]
            ])
            ->add('Nbr_Personnes', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le nombre de personnes pour ce menu.']),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Ce menu est destiné à au moin une personne.',
                        'max' => 100,
                        'maxMessage' => 'Ce menu est destiné à au plus 100 personnes.',

                    ])

                ]
            ])
            ->add('duree_prepare', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer la durée de préparation de ce menu.']),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'La durée de préparation ne doit ne doit pas etre moin$prix_unit d\'une journée.',
                        'max' => 100,
                        'maxMessage' => 'La durée de préparation ne doit pas dépasser 100 jours.',

                    ])

                ]
            ])
            ->add('prix_unit', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le prix pour une personne pour ce menu.']),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le prix unitaire ne doit pas être moin 0 euros.',
                        'max' => 100,
                        'maxMessage' => 'Le prix unitaire ne doit pas dépasser 100 euros.',

                    ])

                ]
            ])
            ->add('temperature_min', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer la température de concervantion minimale pour ce menu.']),
//                    new Length([
//                        'min' => 20,
//                        'minMessage' => 'Le nom doit contenir au moin 8 caractères.',
//                        'max' => 100,
//                        'maxMessage' => 'Le nom ne doit pas contenir plus de 100 caractères.',
//
//                    ])

                ]
            ])
            ->add('temperature_max', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer la température maximale de concervation pour ce menu.']),
//                    new Length([
//                        'min' => 20,
//                        'minMessage' => 'Le nom doit contenir au moin 8 caractères.',
//                        'max' => 100,
//                        'maxMessage' => 'Le nom ne doit pas contenir plus de 100 caractères.',
//
//                    ])

                ]
            ])
            ->add('description_courte', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer une petite description pour ce menu.']),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'Le nom doit contenir au moin 8 caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le nom ne doit pas contenir plus de 100 caractères.',

                    ])

                ]
            ])
            ->add('description_longue', TextType::class)
            ->add('presentation', ChoiceType::class, [
                'mapped' => false,
                'choices' => $options['presentationList'],
                "choice_label" => function ($cats, $ind, $val) {
                    return ucfirst($cats);
                },
            ])
            ->add('ingredients', TextType::class)
            ->add('suggestions', TextType::class)
            ->add('photo', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image',
                    'value' => '',
                ],
//                'label' => 'Image principale',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ])
            ->add('photo1', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image',
                    'value' => '',],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ])
            ->add('photo2', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image',
                    'value' => '',],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ])
            ->add('photo3', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image',
                    'value' => '',],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ])
            ->add('photo4', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image',
                    'value' => '',],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuDetaille::class,
        ]);
        $resolver->setRequired('presentationList');
    }
}
