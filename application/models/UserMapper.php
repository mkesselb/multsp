<?php
/**
 * Model mapper class which offers methods to save and fetch user objects
 * from the corresponding database table .
 * 
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_UserMapper
{
	/** DB table object, of type Zend_Db_Table_Abstract. */
    protected $_dbTable;

    /**
     * Sets the database table of this object to parameter database table.
     * Parameter shall be of type Zend_Db_Table_Abstract.
     * @param Zend_Db_Table_Abstract $dbTable	the database table object
     * @throws Exception
     * @return Application_Model_UserMapper	this changed object
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
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    /**
     * Saves the parameter user object to the database table of this mapper.
     * @param Application_Model_User $user	the user to be saved
     */
    public function save(Application_Model_User $user)
    {
        $data = array(
            'email'   => $user->getEmail(),
            'password' => $user->getPassword(),
            'confirmation_code' => $user->getConfirmation_code(),
        );

        $this->getDbTable()->insert($data);
    }

    /**
     * Finds a user in the corresponding table with parameter id.
     * @param int $id	the id to be found
     * @param Application_Model_User $user	the user object to be enriched with db data
     */
    public function find($id, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                  ->setEmail($row->email)
                  ->setPassword($row->password)
                  ->setConfirmation_code($row->confirmation_code);
    }
    
    /**
     * Updates the database table with the parameter user.
     * @param Application_Model_User $user	the updated user to be saved
     */
    public function update(Application_Model_User $user){
        $data = array(
            'email'   => $user->getEmail(),
            'password' => $user->getPassword(),
            'confirmation_code' => $user->getConfirmation_code(),
        );
        $id = $user->getId();
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }

    /**
     * Finds the user data from the table by a specified field-value matching.
     * @param string $field	field name to be found
     * @param $value	value to be found
     * @param Application_Model_User $user	the user to be enriched
     */
    public function findByField($field, $value, Application_Model_User $user){
        $row = $this->getDbTable()->fetchRow(
            $this->getDbTable()->select()
                ->where($field . ' = :value')
                ->bind(array(':value'=>$value)));
        if($row === null){
            return;
        }
        
        $user->setId($row->id)
            ->setEmail($row->email)
            ->setPassword($row->password)
            ->setConfirmation_code($row->confirmation_code);
    }
}