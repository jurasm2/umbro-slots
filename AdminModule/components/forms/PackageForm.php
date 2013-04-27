<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class PackageForm extends \AdminModule\Components\Forms\BaseForm {

   
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);
        
        // try to load external configuration file
        $loader = new \Nette\Config\Loader();
        $selectData = $loader->load(CONFIG_DIR.'/package.neon', 'selectdata');

        $packageModel = $this->modelLoader->loadModel('PackageModel');
                        
        $this->addText('name', 'Jméno balíčku:')
                ->addRule(Form::FILLED);
        
        $this->addText('max_ads', 'Počet inzerátů [ks]:')
                ->addRule(Form::FILLED)
                ->addRule(Form::INTEGER);
        
        $this->addText('ad_validity', 'Platnost inzerátu [dny]:')
                ->addRule(Form::FILLED)
                ->addRule(Form::INTEGER);
        
        $this->addSelect('reload_period', 'Perioda:', $selectData['reload_period'])
                ->setPrompt('--- Vyberte periodu ---')
                ->addRule(Form::FILLED);
        
        $this->addText('price', 'Cena [CZK]:')
                ->addRule(Form::FILLED)
                ->addRule(Form::INTEGER);
        
        $this->addSelect('package_validity', 'Platnost balíčku:', $selectData['package_validity'])
                ->setPrompt('--- Zvolte platnost balíčku ---')
                ->addRule(Form::FILLED);
        
        $this->addText('available_from', 'Balíček dostupný od:')
                ->addRule(Form::FILLED);
        
        $this->addText('available_to', 'Balíček dostupný do:');
                
        $this->addSubmit('send', 'Uložit');
           
        
        $this['available_from']->getControlPrototype()->class = 'j_date_from';
        $this['available_to']->getControlPrototype()->class = 'j_date_to';
        
        switch ($this->parent->action) {
            case 'create':
                $this->onSuccess[] = array($this, 'addFormSubmited');
                
                break;
            case 'edit':
                $this->addHidden('package_id', $this->parent->getParam('package_id'));
                $this->onSuccess[] = array($this, 'editFormSubmited'); 
                // get data from db & set defaults            
                $defaults = $packageModel->getPackage($this->parent->getParam('package_id'));  
                if (!empty($defaults)) {
                    $defaults['available_from'] = date('d.m.Y', strtotime($defaults['available_from']));
                    $defaults['available_to'] = $defaults['available_to'] == '0000-00-00' ? '' : date('d.m.Y', strtotime($defaults['available_to']));
                    $this->setDefaults($defaults);
                }
                break;
        }
           
    }

    public function addFormSubmited($form) {       
        
        $packageModel = $this->modelLoader->loadModel('PackageModel');
        $formValues = $form->getValues();        
        
        $result = $packageModel->createPackage($formValues);
                
        switch($result) {
            case 0:
                $this->parent->flashMessage("Záznam se nepovedlo uložit", 'error');
                $this->parent->redirect('default');
            case 1:
                $this->parent->flashMessage("Záznam uložen");
                $this->parent->redirect('default'); 
            default:
                $this->parent->flashMessage($result, 'error');
                $this->parent->redirect('default');   
        }
    }    
    
    public function editFormSubmited($form) {       
       
        $packageModel = $this->modelLoader->loadModel('PackageModel');
        $formValues = $form->getValues();        
        
        $packageId = $formValues['package_id'];
        unset($formValues['package_id']);
        $result = $packageModel->editPackage($packageId, $formValues);
        
        if ($result) {
            $this->presenter->flashMessage("Balíček upraven");
            $this->presenter->redirect('default'); 
        } else {
            $this->presenter->flashMessage("Žádné změny nebyly provedeny", 'warning');
            $this->presenter->redirect('default');
        }
       
       
    }
    
    

}
