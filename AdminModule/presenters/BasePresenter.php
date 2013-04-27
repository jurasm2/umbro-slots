<?php

namespace AdminModule;

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
            $classname = "AdminModule\\Components\\Forms\\" . ucfirst($name);
            if (class_exists($classname)) {
                $form = new $classname($this, $name);
                //$form->setTranslator($this->context->translator);
                return $form;
            }
        } else if (preg_match('([a-zA-Z0-9]+DataGrid)', $name)) {
            // detect datagrids
            $classname = "AdminModule\\Components\\DataGrids\\" . ucfirst($name);
            if (class_exists($classname)) {
                $datagrid = new $classname($this, $name);
                //$datagrid->setTranslator($this->context->translator);
                return $datagrid;
            }
        } else if (preg_match('([a-zA-Z0-9]+ConfirmDialog)', $name)) {
            // detect confrim dialogs
            $classname = "AdminModule\\Components\\Dialogs\\" . ucfirst($name);
            if (class_exists($classname)) {
                $dialog = new $classname($this, $name);
                //$dialog->setTranslator($this->context->translator);
                return $dialog;
            }
        } else {
            return parent::createComponent($name);
        }
    }
    
    /** --- */
    public function createComponentDataGridHead() {
        $dgr = new \DataGrid\Head();

        
        // API access
        $dgr->tempPath = WWW_DIR . '/mfu';
        
        // Browser access
        $baseUrl = rtrim($this->getHttpRequest()->getUrl()->getBaseUrl(), '/');
        $baseUri = preg_replace('#https?://[^/]+#A', '', $baseUrl);
        
        $dgr->tempUri = $baseUri . '/mfu';
        

        return $dgr;        
    }

    public function createComponentNavigation($name) {
        $navigation = new \Navigation\Navigation($this, $name);

        // menu definition

        $identity = $this->getUser()->getIdentity();
        
        $navigation->add("Rezervace", $this->link("Default:default"));
        //$navigation->add("Uživatelé", $this->link("User:default"));
        
        if ($identity && $identity->roles[0] == 'admin') {
            $navigation->add("Uživatelé", $this->link("User:default"), 'admin');
            //$navigation->add("Nastavení", $this->link("Setting:default"), 'admin');
        }
    }


    public function afterRender() {
        if ($this->isAjax() && $this->hasFlashSession())
            $this->invalidateControl('flashes');
    }

}