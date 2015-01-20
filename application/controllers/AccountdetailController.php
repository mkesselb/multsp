<?php

/**
 * AccountDetail controller, handling all actions that concern a single account.
 * The following actions are supported:
 * 	index action
 * 	createentry action
 * 	editentry action
 * 	createcategory action
 * 	inviteuser action
 *	charts action
 *	credentials action
 * @author mkesselb, comoessl, polschan
 */
class AccountDetailController extends Zend_Controller_Action
{
	/**
	 * Empty init function, creatd by template.
	 * @see Zend_Controller_Action::init()
	 */
    public function init()
    {
       
    }

    /**
     * Index action shows the basic overview of the current account.
     * User_id is fetched from session, account_id is a get-parameter.
     * All account entries are fetched from this the database and given to the view.
     */
    public function indexAction()
    {
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $account_id = $_GET['id'];
            $namespace->account_id = $account_id;
			
			$user = new Application_Model_User();
			$umapper = new Application_Model_UserMapper();
			try{
				$umapper->find($namespace->id, $user);
			} catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
			}
			$this->view->user = $user;
            
            $uamapper = new Application_Model_UserInAccountMapper();
            $b = false;
            try{
            	$b = $uamapper->authUser($namespace->id, $account_id);
            } catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
            }
            if($b){
                $account = new Application_Model_Account();
                $mapperA = new Application_Model_AccountMapper();
                try{
                	$mapperA->findByField('id', $account_id, $account);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                $this->view->account = $account;
                
                $mapper  = new Application_Model_AccountEntryMapper();
                $results = array();
                try{
					$results = $mapper->findByField('account_id', $account_id);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                $mapperC = new Application_Model_CostcategoryMapper();
				$mapperU = new Application_Model_UserMapper();
				
                foreach ($results as $result){
                    $cat = new Application_Model_CostCategory();
                    try{
                    	$mapperC->find($result->getCostCategoryId(), $cat);
                    } catch (Exception $e) {
                    	return $this->_helper->redirector('error', 'error');
                    }
                    $result->setCostCategory($cat);    
					$user = new Application_Model_User();
					try{
						$mapperU->find($result->getUserId(), $user);
					} catch (Exception $e) {
						return $this->_helper->redirector('error', 'error');
					}
                    $result->setUser($user); 
                }
                
                $this->view->entries = $results; 
                $this->view->accId = $account_id;
            } else{
                return $this->_helper->redirector('credentials');
            }            
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
    }
    
    /**
     * CreateEntry action shows the create entry form on get.
     * On post, the values are taken from the form and a new entry object is written into the database.
     * Account_id and user_id is taken from the session.
     */
    public function createentryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            $account_id = $namespace->account_id;
            
            $uamapper = new Application_Model_UserInAccountMapper();
			$b = false;
            try{
            	$b = $uamapper->authUser($namespace->id, $account_id);
            } catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
            }
            if($b){
                $mapperC  = new Application_Model_CostcategoryMapper();
                $categories = array();
                try{
                	$categories = $mapperC->findByField('account_id', $account_id);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                
				$user = new Application_Model_User();
				$umapper = new Application_Model_UserMapper();
				try{
					$umapper->find($namespace->id, $user);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				$this->view->user = $user;
				
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
                        
                        $cat = new Application_Model_CostCategory();
                        $cat->setId($form->getValue('category'));
                        
                        $user = new Application_Model_User();
                        $user->setId($namespace->id);
                        
                        $entry->setCostCategory($cat);
                        $entry->setPrice($form->getValue('price'));
                        $entry->setComment($form->getValue('comment'));
                        $entry->setDate($form->getValue('date'));
                        $entry->setUser($user);
                        $entry->setAccountId($account_id);
                        try{
                        	$mapperE->save($entry);
                        } catch (Exception $e) {
                        	return $this->_helper->redirector('error', 'error');
                        }
                        
                        return $this->_redirect('accountdetail/index?id=' . $account_id);
                    }
                } else{
                	$form->getElement('date')->setValue(date("Y-m-d"));
                }
                
                $this->view->form = $form;
                $this->view->id = $account_id;
            } else {
               return $this->_helper->redirector('credentials');
            }
       } else {
           return $this->_helper->redirector('expire', 'index');
       }
    }
    
    /**
     * EditEntry action renders the edit entry from, which is similar to the create entry form,
     * but fills the form elements with the entry data that is fetched from database.
     * The fetch works with the entry id which is a get-parameter. Account_id is taken from the session.
     */
    public function editentryAction(){
    	$request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            $account_id = $namespace->account_id;
            $entry_id = $_GET['id'];
            
            $uamapper = new Application_Model_UserInAccountMapper();
			$b = false;
            try{
            	$b = $uamapper->authUser($namespace->id, $account_id);
            } catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
            }
            if($b){
                $mapperC  = new Application_Model_CostcategoryMapper();
                $categories = array();
                try{
                	$categories = $mapperC->findByField('account_id', $account_id);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                
				$user = new Application_Model_User();
				$umapper = new Application_Model_UserMapper();
				try{
					$umapper->find($namespace->id, $user);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				$this->view->user = $user;
				
                $categorynames = array();
                foreach($categories as $category){
                    $categorynames[$category->getId()] = $category->getName();
                }
                
                $namespace->categories = $categorynames;
                
                $form = new Application_Form_EditEntry();
                
                if ($this->getRequest()->isPost()){
                    if ($form->isValid($request->getPost())){
                        $entry = new Application_Model_AccountEntry(null);
                        
                        $cat = new Application_Model_CostCategory();
                        $cat->setId($form->getValue('category'));
                        
                        $user = new Application_Model_User();
                        $user->setId($namespace->id);
                        
                        $entry->setCostCategory($cat)
                        	->setPrice($form->getValue('price'))
                        	->setComment($form->getValue('comment'))
                        	->setDate($form->getValue('date'))
                        	->setUser($user)
                        	->setAccountId($account_id)
                        	->setId($entry_id);
						$mapperE  = new Application_Model_AccountEntryMapper();
						try{
							$mapperE->update($entry);
						} catch (Exception $e) {
							return $this->_helper->redirector('error', 'error');
						}
                        
                        return $this->_redirect('accountdetail/index?id=' . $account_id);
                    }
                } else{
                	//fill values in
                	$entry = new Application_Model_AccountEntry(null);
                	$mapperE = new Application_Model_AccountEntryMapper();
                	try{
                		$ent = $mapperE->findByField('id', $entry_id);
                		$entry = $ent[0];
                	} catch (Exception $e) {
                		return $this->_helper->redirector('error', 'error');
                	}
                	$form->getElement('category')->setValue($entry->getCostCategoryId());
                	$form->getElement('comment')->setValue($entry->getComment());
                	$form->getElement('price')->setValue($entry->getPrice());
                	$form->getElement('date')->setValue($entry->getDate());
                }
                
                $this->view->form = $form;
                $this->view->id = $entry_id;
                $this->view->accId = $account_id;
            } else {
               return $this->_helper->redirector('credentials');
            }
       } else {
           return $this->_helper->redirector('expire', 'index');
       }
    }
    
    /**
     * CreateCategory action shows the create category form on get.
     * On post, the name of the new category is taken from the form and written in the database,
     * with the account_id from session.
     */
    public function createcategoryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            $account_id = $namespace->account_id;
            
			$user = new Application_Model_User();
					$umapper = new Application_Model_UserMapper();
					try{
						$umapper->find($namespace->id, $user);
					} catch (Exception $e) {
						return $this->_helper->redirector('error', 'error');
					}
					$this->view->user = $user;
			
            $uamapper = new Application_Model_UserInAccountMapper();
			$b = false;
            try{
            	$b = $uamapper->authUser($namespace->id, $account_id);
            } catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
            }
            if($b){
                $form = new Application_Form_Category();
                
                if ($this->getRequest()->isPost()){
                    if ($form->isValid($request->getPost())){
                        $cat = new Application_Model_CostCategory(null);
                        $cat->setName($form->getValue('name'));
                        $cat->setAccountId($account_id);
                        $mapperC = new Application_Model_CostcategoryMapper();
                        try{
                        	$mapperC->save($cat);
                        } catch (Exception $e) {
                        	return $this->_helper->redirector('error', 'error');
                        }
                        return $this->_redirect('accountdetail/index?id=' . $account_id);
                    }
                }
                
                $this->view->form = $form;
                $this->view->id = $account_id;
            } else{
                return $this->_helper->redirector('credentials');
            }
        } else {
            return $this->_helper->redirector('expire', 'index');
        }
    }
    
    /**
     * InviteUser action shows the invite form on get.
     * On post, it tries to write the email from the invite form in the user_in_account relationship,
     * with the confirm flag of 0.
     */
    public function inviteuserAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        $form = new Application_Form_InviteUser();
        $status = '';
        
        $account_id = $namespace->account_id;
        
        $uamapper = new Application_Model_UserInAccountMapper();
		$b = false;
		try{
			$b = $uamapper->authUser($namespace->id, $account_id);
		} catch (Exception $e) {
        	return $this->_helper->redirector('error', 'error');
       	}
		if($b){
				$user = new Application_Model_User();
				$umapper = new Application_Model_UserMapper();
				try{
					$umapper->find($namespace->id, $user);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				$this->view->user = $user;
		
            if ($request->isPost()){
                if ($form->isValid($request->getPost())){
                    $email = $form->getValue('email');
                    $user = new Application_Model_User();
                    $umapper = new Application_Model_UserMapper();
                    try{
                    	$umapper->findByField('email', $email, $user);
                    } catch (Exception $e) {
                    	return $this->_helper->redirector('error', 'error');
                    }
                    if($user->getEmail() === $email){
                        //user found
                        $mapper = new Application_Model_UserInAccountMapper();
                        $userInAccount = new Application_Model_UserInAccount();
                        try{
                        	$mapper->find($user->getId(), $account_id, $userInAccount);
                        } catch (Exception $e) {
                        	return $this->_helper->redirector('error', 'error');
                        }
                        if($userInAccount->getUserId() === $user->getId()){
                            //user already existed
                            $status = 'User for entered email address is already invited in this account!';
                        } else{
                            $userInAccount->setAccountId($account_id)
                            ->setUserId($user->getId())
                            ->setConfirmed(0);
                            try{
                            	$mapper->save($userInAccount);
                            } catch (Exception $e) {
                            	return $this->_helper->redirector('error', 'error');
                            }
                            $status = 'User successful invited!';
                        }
                    } else{
                        //user not found
                        $status = 'Email address did not match any user!';
                    }
                }
            }
            
            $this->view->status = $status;
            $this->view->form = $form;
            $this->view->id = $account_id;
        } else{
            return $this->_helper->redirector('credentials');
        }
    }
    
    /**
     * Charts action fetches all entries of the account (account_id is get-parameter, user_id is in session),
     * aggregates them and gives them to the view. There, charts are drawn via javascript.
     */
    public function chartsAction(){
		$namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $account_id = $_GET['id'];
            $namespace->account_id = $account_id;
            
            $uamapper = new Application_Model_UserInAccountMapper();
            $b = false;
            try{
            	$b = $uamapper->authUser($namespace->id, $account_id);
            } catch (Exception $e) {
            	return $this->_helper->redirector('error', 'error');
            }
            if($b){
				$user = new Application_Model_User();
				$umapper = new Application_Model_UserMapper();
				try{
					$umapper->find($namespace->id, $user);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				$this->view->user = $user;
			
                $account = new Application_Model_Account();
                $mapperA = new Application_Model_AccountMapper();
                try{
                	$mapperA->findByField('id', $account_id, $account);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                $this->view->account = $account;
                
                $mapper  = new Application_Model_AccountEntryMapper();
                $results = array();
                try{
                	$results = $mapper->findByField('account_id', $account_id);
                } catch (Exception $e) {
                	return $this->_helper->redirector('error', 'error');
                }
                $mapperC = new Application_Model_CostcategoryMapper();
				$mapperU = new Application_Model_UserMapper();
				$users = array();
				
				//array, key = categoryname, value = 0
				$pieChartCategories = array();
				$categories = array();
				try{
					$categories = $mapperC->findByField('account_id', $account_id);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				foreach($categories as $category){
				    $pieChartCategories[$category->getName()] = 0;
				}
				
				//array, key = user_email, value = 0
				$pieChartUsers = array();
				$usersInAccount = array();
				try{
					$usersInAccount = $uamapper->findByField('account_id', $account_id);
				} catch (Exception $e) {
					return $this->_helper->redirector('error', 'error');
				}
				foreach ($usersInAccount as $userInAccount){
				    $user = new Application_Model_User();
				    try{
				    	$mapperU->findByField('id', $userInAccount->getUserId(), $user);
				    } catch (Exception $e) {
				    	return $this->_helper->redirector('error', 'error');
				    }
				    $pieChartUsers[$user->getEmail()] = 0;
				}
				
                foreach ($results as $result){
                    $cat = new Application_Model_CostCategory();
                    try{
                    	$mapperC->find($result->getCostCategoryId(), $cat);
                    } catch (Exception $e) {
                    	return $this->_helper->redirector('error', 'error');
                    }
                    $result->setCostCategory($cat);  
					$user = new Application_Model_User();
					try{
						$mapperU->find($result->getUserId(), $user);
					} catch (Exception $e) {
						return $this->_helper->redirector('error', 'error');
					}
                    $result->setUserEmail($user->getEmail()); 
                    $res = floatval(str_replace(',', '.', $result->getPrice()));
                    $pieChartCategories[$cat->getName()] += $res;
                    $pieChartUsers[$user->getEmail()] += $res;
                }
                
                $this->view->pieChartCategories = $pieChartCategories;
                $this->view->pieChartUsers= $pieChartUsers;
                $this->view->accId = $account_id;
            } else{
                return $this->_helper->redirector('credentials');
            }            
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
    }
    
    /**
     * Credentials action has an empty body and shows only the corresponding view.
     * This action is called when another action is called with insufficient credentials
     * (e.g. trying to access an account by manipulating the URL).
     */
    public function credentialsAction(){
    }
}