<?php
class CreateEntryController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $ns = new Zend_Session_Namespace('myUltimateSession');
        $form    = new Application_Form_NewEntry();
        
        $account_id = $_GET['id'];
       
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                $entry = new Application_Model_AccountEntry(null);
                $mapperE  = new Application_Model_AccountEntryMapper();
                $entry->setCostCategoryId('1');
                //$entry->setCostCategoryId($form->getValue('category'));
                $entry->setPrice($form->getValue('price'));
                $entry->setComment($form->getValue('comment'));
                $entry->setDate($form->getValue('date'));
                $entry->setUserId($ns->id);
                $entry->setAccountId('1');
                //$entry->setAccountId($account_id);
                $mapperE->save($entry);
              
                //$params = array('id'=> $accountid);
                //return $this->_helper->redirector('index', 'accountdetail', $params);
                return $this->_redirect('accountdetail/index?id=1');
            } 
        }
        
        $this->view->form = $form;
       $this->view->id = $account_id;
    }
}