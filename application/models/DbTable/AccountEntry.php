<?php
/**
 * Extension of the abstract zend database table for the account entry table.
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_DbTable_AccountEntry extends Zend_Db_Table_Abstract
{
	/** Name of the database table. */
    protected $_name = 'account_entries';
}