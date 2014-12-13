<?php
class AccountDetailController extends Zend_Controller_Action
{
    public function init()
    {
       
    }

    public function indexAction()
    {
        $ns = new Zend_Session_Namespace('myUltimateSession');
        $form = new Application_Form_NewEntry();
        if(isset($ns->id)) {
            $account_id = $_GET['id'];
            $this->view->text = $account_id;
            $account = new Application_Model_Account();
            $mapperA = new Application_Model_AccountMapper();
            $mapperA->findByField('id', $account_id, $account);
            $this->view->account = $account;
            
            $mapper  = new Application_Model_AccountEntryMapper();
            $results = $mapper->findByField('account_id', $account_id);
            $this->view->entries = $results;            
            
            $this->view->form = $form;
        } else {
            // TODO Access denied    
        }
    }
}
