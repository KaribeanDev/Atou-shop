<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'mapped' => true,
                'label' => 'Pseudo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un pseudo',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom de famille',
                    ]),
                ],
            ])
            ->add('birthdate', TextType::class, [
                'label' => 'Date de naissance',
                'attr' => [
                    'placeholder' => 'jj/mm/aaaa',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une date de naissance',
                    ]),
                    new Callback([
                        'callback' => function ($birthdate, ExecutionContextInterface $context) {
                            $date = \DateTime::createFromFormat('d/m/Y', $birthdate);
                            if ($date === false || !checkdate($date->format('m'), $date->format('d'), $date->format('Y'))) {
                                $context->buildViolation('La date entrée n\'est pas valide')
                                    ->addViolation();
                            }
                            $now = new \DateTime();
                            $eighteenYearsAgo = $now->modify('-18 years');
                            if ($date >= $eighteenYearsAgo) {
                                $context->buildViolation('Vous devez avoir au moins 18 ans pour vous inscrire')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email valide',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
