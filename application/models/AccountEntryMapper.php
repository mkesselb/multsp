<?php
/**
 * Model mapper class which offers methods to save and fetch account entry objects
 * from the corresponding database table.
 *
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_AccountEntryMapper
{
	/** DB table object, of type Zend_Db_Table_Abstract. */
    protected $_dbTable;

    /**
     * Sets the database table of this object to parameter database table.
     * Parameter shall be of type Zend_Db_Table_Abstract.
     * @param Zend_Db_Table_Abstract $dbTable	the database table object
     * @throws Exception
     * @return Application_Model_AccountEntryMapper	this changed object
     */
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

    /**
     * Returns the database table object.
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_AccountEntry');
        }
        return $this->_dbTable;
    }

    /**
     * Saves parameter account_entry object to the database table of this mapper.
     * @param Application_Model_AccountEntry $accountentry	account_entry to be saved
     */
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

    /**
     * Finds an account entry in the corresponding with parameter id. 
     * @param int $id	the id to be found
     * @param Application_Model_AccountEntry $accountentry	the account entry to be enriched
     */
    public function find($id, Application_Model_AccountEntry $accountentry)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        
        $cat = new Application_Model_CostCategory();
        $cat->setId($row->cost_category_id);
        
        $user = new Application_Model_User();
        $user->setId($row->user_id);
        
        $accountentry->setId($row->id)
        ->setAccountId($row->account_id)
        ->setCostCategory($cat)
        ->setUser($user)
        ->setDate($row->date)
        ->setPrice($row->price)
        ->setComment($row->comment);
    }

    /**
     * Updates the database table with parameter account entry.
     * @param Application_Model_AccountEntry $accountentry	the account entry to be saved
     */
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

    /**
     * Finds the account entry data from the table by a specified field-value matching.
     * @param string $field	field name to be found
     * @param $value	value to be found
     * @return void|multitype:Application_Model_AccountEntry	the list of account entries
     */
    public function findByField($field, $value){
        $resultSet = $this->getDbTable()->fetchAll($field . ' = ' . $value);
        if($resultSet === null){
            return;
        }
        $accountentries = array();
        foreach ($resultSet as $row){
        	$cat = new Application_Model_CostCategory();
        	$cat->setId($row->cost_category_id);
        	
        	$user = new Application_Model_User();
        	$user->setId($row->user_id);
        	
            $accountentry = new Application_Model_AccountEntry();
            $accountentry->setId($row->id)
            ->setAccountId($row->account_id)
            ->setCostCategory($cat)
            ->setUser($user)
            ->setDate($row->date)
            ->setPrice($row->price)
            ->setComment($row->comment);
            $accountentries[] = $accountentry;
        }
        return $accountentries;
    }
}