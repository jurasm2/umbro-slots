<?php

namespace AdminModule;

use Nette\Security\User;

abstract class SecuredPresenter extends BasePresenter {

    public function startup() {
        parent::startup();

        $user = $this->getUser();

       
        
            if (!$user->isLoggedIn()) {
                if ($user->getLogoutReason() === User::INACTIVITY) {
                    $this->flashMessage('Uplynula doba neaktivity! Systém Vás z bezpečnostních důvodů odhlásil.', 'warning');
                }

                $backlink = $this->getApplication()->storeRequest();
                $this->redirect('Auth:login', array('backlink' => $backlink));
            } else {            
                if (!$user->isAllowed($this->name, $this->action)) {
                    $this->flashMessage('Na vstup do této sekce namáte dostatečné oprávnění!', 'warning');
                    $this->redirect('Default:');
                }
            }

    }

}