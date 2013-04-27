<?php

namespace FrontModule;

use \Nette;

abstract class BasePresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        
    }

    /**
     * Factory method for all
     * - forms
     * - datagrids
     * - confirmdialogs
     *
     * @param type $name
     * @return classname 
     */
    public function createComponent($name) {
        if (preg_match('([a-zA-Z0-9]+Form)', $name)) {
            
            // detect forms            
            $classname = "FrontModule\\Components\\Forms\\" . ucfirst($name);
            if (class_exists($classname)) {
                $form = new $classname($this, $name);
                //$form->setTranslator($this->context->translator);
                return $form;
            }
        } else if (preg_match('([a-zA-Z0-9]+DataGrid)', $name)) {
            // detect datagrids
            $classname = "FrontModule\\Components\\DataGrids\\" . ucfirst($name);
            if (class_exists($classname)) {
                $datagrid = new $classname($this, $name);
                //$datagrid->setTranslator($this->context->translator);
                return $datagrid;
            }
        } else if (preg_match('([a-zA-Z0-9]+ConfirmDialog)', $name)) {
            // detect confrim dialogs
            $classname = "FrontModule\\Components\\Dialogs\\" . ucfirst($name);
            if (class_exists($classname)) {
                $dialog = new $classname($this, $name);
                //$dialog->setTranslator($this->context->translator);
                return $dialog;
            }
        } else {
            return parent::createComponent($name);
        }
    }
    



    public function afterRender() {
        if ($this->isAjax() && $this->hasFlashSession())
            $this->invalidateControl('flashes');
    }

}