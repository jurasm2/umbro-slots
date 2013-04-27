<?php

namespace AdminModule\Components\Dialogs;

class BaseConfirmDialog extends \ConfirmationDialog {
    
   
    /* model */
    public $modelLoader;
    
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);

        $this->modelLoader = $this->parent->context->modelLoader;
        $this->getFormElementPrototype()->addClass('ajax');      
            
    }
    
}