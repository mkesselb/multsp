<?php
class AccountDetailController extends Zend_Controller_Action
{
    public function init()
    {
       
    }

    public function indexAction()
    {
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)) {
            $account_id = $_GET['id'];
            $namespace->account_id = $account_id;
            
            $uamapper = new Application_Model_UserInAccountMapper();
            if($uamapper->authUser($namespace->id, $account_id)){
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
            } else{
                return $this->_helper->redirector('credentials');
            }            
        } else {
            return $this->_helper->redirector('expire', 'index');    
        }
    }
    
    public function createentryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            $account_id = $namespace->account_id;
            
            $uamapper = new Application_Model_UserInAccountMapper();
            if($uamapper->authUser($namespace->id, $account_id)){
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
                        
                        return $this->_redirect('accountdetail/index?id=' . $account_id);
                    }
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
    
    public function createcategoryAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            $account_id = $namespace->account_id;
            
            $uamapper = new Application_Model_UserInAccountMapper();
            if($uamapper->authUser($namespace->id, $account_id)){
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
            } else{
                return $this->_helper->redirector('credentials');
            }
        } else {
            return $this->_helper->redirector('expire', 'index');
        }
    }
    
    public function inviteuserAction(){
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        $form = new Application_Form_InviteUser();
        $status = '';
        
        $account_id = $namespace->account_id;
        
        $uamapper = new Application_Model_UserInAccountMapper();
        if($uamapper->authUser($namespace->id, $account_id)){
            if ($request->isPost()){
                if ($form->isValid($request->getPost())){
                    $email = $form->getValue('email');
                    $user = new Application_Model_User();
                    $umapper = new Application_Model_UserMapper();
                    $umapper->findByField('email', $email, $user);
                    if($user->getEmail() === $email){
                        //user found
                        $mapper = new Application_Model_UserInAccountMapper();
                        $userInAccount = new Application_Model_UserInAccount();
                        $mapper->find($user->getId(), $account_id, $userInAccount);
                        if($userInAccount->getUserId() === $user->getId()){
                            //user already existed
                            $status = 'User for entered email address is already invited in this account!';
                        } else{
                            $userInAccount->setAccountId($account_id)
                            ->setUserId($user->getId())
                            ->setConfirmed(0);
                            $mapper->save($userInAccount);
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
    
    public function credentialsAction(){
        //
    }
}