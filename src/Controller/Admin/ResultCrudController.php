<?php

namespace App\Controller\Admin;

use App\Entity\Result;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ResultCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Result::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('user')->onlyOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        $actions->remove(Crud::PAGE_INDEX, 'edit');
        $actions->remove(Crud::PAGE_INDEX, 'new');

        $detailAction = Action::new('detail')->linkToRoute(
            'admin_results',
            function (Result $entity) {
                return ['id' => $entity->getId()];
            }
        );
        $actions->add(Crud::PAGE_INDEX, $detailAction);

        return $actions;
    }
}
