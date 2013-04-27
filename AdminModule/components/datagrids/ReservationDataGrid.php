<?php

namespace AdminModule\Components\DataGrids;

class ReservationDataGrid extends \AdminModule\Components\DataGrids\BaseDataGrid {
    
    public function __construct($parentPresenter) {
        parent::__construct($parentPresenter);
        
        
        
        $ds = $this->connection->dataSource("SELECT
                                                  *
                                                FROM
                                                  [reg2_slots]
                                                ORDER BY
                                                    [start_time]
                                                DESC");
        
        

        
        
        // Create a data source
        $dataSource = new \DataGrid\DataSources\Dibi\DataSource($ds);

        // Configure data grid
        $this->setDataSource($dataSource);

        // Configure columns
        $this->addDateColumn('start_time', 'Termín', '%d.%m.%Y v %H:%M');
        $this->addColumn('name', 'Jméno');
        $this->addColumn('email', 'Jméno');
        $this->addColumn('phone', 'Telefon');
        $this->addColumn('club', 'Jméno klubu');
        
//        $el = \Nette\Utils\Html::el('span')->style('margin: 0 auto');
//        $this['status']->replacement['missing_in'] = clone $el->class("icon icon-bullet-arrow-top")->title("Missing in");
//        $this['status']->replacement['missing_out'] = clone $el->class("icon icon-bullet-arrow-bottom")->title("Missing out");
//        $this['status']->replacement['open'] = clone $el->class("icon icon-bullet-add")->title("Open");
//        $this['status']->replacement['close'] = clone $el->class("icon icon-bullet-delete")->title("Close");
//        $this['status']->replacement['ignore'] = clone $el->class("icon icon-bullet-error")->title("Ignore");
        
        $this->keyName = 'slot_id';
        $this->addActionColumn('Actions');

        // výchozí filtrování
        //$this['is_leaf']->addDefaultFiltering('1'); // výchozí filtrování
        
        $icon = \Nette\Utils\Html::el('span');
        //$grid->addAction('New entry', 'Sport:add', clone $icon->class('icon icon-add'), FALSE, \DataGrid\Action::WITHOUT_KEY);
        //$this->addAction('Edit', 'Artist:editArtist', clone $icon->class('icon icon-edit')->setText('Upravit údaje'));
        $this->addAction('Delete', 'reservationConfirmDialog:confirmDelete!', clone $icon->class('icon icon-del')->setText('Smazat rezervaci'), TRUE);      
        
        
//        // nadefinujeme si operace, tyto hodnoty je možno nechat překládat translatorem
//        $operations = array(
//                        'activate'  => 'Aktivovat', 
//                        'delete'    => 'Smazat' 
//                        );
//
//        // poté callback(y), které mají operaci zpracovat
//        $callback = array($this, 'gridOperationHandler'); // $this je presenter
//
//        // povolíme operace
//        $this->allowOperations($operations, $callback);
//        // pozn: pokud je již uveden $grid->keyName není třeba poslední parametr udávat
        
    
    }
    
    public function gridOperationHandler(\Nette\Forms\Controls\SubmitButton $button) {
        $form = $button->getParent();
        
        $adModel = $form->presenter->context->modelLoader->loadModel('AdModel');
        
        $values = $form->getValues();

        // ... provedeme zpracování operace
        // název operace získáme z $values['operations']
        // a zda-li byl checkbox zaškrtnut zjistíme přes $values['checker'][123] => bool(TRUE)

        switch ($values['operations']) {
            
            case 'activate':  
                $adModel->batchActivateAds($values['checker']);
                $this->flashMessage("Inzeráty byly aktivovány");
                break;
            case 'delete':
                $adModel->batchDeleteAds($values['checker']);
                $this->flashMessage("Inzeráty byly smazány");
                break;
            
        }
        
        
        $this->invalidateControl();
    }
    
}
