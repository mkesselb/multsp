<?php
require_once 'Zend/Session/Namespace.php';

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        $form    = new Application_Form_Index();
        $confirm = null;
        
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                $user = new Application_Model_User(null);
                $mapper  = new Application_Model_UserMapper();
                $mapper->findByField('email', $form->getValue('email'), $user);
                if(password_verify($form->getValue('password'), $user->getPassword())){
                    if($user->getConfirmation_code() === '1'){
                        $namespace->id = $user->getId();
                        return $this->_helper->redirector('index', 'account');
                    } else{
                        $confirm = 'email is not yet confirmed!';
                    } 
                } else{
                    $confirm = 'email or password not correct';
                }
            }
        }
       
        $this->view->confirm = $confirm;
        $this->view->form = $form;
    }
}