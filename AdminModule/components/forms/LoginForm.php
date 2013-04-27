<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form,
    Nette\Environment,
    Nette\Security\AuthenticationException;

class LoginForm extends Form {

    public function __construct($parent, $name) {
        parent::__construct($parent, $name);

        $this->addProtection('Prosím, odešlete přihlašovací údaje znova (vypršela platnost bezpečnostního tokenu)');

        $this->addText('email', 'E-mail:')
                ->addRule(Form::FILLED, 'Prosím zadejte Váš email');

        $this->addPassword('password', 'Password:')
                ->addRule(Form::FILLED, 'Prosím zadejte heslo');
        
        //$this->addCheckbox('remember', 'Remember');

        $this->addSubmit('send', 'Log in!');
        $this->onSuccess[] = array($this, 'formSubmited');
    }

    public function formSubmited($form) {
        try {
            $user = $this->getPresenter()->getUser();
            $user->login($form['email']->value, $form['password']->value);

            $this->getPresenter()->getApplication()->restoreRequest($this->getPresenter()->backlink);
            $this->getPresenter()->redirect('Default:default');
        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

}