<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'expanded' => true,
                'required' => true,
                'multiple' => false,

            ])
            ->add('addresses', EntityType::class, [
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'expanded' => true,
                'required' => true,
                'multiple' => false,
                'choice_value' => 'id',

            ])
            ->add('deliveryAddress', HiddenType::class, [
                'data' => $options['user']->getAddresses(),
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
