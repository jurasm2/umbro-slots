<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class ChartForm extends \AdminModule\Components\Forms\BaseForm {

   
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);
        
                        
        $this->addText('offset', 'Offset:');
        
        $selectData = array(
                        '+1 day'    =>  '+1 day',
                        '+2 days'    =>  '+2 days',
                        '+1 week'    =>  '+1 week'
        );
        
        $this->addSelect('limit', 'Limit:', $selectData);
        
//        $userSelectData = array(1 => '1');        
//        $this->addSelect('user_id', 'Uživatel:', $userSelectData);
        
        $defaults['offset'] = $this->presenter->getParam('offset');
        $defaults['limit'] = $this->presenter->getParam('limit');
        
        $this->setDefaults($defaults);
                
        $this->addSubmit('send', 'Zobrazit');
           
        
        $this['offset']->getControlPrototype()->class = 'datepicker';
        
        $this->onSuccess[] = array($this, 'formSubmited'); 
        
       
           
    }

    
    public function formSubmited($form) {       

        $formValues = $form->getValues();        
        
//        dump($formValues);
//        die();
        $this->presenter->redirect('this', array('offset' => $formValues['offset'], 'limit' => $formValues['limit'])); 
//        if ($result) {
//            $this->presenter->redirect('default' $formValues); 
//        } else {
//            $this->presenter->redirect('default');
//        }
       
       
    }
    
    

}
