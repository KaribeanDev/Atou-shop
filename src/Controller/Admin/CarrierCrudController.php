<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Transporteurs')
            ->setEntityLabelInSingular('Transporteur')
            ->setPageTitle('index', 'Administration des transporteurs');
    }


    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('carrierName', 'Nom'),
            TextareaField::new('carrierDescript', 'Description'),
            MoneyField::new('carrierPrice', 'Prix')
            ->setCurrency('EUR'),
            ImageField::new('carrierImage', 'Image')
            ->setBasePath('assets/images/upload/carrier/')
            ->setUploadDir('public/assets/images/upload/carrier/')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),


        ];
    }
    
}
