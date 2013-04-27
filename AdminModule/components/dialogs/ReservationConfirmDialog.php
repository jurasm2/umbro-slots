<?php

namespace AdminModule\Components\Dialogs;

final class ReservationConfirmDialog extends BaseConfirmDialog {
    
    public function __construct($parent, $name) {
        parent::__construct($parent, $name);    
        $this->buildConfirmDialog();
    }
    
    public function buildConfirmDialog() {
        $this
                ->addConfirmer(
                        'delete', // název signálu bude 'confirmDelete!'
                        array($this, 'deleteItem'), // callback na funkci při kliku na YES
                        'Opravdu smazat?' // otázka (může být i callback vracející string)
                );
                
   
    }
    
    public function deleteItem($id) {
        
        $this->presenter->slotModel->deleteSlot($id);
        $this->presenter->flashMessage("Rezervace smazána");

        $this->presenter->redirect('this');
    }
   
}