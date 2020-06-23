<?php

namespace App\Form;

use App\Entity\Menu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MenuFormType extends AbstractType
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
           ->add('description_courte', TextType::class
        // , [
//                'constraints' => [
//                    new NotBlank(['message' => 'Veuillez indiquer une petite description pour ce menu.']),
//                    new Length([
//                        'min' => 20,
//                        'minMessage' => 'Le nom doit contenir au moin 8 caractères.',
//                        'max' => 100,
//                        'maxMessage' => 'Le nom ne doit pas contenir plus de 100 caractères.',
//
//                    ])
//
//                ]
//            ]
    )
            ->add('description_longue')
            ->add('photo', FileType::class, [
                'attr' => ['placeholder' => 'Choisir une image'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ]),
//                    new NotBlank(['message' => 'Veuillez inserer une photo pour ce menu.']),
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'csrf_protection' => false,
        ]);
    }
}
