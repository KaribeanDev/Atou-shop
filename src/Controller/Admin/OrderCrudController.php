<?php

namespace App\Controller\Admin;

use App\Admin\Fields\ProductFields;
use App\Entity\Order;
use App\Entity\OrderDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Commandes')
            ->setEntityLabelInSingular('Commande')
            ->setPageTitle('index', 'Administration des commandes');
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setDisabled(true),
            DateTimeField::new('createdAt', 'Date de commande')->setFormat('Y-m-d H:i:s'),
            AssociationField::new('userOrder', 'Client'),
            TextField::new('deleveryAddress', 'Adresse'),
            IntegerField::new('orderPrice'),
            IntegerField::new('discount'),
            // CollectionField::new('orderDetails', 'Produits commandés'),
            AssociationField::new('orderDetails'),
            AssociationField::new('carrier', 'Transporteur'),
            IntegerField::new('orderQuantity'),
            ChoiceField::new('status', 'Statut')
                ->setChoices([
                    'En cours de préparation' => 'En cours de préparation',
                    'En cours d\'envoie' => 'En cours d\'envoie',
                    'Envoyé' => 'Envoyé'
                ])
                ->allowMultipleChoices(false)


        ];
    }
}
