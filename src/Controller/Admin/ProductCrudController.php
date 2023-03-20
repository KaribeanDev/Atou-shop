<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Produits')
            ->setEntityLabelInSingular('Produit')
            ->setPageTitle('index', 'Administration des produits')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('productName', 'Nom du produit'),
            TextareaField::new('productDescription', 'Description')
            ->setFormType(CKEditorType::class),
            MoneyField::new('productPrice', 'Prix')
                ->setCurrency('EUR'),
            ImageField::new('image', 'Image')
                ->setBasePath('assets/images/upload/products/')
                ->setUploadDir('public/assets/images/upload/products/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setFormTypeOption('required', false),
                
            IntegerField::new('quantity', 'Stock'),
            AssociationField::new('category', 'Catégories'),
            IntegerField::new('discount', 'Remise en pourcentage'),


        ];
    }
}
