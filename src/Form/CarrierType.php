<?php

namespace App\Form;

use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carrierName')
            ->add('carrierDescript')
            ->add('carrierImage')
            ->add('carrierPrice')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carrier::class,
        ]);
    }
}
