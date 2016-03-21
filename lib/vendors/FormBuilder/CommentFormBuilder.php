<?php
namespace FormBuilder;

use OCFram\EmailValidator;
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Auteur <span style="color: #b82720"> * </span>',
            'name' => 'auteur',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
            ],
        ]))
        ->add(new StringField([
            'label' => 'Email',
            'name' => 'email',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50)],
                new EmailValidator('L\'Email indiquée n\'est pas valide'),
        ]))

            ->add(new TextField([
                'label' => 'Contenu <span style="color: #b82720"> * </span>',
                'name' => 'contenu',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre commentaire'),
                ],
            ]));
    }

    public function buildUser()
    {
        $this->form->add(new TextField([
                'label' => 'Contenu <span style="color: #b82720"> * </span>',
                'name' => 'contenu',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre commentaire'),
                ],
            ]));

    }
}