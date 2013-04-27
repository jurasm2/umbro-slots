<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Services;

use Nette;

abstract class BaseService extends Nette\Object {
    
    private $presenter;
    
    public function isPresenterSet() {
        $this->presenter !== NULL;
    }
    
    public function getPresenter() {
        return $this->presenter;
    }
    
    public function setPresenter($presenter) {
        $this->presenter = $presenter;
    }
    
}
