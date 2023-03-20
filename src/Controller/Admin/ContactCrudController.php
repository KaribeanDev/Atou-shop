<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Contacts')
            ->setEntityLabelInSingular('Contact')
            ->setPageTitle('index', 'Administration des contacts');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('userName', 'Client' ),
            TextField::new('contactEmail', 'Email')
            ->setFormTypeOption('disabled', 'disabled'),
            TextEditorField::new('message', 'Message'),
            DateTimeField::new('createdAt', 'Date d\'édition'),


            // ChoiceField::new('...')
            // ->renderAsBadges([
            //     'traité' => 'success',
            //     'en attente' => 'warning',
            // ])
        ];
    }
    
}
