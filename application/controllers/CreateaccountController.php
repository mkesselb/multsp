<?php
class CreateAccountController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
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
                $userInAccount->setAccountId($account->getId())->setUserId($namespace->id)->setConfirmed(1);
                $mapperC->save($userInAccount);
              
                return $this->_helper->redirector('index', 'account');
     
            } 
        }
        
        $this->view->form = $form;
       
    }
}