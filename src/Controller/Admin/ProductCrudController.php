<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

      public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom')->setHelp("Nom Produit"),
            SlugField::new('slug')->setLabel('url')->setTargetFieldName('name')->setHelp("URL du Produit"),
            TextareaField::new('description')->setLabel('Description')->setHelp("Saisir une description"),
            ImageField::new('Image')->setLabel('Image')->setHelp('Image produit 600x-600px')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]') ->setBasePath('/uploads')->setUploadDir('/public/uploads'),
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Prix H.T'),
            ChoiceField::new('tva')->setLabel('tva')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20',
            ]),
            AssociationField::new('category','Categorie')

        ];
    }
    
}
