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
        $duplicate = null;
      
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                $u = new Application_Model_User(null);
                $mapper = new Application_Model_UserMapper();
                $mapper->findByField('email', $form->getValue('email'), $u);
                if($u->getEmail() === $form->getValue('email')){
                    $duplicate = 'registration with duplicate email not allowed';
                } else{
                    $user = new Application_Model_User($form->getValues());
                    $user->setConfirmation_code(substr(base64_encode(sha1(mt_rand())), 0, 20));
                    $pw = password_hash($user->getPassword(), PASSWORD_DEFAULT);
                    $user->setPassword($pw);
                    $mapper->save($user);
                    
                    //TODO: mail configuration
                    $mail = new Zend_Mail();
                    $mail->setBodyText('registration url: '
                        . $_SERVER["HTTP_HOST"]
                        . $_SERVER["REQUEST_URI"]
                        . '/confirm?confirmation_code=' . $user->getConfirmation_code())
                        ->setFrom('noreply@multsp.at', 'noreply')
                        ->addTo($user->getEmail(), $user->getEmail())
                        ->setSubject('Registration to my-ultimate-spendings')
                        ->send();
                    
                    //for test only: return confirmation link to user on view
                    $duplicate = 'registration url: '
                        . $_SERVER["HTTP_HOST"]
                        . $_SERVER["REQUEST_URI"]
                        . '/confirm?confirmation_code=' . $user->getConfirmation_code();
                    //redirect to index/index
                    //return $this->_helper->redirector('index', 'index');
                }
            }
        }
        
        $this->view->duplicate = $duplicate;
        $this->view->form = $form;
    }
    
    public function confirmAction(){
        $request = $this->getRequest();
        $success = 'confirmation not successful';
        
        if ($request->isGet()){
            $confirmcode = $request->getParam('confirmation_code');
            $user = new Application_Model_User();
            $mapper = new Application_Model_UserMapper();
            $mapper->findByField('confirmation_code', $confirmcode, $user);
            if($user->getConfirmation_code() === $confirmcode){
                $mapper->update($user->setConfirmation_code('1'));
                $success = 'confirmation successful!';
            }
        }
        
        $this->view->success = $success;
    }
}