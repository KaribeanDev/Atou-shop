<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index', 'Administration des utilisateurs');
    }

    public function createEntity(string $entityFqcn)
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']); // définir le rôle par défaut pour les nouveaux utilisateurs

        return $user;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            TextField::new('pseudo', 'Pseudo'),
            AssociationField::new('addresses', 'Adresses')
                ->hideOnIndex(),
            TextField::new('email', 'Email')
                ->setFormTypeOption('disabled', 'disabled'),
            ArrayField::new('roles')
                ->hideOnIndex(),
                TextField::new('birthdate', 'Date de naissance')
                ->setFormTypeOptions([
                    'required' => true,
                    'constraints' => [
                        new NotNull(),
                        new Regex([
                            'pattern' => "/^\d{2}\/\d{2}\/\d{4}$/",
                            'message' => "Le format de la date doit être jj/mm/aaaa",
                        ]),
                    ],
                ]),
        ];
    }
}
