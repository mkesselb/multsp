<?php
/**
 * Model mapper class which offers methods to save and fetch objects which represent entries in the
 * user_in_account database table.
 *
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_UserInAccountMapper
{
	/** DB table object, of type Zend_Db_Table_Abstract. */
    protected $_dbTable;

    /**
     * Sets the database table of this object to parameter database table.
     * Parameter shall be of type Zend_Db_Table_Abstract.
     * @param Zend_Db_Table_Abstract $dbTable	the database table object
     * @throws Exception
     * @return Application_Model_UserInAccountMapper	this changed object
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
            $this->setDbTable('Application_Model_DbTable_UserInAccount');
        }
        return $this->_dbTable;
    }

    /**
     * Saves the parameter user_in_account object to the database table of this mapper. 
     * @param Application_Model_UserInAccount $userInAccount	the user_in_account relationship to be saved
     */
    public function save(Application_Model_UserInAccount $userInAccount)
    {
        $data = array(
            'user_id' => $userInAccount->getUserId(),
            'account_id' => $userInAccount->getAccountId(),
            'confirmed' => $userInAccount->getConfirmed(),  
        );

        $this->getDbTable()->insert($data);
    }

    /**
     * Finds the relationship object from the table with the key fields user_id and account_id.  
     * @param int $user_id		user_id to be found
     * @param int $account_id	account_id to be found
     * @param Application_Model_UserInAccount $userInAccount	relationship object to be enriched with data
     */
    public function find($user_id, $account_id, Application_Model_UserInAccount $userInAccount)
    {
        $result = $this->getDbTable()->find($user_id, $account_id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $userInAccount->setUserId($row->user_id)
        ->setAccountId($row->account_id)
        ->setConfirmed($row->confirmed);
    }
    
    /**
     * Deletes the relationship between paramter user_id and account_id from the table.
     * @param int $user_id		user_id to be deleted
     * @param int $account_id	account_id to be deleted
     */
    public function delete($user_id, $account_id){
        $result = $this->getDbTable()->delete(
            array('user_id = ?' => $user_id, 'account_id = ?' => $account_id));
    }

    /**
     * Updates the table entry corresponding to the parameter object.
     * @param Application_Model_UserInAccount $userInAccount	relationship object to be saved
     */
    public function update(Application_Model_UserInAccount $userInAccount){
        $data = array(
            'confirmed' => $userInAccount->getConfirmed(),
        );
        $user_id = $userInAccount->getUserId();
        $account_id = $userInAccount->getAccountId();
        $this->getDbTable()->update($data, array('user_id = ?' => $user_id, 'account_id = ?' => $account_id));
    }

    /**
     * Finds the relationship data from the table by a specified field-value matching.
     * @param string $field	field name to be found
     * @param $value		value to be found
     * @return void|multitype:Application_Model_UserInAccount	a list of relationship objects
     */
    public function findByField($field, $value){
        $resultSet = $this->getDbTable()->fetchAll($field . ' = ' . $value);
        if($resultSet === null){
            return;
        }
        $users = array();
        foreach ($resultSet as $row){
          $userInAccount = new Application_Model_UserInAccount();
          $userInAccount->setUserId($row->user_id)
          ->setAccountId($row->account_id)
          ->setConfirmed($row->confirmed);
          $users[] = $userInAccount;
        }
        return $users;
    }
    
    /**
     * Checks whether parameter user_id is allowed to access parameter account_id by assessing
     * the relationship data from corresponding table.
     * @param int $user_id		the user_id
     * @param int $account_id	the account_id
     * @return int	flag whether the authentication was sucessful or not
     */
    public function authUser($user_id, $account_id){
        $userInAccount = new Application_Model_UserInAccount();
        $this->find($user_id, $account_id, $userInAccount);
        return $userInAccount->getConfirmed();
    }
}