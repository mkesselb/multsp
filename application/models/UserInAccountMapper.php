<?php
class Application_Model_UserInAccountMapper
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
            $this->setDbTable('Application_Model_DbTable_UserInAccount');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_UserInAccount $userInAccount)
    {
        $data = array(
            'user_id' => $userInAccount->getUserId(),
            'account_id' => $userInAccount->getAccountId(),
            'confirmed' => $userInAccount->getConfirmed(),  
        );

        $this->getDbTable()->insert($data);
    }

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

    public function update(Application_Model_UserInAccount $userInAccount){
        $data = array(
            'confirmed' => $userInAccount->getConfirmed(),
        );
        $user_id = $userInAccount->getUserId();
        $account_id = $userInAccount->getAccountId();
        $this->getDbTable()->update($data, array('user_id = ?' => $user_id, 'account_id = ?' => $account_id));
    }

    public function findByField($field, $value){
        /*$row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
            ->where($field . ' = :value')
            ->bind(array(':value'=>$value)));*/
        $resultSet = $this->getDbTable()->fetchAll();
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
}