<?php

/**
 * Account controller includes all actions that are concerned with the management of one's accounts.
 * The following actions are supported:
 * 	index action
 * 	create action
 * 	confirminvite action
 * 	deleteaccount action
 * 
 * @author mkesselb, comoessl, polschan
 */
class AccountController extends Zend_Controller_Action
{
	/**
	 * Empty init function, creatd by template.
	 * @see Zend_Controller_Action::init()
	 */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Index action takes the user_id from the session to query the database for all
     * accounts the user is in relationship with.
     * Those accounts are given to the view in order to show all accounts.
     */
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
    
    /**
     * Create action shows the CreteAccount form on get.
     * On post, the account with the entered name is written into the database,
     * and the current user is entered in the user_in_account relationship.
     */
    public function createAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
			$form    = new Application_Form_Create();
			$user = new Application_Model_User();
					$umapper = new Application_Model_UserMapper();
					$umapper->find($namespace->id, $user);
					$this->view->user = $user;
            
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
    
    /**
     * ConfirmInvite actions takes the account_id of the account to be invited from get-parameter
     * and the user_id from session in order to write the confirmed-flag in the user_in_account
     * relationship on database.
     * Then, it is redirected to the index action of this controller.
     */
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
    
    /**
     * DeleteAccount action takes the account_id that shall be deleted as get-parameter,
     * and deletes the current user_id (from session) from the user_in_account relationship.
     * Then, it is redirected to the index action of this controller.
     */
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