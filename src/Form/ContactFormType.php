<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactFormType extends AbstractType
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

            ->add('nom', TextType::class, [
                'constraints' => [

                    new Length([
                        'min' => 0,
                        'minMessage' => 'Le nom doit contenir au moin 3 caractères.',
                        'max' => 40,
                        'maxMessage' => 'Le nom ne doit pas contenir plus de 40 caractères.',

                    ])

                ]
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
//
                    new Length([
                        'min' => 0,
                        'minMessage' => 'Le nom doit contenir au moin 3 caractères.',
                        'max' => 40,
                        'maxMessage' => 'Le nom ne doit pas contenir plus de 40 caractères.',

                    ])

                ]
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^((\+)33|0|0033)[1-9](\d{2}){4}$/',
                        'message' => 'Format incorrect.',

                    ]),
                    new Length([
                        'min' => 0,
                        'minMessage' => 'format de numéro de téléphone incorrect.',
                        'max' => 10,
                        'maxMessage' => 'Le pseudo doit contenir plus de 40 caractères.',

                    ])

                ]
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'f' => 'f',
                    'h' => 'h',
                ],
            ])
            ->add('objet', ChoiceType::class, [
                'choices' => [
                    'devis' => 'Demande de devis',
                    'information' => 'Demande d\'information',
                    'reclamation' => 'réclamation',
                    'Autre' => 'Autre',
                ],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un message.']),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'votre message doit contenir au moin 20 caractère.',
                        'max' => 500,
                        'maxMessage' => 'votre message ne doit pas dépasser les 500 caractères.',

                    ])

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
