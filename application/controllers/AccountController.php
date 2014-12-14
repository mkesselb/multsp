<?php

class AccountController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        $user_id = $namespace->id;
        
        $userInAccount = new Application_Model_UserInAccount(null);
        $mapper  = new Application_Model_UserInAccountMapper();
        $results = $mapper->findByField('user_id', $user_id);
        
       $accounts = array();
        foreach($results as $row){
            $account = new Application_Model_Account();
            $mapper  = new Application_Model_AccountMapper();
            $mapper->findByField('id',$row->getAccountId(), $account);
            $accounts[] = $account;
        }
       
      
        $this->view->results = $accounts;
    }
}