<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('email');

        yield TextField::new('password')
            ->setFormType(PasswordType::class)
            ->onlyOnForms();
        
        yield ChoiceField::new('roles')
            ->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Moderator' => 'ROLE_MODERATOR'
            ])
            ->allowMultipleChoices()
            ->renderExpanded();
    }

}
