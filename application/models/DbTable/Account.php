<?php
/**
 * Extension of the abstract zend database table for the accounts table.
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_DbTable_Account extends Zend_Db_Table_Abstract
{
	/** Name of the database table. */
    protected $_name = 'accounts';
}