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
        if(isset($namespace->id)) {
            $user_id = $namespace->id;
            
            $userInAccount = new Application_Model_UserInAccount(null);
            $mapper  = new Application_Model_UserInAccountMapper();
            $results = $mapper->findByField('user_id', $user_id);
            
            $accounts = array();
            foreach($results as $row){
                $account = new Application_Model_Account();
                $mapper  = new Application_Model_AccountMapper();
                $mapper->findByField('id',$row->getAccountId(), $account);
                $account->setConfirmed($row->getConfirmed());
                $accounts[] = $account;
            }
            
            $user = new Application_Model_User();
            $umapper = new Application_Model_UserMapper();
            $umapper->find($user_id, $user);
            
            $this->view->results = $accounts;
            $this->view->user = $user;
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
    }
    
    public function createAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $form    = new Application_Form_Create();
            
            if ($this->getRequest()->isPost()){
                if ($form->isValid($request->getPost())){
                    $account = new Application_Model_Account(null);
                    $mapperA  = new Application_Model_AccountMapper();
                    $account->setName($form->getValue('name'));
                    $code = substr(base64_encode(sha1(mt_rand())), 0, 20);
                    $account->setCode($code);
                    $mapperA->save($account);
            
                    $userInAccount = new Application_Model_UserInAccount();
                    $mapperC = new Application_Model_UserInAccountMapper();
                    $mapperA->findByField('code', $code, $account);
                    $userInAccount->setAccountId($account->getId())
                    ->setUserId($namespace->id)
                    ->setConfirmed(1);
                    $mapperC->save($userInAccount);
                    
                    //also create default category
                    $cat = new Application_Model_Costcategory(null);
                    $cat->setAccountId($account->getId())
                    	->setName('default');
                    $catMapper = new Application_Model_CostcategoryMapper();
                    $catMapper->save($cat);
            
                    return $this->_helper->redirector('index', 'account');
                }
            }
            
            $this->view->form = $form;
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
    }
    
    public function confirminviteAction(){
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $account_id = $_GET['id'];
            $user_id = $namespace->id;
            
            $mapper = new Application_Model_UserInAccountMapper();
            $userInAccount = new Application_Model_UserInAccount();
            $mapper->find($user_id, $account_id, $userInAccount);
            if($userInAccount->getUserId() === $user_id){
                $mapper->update($userInAccount->setConfirmed(1));   
            }
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
        
        return $this->_helper->redirector('index', 'account');
    }
    
    public function deleteaccountAction(){
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $account_id = $_GET['id'];
            $user_id = $namespace->id;
            
            $mapper = new Application_Model_UserInAccountMapper();
            $userInAccount = new Application_Model_UserInAccount();
            $mapper->delete($user_id, $account_id);
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
        
        return $this->_helper->redirector('index', 'account');
    }
}