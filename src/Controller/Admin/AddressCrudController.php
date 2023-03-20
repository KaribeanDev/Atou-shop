<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Adresses')
            ->setEntityLabelInSingular('Adresse')
            ->setPageTitle('index', 'Administration des adresses');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            AssociationField::new('user', 'Utilisateurs'),
            TextField::new('fullname', 'Destinataire'),
            TextField::new('company', 'Entreprise'),
            TextareaField::new('street', 'N° et Voie'),
            IntegerField::new('zipCode', 'Code Postale'),
            TextField::new('city', 'Ville'),
            TextField::new('contry', 'Pays'),
        ];
    }
    
}
