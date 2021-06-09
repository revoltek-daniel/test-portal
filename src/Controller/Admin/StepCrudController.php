<?php

namespace App\Controller\Admin;

use App\Entity\Step;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StepCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Step::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            ChoiceField::new('type')->setChoices(
                function () {
                    return [
                        'Fragen' => Step::TYPE_QUESTION,
                        'Text' => Step::TYPE_TEXT,
                    ];
                }
            ),
            NumberField::new('sorting'),
            NumberField::new('time')->setLabel('Zeit in Minuten'),
            AssociationField::new('questions')->setFormTypeOptionIfNotSet('by_reference', false),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        $actions->remove(Crud::PAGE_INDEX, 'delete');
        $actions->remove(Crud::PAGE_DETAIL, 'delete');

        return $actions;
    }
}
