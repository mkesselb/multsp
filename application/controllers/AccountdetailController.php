<?php
class AccountDetailController extends Zend_Controller_Action
{
    public function init()
    {
       
    }

    public function indexAction()
    {
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        $form = new Application_Form_NewEntry();
        if(isset($namespace->id)) {
            //TODO: also check if user_id in session is allowed to access the account
            $account_id = $_GET['id'];
            $namespace->account_id = $account_id;
            
            $account = new Application_Model_Account();
            $mapperA = new Application_Model_AccountMapper();
            $mapperA->findByField('id', $account_id, $account);
            $this->view->account = $account;
            
            $mapper  = new Application_Model_AccountEntryMapper();
            $results = $mapper->findByField('account_id', $account_id);
            $mapperC = new Application_Model_CostcategoryMapper();
            foreach ($results as $result){
                $cat = new Application_Model_CostCategory();
                $mapperC->find($result->getCostCategoryId(), $cat);
                $result->setCostCategoryName($cat->getName());
            }
            
            $this->view->entries = $results;            
            
            $this->view->form = $form;
        } else {
            // TODO Access denied    
        }
    }
    
    public function createentryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            //TODO: also check if user_id in session is allowed to access the account
            $account_id = $namespace->account_id;
            
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
                    $entry->setCostCategoryId($form->getValue('category'));
                    $entry->setPrice($form->getValue('price'));
                    $entry->setComment($form->getValue('comment'));
                    $entry->setDate($form->getValue('date'));
                    $entry->setUserId($namespace->id);
                    $entry->setAccountId($account_id);
                    $mapperE->save($entry);
                  
                    //$params = array('id'=> $accountid);
                    //return $this->_helper->redirector('index', 'accountdetail', $params);
                    return $this->_redirect('accountdetail/index?id=' . $account_id);
                } 
            }
            
           $this->view->form = $form;
           $this->view->id = $account_id;
       } else {
            //TODO Access denied   
       }
    }
    
    public function createcategoryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            //TODO: also check if user_id in session is allowed to access the account
            $account_id = $namespace->account_id;
        
            $form = new Application_Form_Category();
        
            if ($this->getRequest()->isPost()){
                if ($form->isValid($request->getPost())){
                    $cat = new Application_Model_CostCategory(null);
                    $cat->setName($form->getValue('name'));
                    $cat->setAccountId($account_id);
                    $mapperC = new Application_Model_CostcategoryMapper();
                    $mapperC->save($cat);
                    return $this->_redirect('accountdetail/index?id=' . $account_id);
                }
            }
        
            $this->view->form = $form;
            $this->view->id = $account_id;
        } else {
            //TODO Access denied
        }
    }
}
