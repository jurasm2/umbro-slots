<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class UserForm extends BaseForm {

   
    public function __construct($parentPresenter, $name) {
        parent::__construct($parentPresenter, $name);
        
        $userModel = $this->modelLoader->loadModel('UserModel');
                        
        $this->addText('name', 'Jméno:')
                ->addRule(Form::FILLED);
        
        $this->addText('email', 'Email:')
                ->addRule(Form::FILLED)
                ->addRule(Form::EMAIL);
        
        $this->addPassword('password', 'Heslo:')
                ->addRule(Form::FILLED)
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 3);
        
        $this->addPassword('password2', 'Heslo znovu:')
                ->addRule(Form::FILLED)
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $this['password']);
        
        $this->addSubmit('send', 'Uložit');
            
        
        switch ($parentPresenter->action) {
            case 'add':
                $this->addHidden('role', 'admin');
                $this->onSuccess[] = array($this, 'addFormSubmited');
                
                break;
            case 'edit':
                $this->addHidden('user_id', $parentPresenter->getParam('user_id'));
                $this->onSuccess[] = array($this, 'editFormSubmited'); 
                // get data from db & set defaults            
                $defaults = $userModel->getUser($parentPresenter->getParam('user_id'));            
                $this->setDefaults($defaults);
                break;
        }
           
    }

    public function addFormSubmited($form) {       
        
        $userModel = $this->modelLoader->loadModel('UserModel');
        
        $formValues = $form->getValues();        
       
        $formValues['password'] = sha1($formValues['password']);
        
        
        //$formValues['nicename'] = \Nette\Utils\Strings::webalize($formValues['name']);
                
        $userId = $userModel->addUser($formValues);
        
        
        $p = $this->parentPresenter;
        
        if ($userId) {
            $p->flashMessage("Záznam uložen");
        } else {
            $p->flashMessage("Chyba", 'error');
        }
        $p->redirect('default'); 
        
    }    
    
    public function editFormSubmited($form) {       
        
        $userModel = $this->modelLoader->loadModel('UserModel');
        
        $formValues = $form->getValues();        
        $formValues['password'] = sha1($formValues['password']);
        unset($formValues['password2']);
        //$formValues['nicename'] = \Nette\Utils\Strings::webalize($formValues['name']);
        $id = $formValues['user_id'];
                
        $result = $userModel->editUser($formValues);
        
        $p = $this->parentPresenter;        
        $chunk = \Nette\Utils\Strings::webalize($formValues['name']);
        $data['url_chunk'] = $userModel->generateUniqueUrlChunk($chunk, $id, 'users', 'user_id');    
       
        $userModel->updateUser($id, $data);
        
        
        switch($result) {
            case 0:
                $p->flashMessage("Žádné změny nebyly provedeny", 'warning');
                $p->redirect('default');
            case 1:
                $p->flashMessage("Uživatel upraven");
                $p->redirect('default'); 
            default:
                $p->flashMessage($result, 'error');
                $p->redirect('this', $id);
        }
    }
    
    

}