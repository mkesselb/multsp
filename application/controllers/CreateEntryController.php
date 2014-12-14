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
        $namespace = new Zend_Session_Namespace('myUltimateSession');
       if(isset($namespace->id)){
        $account_id = $_GET['id'];
        $id = $_GET['id'];
        $this->view->test = $account_id;
        
        $mapperC  = new Application_Model_CostcategoryMapper();
        $categories = $mapperC->findByField('account_id', $account_id);
        
        $categorynames = array();
        foreach($categories as $category){
            $categorynames[$category->getId()] = $category->getName();
        }
        
        $namespace->categories = $categorynames;
        
        $form    = new Application_Form_NewEntry();
        
       
       
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                $entry = new Application_Model_AccountEntry(null);
                $mapperE  = new Application_Model_AccountEntryMapper();
                $entry->setCostCategoryId('1');
                //$entry->setCostCategoryId($form->getValue('category')); 
                $entry->setPrice($form->getValue('price'));
                $entry->setComment($form->getValue('comment'));
                $entry->setDate($form->getValue('date'));
                $entry->setUserId($namespace->id);
                $entry->setAccountId('15');
                //$entry->setAccountId($account_id);
                $mapperE->save($entry);
              
                //$params = array('id'=> $accountid);
                //return $this->_helper->redirector('index', 'accountdetail', $params);
                return $this->_redirect('accountdetail/index?id=' . 15);
            } 
        }
        
        $this->view->form = $form;
       $this->view->id = $account_id;
       } else {
            //TODO Access denied   
       }
    }
}