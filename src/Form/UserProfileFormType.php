<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
           $builder
               ->add('email', EmailType::class, [
                       'constraints' => [
                           new NotBlank(['message' => 'Veuillez entrer un Email.']),
//                        new Email(['Veuillez rentrer une adresse valide.'])

                       ]]
               )
               // ->add('roles')
//             ->add('password')
               ->add('pseudo', TextType::class, [
                   'constraints' => [
                       new NotBlank(['message' => 'Veuillez indiquer un pseudo.']),
                       new Regex([
                           'pattern' => '/^[a-z0-9-_]+$/i',
                           'message' => 'Le pseudo ne peut contenir que des caractères alphanumérique.',

                       ]),
                       new Length([
                           'min' => 3,
                           'minMessage' => 'Le pseudo doit contenir au moin 3 caractères.',
                           'max' => 40,
                           'maxMessage' => 'Le pseudo ne doit pas contenir plus de 40 caractères.',

                       ])

                   ]
               ])
               ->add('nom', TextType::class, [
                   'constraints' => [
                       new NotBlank(['message' => 'Veuillez indiquer un pseudo.']),
//                    new Regex([
//                        'pattern' => '/^[a-z]+$/i',
//                        'message' => 'Le pseudo ne peut contenir que des caractères alphanumérique.',
//
//                    ]),
                       new Length([
                           'min' => 3,
                           'minMessage' => 'Le nom doit contenir au moin 3 caractères.',
                           'max' => 40,
                           'maxMessage' => 'Le nom ne doit pas contenir plus de 40 caractères.',

                       ])

                   ]
               ])
               ->add('prenom', TextType::class, [
                   'constraints' => [
                       new NotBlank(['message' => 'Veuillez indiquer un pseudo.']),
//                    new Regex([
//                        'pattern' => '/^[a-z]+$/i',
//                        'message' => 'Le pseudo ne peut contenir que des caractères alphanumérique.',
//
//                    ]),
                       new Length([
                           'min' => 3,
                           'minMessage' => 'Le nom doit contenir au moin 3 caractères.',
                           'max' => 40,
                           'maxMessage' => 'Le nom ne doit pas contenir plus de 40 caractères.',

                       ])

                   ]
               ])
               ->add('telephone', TextType::class, [
                   'constraints' => [
                       new NotBlank(['message' => 'Veuillez indiquer votre numéro de téléphone.']),
                       new Regex([
                           'pattern' => '/^((\+)33|0|0033)[1-9](\d{2}){4}$/',
                           'message' => 'Format incorrect.',

                       ]),
                       new Length([
                           'min' => 10,
                           'minMessage' => 'format de numéro de téléphone incorrect.',
                           'max' => 13,
                           'maxMessage' => 'format de numéro de téléphone incorrect.',

                       ])

                   ]
               ])
               ->add('civilite', ChoiceType::class, [
                   'choices' => [
                       'f' => 'f',
                       'h' => 'h',
                   ],
               ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
