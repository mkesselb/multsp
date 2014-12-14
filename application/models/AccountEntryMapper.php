<?php
class Application_Model_AccountEntryMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_AccountEntry');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_AccountEntry $accountentry)
    {
        $data = array(
            'account_id' => $accountentry->getAccountId(),
            'cost_category_id' => $accountentry->getCostCategoryId(),
            'user_id' => $accountentry->getUserId(),
            'date' => $accountentry->getDate(),
            'price' => $accountentry->getPrice(),
            'comment' => $accountentry->getComment(),          
        );

        $this->getDbTable()->insert($data);
    }

    public function find($id, Application_Model_AccountEntry $accountentry)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $accountentry->setId($row->id)
        ->setAccountId($row->account_id)
        ->setCostCategoryId($row->cost_category_id)
        ->setUserId($row->user_id)
        ->setDate($row->date)
        ->setPrice($row->price)
        ->setComment($row->comment);
    }

    public function update(Application_Model_AccountEntry $accountentry){
        $data = array(
            'account_id' => $accountentry->getAccountId(),
            'cost_category_id' => $accountentry->getCostCategoryId(),
            'user_id' => $accountentry->getUserId(),
            'date' => $accountentry->getDate(),
            'price' => $accountentry->getPrice(),
            'comment' => $accountentry->getComment(),  
        );
        $id = $accountentry->getId();
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }

    public function findByField($field, $value){
        $resultSet = $this->getDbTable()->fetchAll($field . ' = ' . $value);
        if($resultSet === null){
            return;
        }
        $accountentries = array();
        foreach ($resultSet as $row){
            $accountentry = new Application_Model_AccountEntry();
            $accountentry->setId($row->id)
            ->setAccountId($row->account_id)
            ->setCostCategoryId($row->cost_category_id)
            ->setUserId($row->user_id)
            ->setDate($row->date)
            ->setPrice($row->price)
            ->setComment($row->comment);
            $accountentries[] = $accountentry;
        }
        return $accountentries;
    }
}