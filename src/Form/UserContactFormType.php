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

class UserContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
