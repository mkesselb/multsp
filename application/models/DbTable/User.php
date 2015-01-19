<?php
/**
 * Extension of the abstract zend database table for the user table.
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{
	/** Name of the database table. */
    protected $_name = 'users';
}