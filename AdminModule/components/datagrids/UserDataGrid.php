<?php

namespace AdminModule\Components\DataGrids;

final class UserDataGrid extends BaseDataGrid {
    
    public function __construct($parentPresenter) {
        parent::__construct($parentPresenter);
        // Create a query
        $ds = $this->connection->dataSource("SELECT 
                                                *                                           
                                             FROM [reg2_users] u");        
        // Create a data source
        $dataSource = new \DataGrid\DataSources\Dibi\DataSource($ds);

        // Configure data grid
        
        $this->setDataSource($dataSource);

        // Configure columns
        $this->addNumericColumn('user_id', 'ID')->addFilter();
        $this->addColumn('name', 'Jméno')->addFilter();
        $this->addColumn('email', 'E-mail')->addFilter();

        
        $this->keyName = 'user_id';
        $this->addActionColumn('Actions');

        
        $icon = \Nette\Utils\Html::el('span');
        //$this->addAction('New entry', 'User:new', clone $icon->class('icon icon-add'), FALSE, \DataGrid\Action::WITHOUT_KEY);
        
        $this->addAction('Edit', 'User:edit', clone $icon->class('icon icon-edit')->setText('Osobní údaje'));
        //$this->addAction('Delete', 'userConfirmDialog:confirmDelete!', clone $icon->class('icon icon-del')->settext('Smazat'), TRUE);
                
        
        
    }
    



}