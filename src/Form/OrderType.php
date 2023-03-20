<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderPrice')
            ->add('discount')
            ->add('fullPrice')
            ->add('createdAt')
            ->add('userOrder')
            ->add('productOrder')
            ->add('carrier')
            ->add('addresses')
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choice' => [
                    'En cours de préparation' => 'En cours de préparation',
                    'En cours d\'envoie' => 'En cours d\'envoie',
                    'Envoyé' => 'Envoyé'
                ],
                'required' => false,
                'placeholder' => 'Sélectionné le statut'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
