<?php
class Application_Model_UserMapper
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
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_User $user)
    {
        $data = array(
            'email'   => $user->getEmail(),
            'password' => $user->getPassword(),
            'confirmation_code' => $user->getConfirmationcode(),
        );

        $this->getDbTable()->insert($data);
    }

    public function find($id, Application_Model_Guestbook $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                  ->setEmail($row->email)
                  ->setPassword($row->password)
                  ->setConfirmationcode($row->comfirmation_code);
    }
    
    //find for email_address
    //find on confirmation_code
    //  -> 1 method with params
}