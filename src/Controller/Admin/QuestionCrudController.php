<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Form\AnswerType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            ImageField::new('imageName')
                ->setRequired(false)
                ->setUploadDir('public/images/questions')
                ->setBasePath('/images/questions/'),
            CollectionField::new('answers')
                ->setEntryType(AnswerType::class)
                ->hideOnIndex()
                ->setEntryIsComplex(true),
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
