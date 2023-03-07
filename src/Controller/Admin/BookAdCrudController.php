<?php

namespace App\Controller\Admin;

use App\Entity\BookAd;
use App\Form\CategoryType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookAdCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BookAd::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('title');
        yield TextEditorField::new('description');
        yield Field::new('updatedAt')->onlyOnIndex();
        yield Field::new('createdAt')->onlyOnIndex();
        yield AssociationField::new('category');
        yield CollectionField::new('imageFile')
            ->setEntryType(VichImageType::class);
        yield AssociationField::new('user');
    }
}
