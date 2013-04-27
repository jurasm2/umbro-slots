<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class BalanceForm extends \AdminModule\Components\Forms\BaseForm {

   
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);
        
                        
        $this->addText('subject', 'Předmět:');
        $this->addText('amount', 'Částka:');
        $this->addSubmit('send', 'Odeslat');
        
        $this->onSuccess[] = array($this, 'formSubmited'); 
           
    }

    
    public function formSubmited($form) {       

        $formValues = $form->getValues();        
        $formValues['user_id'] = $this->presenter->getParam('user_id');
       
        $this->presenter->accountModel->changeBalance($formValues);
        
        $this->presenter->redirect('account');
        
    }
    
    

}
