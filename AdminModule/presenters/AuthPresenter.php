<?php

namespace AdminModule;

use AdminModule\Forms;

final class AuthPresenter extends BasePresenter {

    /** @persistent */
    public $backlink = '';

    protected function createComponentLoginForm($name) {
        $form = new Forms\LoginForm($this, $name);
    }
    
    protected function createComponentForgotForm($name) {
        $form = new Forms\ForgotForm($this, $name);
    }

}