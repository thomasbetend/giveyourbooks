<?php

namespace App\Controller\Admin;

use App\Entity\BookAd;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookAdCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BookAd::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
