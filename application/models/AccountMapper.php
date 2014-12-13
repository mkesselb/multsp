<?php
class Application_Model_AccountMapper
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
            $this->setDbTable('Application_Model_DbTable_Account');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Account $account)
    {
        $data = array(
            'name' => $account->getName(),
            'code' => $account->getCode(),   
        );

        $this->getDbTable()->insert($data);
    }

    public function find($id, Application_Model_Account $account)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $account->setId($row->id)
        ->setName($row->name)
        ->setCode($row->code);
    }

    public function update(Application_Model_Account $account){
        $data = array(
            'name' => $account->getName(),
            'code' => $account->getCode(),
        );
        $id = $account->getId();
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }

    public function findByField($field, $value, Application_Model_Account $account){
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
            ->where($field . ' = :value')
            ->bind(array(':value'=>$value)));
        if($row === null){
            return;
        }

        $account->setId($row->id)
        ->setName($row->name)
        ->setCode($row->code);
    }
}