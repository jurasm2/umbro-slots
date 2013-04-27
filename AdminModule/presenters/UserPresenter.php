<?php

namespace AdminModule;

use \AdminModule\DataGrids\UserDataGrid,
    \AdminModule\Forms\UserForm,
    \AdminModule\Dialogs\UserConfirmDialog,
        
    \ThumbLoader\ThumbLoader;

final class UserPresenter extends SecuredPresenter {

    /** @persistent */
    public $user_id;
    
    public function createComponentUserDataGrid() {
        return new UserDataGrid($this);        
    }
    
    public function createComponentUserForm($name) {
        return new UserForm($this, $name);
    }

    public function createComponentUserConfirmDialog() {
        return new UserConfirmDialog($this);        
    }
    
    public function createComponentThumbLoader() {
        return new ThumbLoader();
    }
    
    public function renderEdit($id) {
        $this->template->memberId = $id;
    }
    
    public function renderReservations($offset = NULL, $limit = NULL) {
        $mode = array('meas', 'reg', 'bill');
        
        $rangeParams = array(
                        'offset'     =>  $offset ?: 'today',
                        'limit'    =>  $limit ?: '+1 day'
        );
        
        $slots = $this->slotModel->getSlots($mode, $rangeParams, $this->user_id);
        
        $chartData = $this->chartManagerService->getChartData($slots);        
        $this->template->date = strtotime($rangeParams['offset']);
        $this->template->chartData = $chartData;
    }
    
    
    public function renderAccount() {
        
        $userId = $this->user_id;
        
        $balance = $this->accountModel->getCurrentBalance($userId);
        $this->template->balance = $balance ?: 0;
    }
    
    public function beforeRender() {
        parent::beforeRender();
        
        $userId = $this->getParam('user_id');
        
        if (!empty($userId)) {
            
            $member = $this->userModel->getUser($userId);

            if ($member)
                $this->template->member = $member;
        }
            
        
    }
    
}