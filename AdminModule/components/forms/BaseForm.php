<?php

namespace AdminModule\Components\Forms;

use Nette\Application\UI\Form;

class BaseForm extends Form {
    
    /* owner presenter */ 
    public $parentPresenter;
    
    /* model loader */
    public $modelLoader;
    
    /* */
    public $models = array();
    
    public $thumbSizeRestrictions = array(5, 1000);
    public $photoSizeRestrictions = array(5, 3000);

    public function __construct($parentPresenter, $name) {
        parent::__construct($parentPresenter, $name);

        $this->parentPresenter = $parentPresenter;
        
        // load model loader
        $this->modelLoader = $parentPresenter->context->modelLoader;
        
        // turn off HTMl5 validation
        $this->getElementPrototype()->setNovalidate('novalidate');
        
        $this->getElementPrototype()->addAttributes(array('class' => 'custom-form'));
    }
    
    public function enhancedDate($format, $dateTimeValue) {
        $dateTime = new \DateTime($dateTimeValue);
        return $dateTime->format($format);
    }
    
    /**
     * CUSTOM FORM VALIDATORS
     */
    
    public function uniquenessValidator($item, $arg) {  
        
        $c = $arg['connection']->fetchSingle('SELECT COUNT(*) FROM [media_containers] WHERE password = %s %if AND id != %i', $item->value, isset($arg['id']),(isset($arg['id']) ? $arg['id'] : 0));

        return ($c == 0);
    }

  

}