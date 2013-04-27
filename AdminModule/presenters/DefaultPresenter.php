<?php

namespace AdminModule;


final class DefaultPresenter extends BasePresenter {


    public function startup() {
        parent::startup();

        $user = $this->getUser();

        if (!$user->isLoggedIn()) {
            if ($user->getLogoutReason() === \Nette\Security\User::INACTIVITY) {
                $this->flashMessage('Uplynula doba neaktivity! Systém vás z bezpečnostních důvodů odhlásil.', 'warning');
            }

            $backlink = $this->getApplication()->storeRequest();
            $this->redirect('Auth:login', array('backlink' => $backlink));
        } else {
            if (!$user->isAllowed($this->name, $this->action)) {
                $this->flashMessage('Na vstup do této sekce nemáte oprávnění!', 'warning');
                $this->redirect('Auth:login');
            }
        }
    }
    
    public function renderDefault() {
        
        //$user = $this->context->modelLoader->loadModel('UserModel');
        
    }

    
    public function actionLogout() {
        $this->getUser()->logOut();
        $this->flashMessage('Práve jste se odhlásili z administrace.');
        $this->redirect('Auth:login');
    }


}