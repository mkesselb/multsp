<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Index();
        
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                //TODO: check if user is confirmed
                /*$comment = new Application_Model_Guestbook($form->getValues());
                 $mapper  = new Application_Model_GuestbookMapper();
                 $mapper->save($comment);*/
                
                return $this->_helper->redirector('index', 'account');
            }
        }
        
        $this->view->form = $form;
    }
}