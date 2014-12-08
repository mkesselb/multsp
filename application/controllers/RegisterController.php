<?php

class RegisterController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Register();
        
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){ 
                $user = new Application_Model_User($form->getValues());
                //TODO: set user confirm code
                $user->setConfirmationcode('abc');
                $mapper = new Application_Model_UserMapper();
                //TODO: first, check if user email exists
                
                $mapper->save($user);
                
                //TODO: send zend email
                
                
                //redirect to index/index
                return $this->_helper->redirector('index', 'index');
            }
        }
        
        $this->view->form = $form;
    }
    
    public function confirmAction(){
        //check confirm code of request
        //if ok->purge confirm code in db, redirect to login, user can login now
        
    }
}