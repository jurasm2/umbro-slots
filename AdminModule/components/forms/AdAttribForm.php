<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class AdAttribForm extends BaseForm {

   
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);

        $adModel = $this->modelLoader->loadModel('AdModel');
        
        $loader = new \Nette\Config\Loader();
        $selectData = $loader->load(CONFIG_DIR.'/ad.neon', 'attrib-types');
        
        $this->addSelect('cad_', 'Kateogorie:', $parent->categories)
                ->setPrompt('--- Vyberte kategorii ---')
                ->setDisabled()
                ->setDefaultValue($parent->getParam('cad'))
                ->addRule(Form::FILLED);
                        
        $this->addText('name', 'Jméno atributu:')
                ->addRule(Form::FILLED);
       
        $this->addSelect('type', 'Typ:', $selectData)
                ->setPrompt('--- Vyberte datový typ ---')
                ->addRule(Form::FILLED);
        
        $this->addText('options', 'Hodnoty (odděleny středníkem):');
        
        $this->addText('section', 'Sekce:');
        
      
        $this->addHidden('cad', $parent->getParam('cad'));
        
        
        $this->addSubmit('send', 'Uložit');
           
        
        switch ($this->parent->action) {
            case 'create':
                $this->onSuccess[] = array($this, 'addFormSubmited');
                
                break;
            case 'edit':
                $this->addHidden('attrib_id', $this->parent->getParam('attrib_id'));
                $this->onSuccess[] = array($this, 'editFormSubmited'); 
                // get data from db & set defaults            
                $defaults = $adModel->getAttrib($this->parent->getParam('attrib_id'));  
                
                $this->setDefaults($defaults);
                break;
        }
           
    }

    public function addFormSubmited($form) {       
        
        $adModel = $this->modelLoader->loadModel('AdModel');
        $formValues = $form->getValues();        
        
        $cad = $formValues['cad'];
        
        $result = $adModel->createAttrib($formValues);
                
        switch($result) {
            case 0:
                $this->parent->flashMessage("Záznam se nepovedlo uložit", 'error');
                $this->parent->redirect('default', array('cad' => $cad));
            case 1:
                $this->parent->flashMessage("Záznam uložen");
                $this->parent->redirect('default', array('cad' => $cad));
            default:
                $this->parent->flashMessage($result, 'error');
                $this->parent->redirect('default', array('cad' => $cad));
        }
    }    
    
    public function editFormSubmited($form) {       
       
        $adModel = $this->modelLoader->loadModel('AdModel');
        $formValues = $form->getValues();    
        
        $cad = $formValues['cad'];
        
        $attribId = $formValues['attrib_id'];
        unset($formValues['attrib_id']);
        $result = $adModel->editAttrib($attribId, $formValues);
        
        if ($result) {
            $this->presenter->flashMessage("Atribut upraven");
            $this->presenter->redirect('default', array('cad' => $cad)); 
        } else {
            $this->presenter->flashMessage("Žádné změny nebyly provedeny", 'warning');
            $this->presenter->redirect('default', array('cad' => $cad));
        }
       
       
    }
    
    

}
