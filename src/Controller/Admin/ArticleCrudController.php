<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Articles')
            ->setEntityLabelInSingular('Article')
            ->setPageTitle('index', 'Administration des articles du blog');
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('title', 'Titre de l\'article'),
            TextEditorField::new('contents', 'Contenue'),
            ImageField::new('image', 'Image')
                ->setBasePath('assets/images/upload/article/')
                ->setUploadDir('public/assets/images/upload/article/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setFormTypeOption('required', false),
            DateTimeField::new('createdAt', 'Date d\'édition'),


        ];
    }
}
