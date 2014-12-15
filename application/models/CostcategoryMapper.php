<?php
class Application_Model_CostcategoryMapper
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
            $this->setDbTable('Application_Model_DbTable_Costcategory');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Costcategory $category)
    {
        $data = array(
            'account_id' => $category->getAccountId(),
            'name' => $category->getName(),   
        );

        $this->getDbTable()->insert($data);
    }

    public function find($id, Application_Model_CostCategory $category)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $category->setId($row->id)
        ->setName($row->name)
        ->setAccountId($row->account_id);
    }

    public function update(Application_Model_CostCategory $category){
        $data = array(
            'name' => $category->getName(),
            'account_id' => $category->getAccountId()
        );
        $id = $category->getId();
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }

    public function findByField($field, $value){
        $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()
            ->where($field . ' = :value')
            ->bind(array(':value'=>$value)));
        if($resultSet === null){
            return;
        }
        $categories = array();
        foreach ($resultSet as $row){
          $category = new Application_Model_CostCategory();
          $category->setId($row->id)
          ->setAccountId($row->account_id)
          ->setName($row->name);
          $categories[] = $category;
        }
        return $categories;
    }
}