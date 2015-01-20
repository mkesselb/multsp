<?php

/**
 * Register controller class, handling the actions associated with registering of a new user.
 * 
 * @author mkesselb, comoessl, polschan
 */
class RegisterController extends Zend_Controller_Action
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
     * Index action, showing the register form of a new user on get.
     * On post, the new user is written into the database if the email is not yet registered,
     * together with a confirmation code.
     * This confirmation code and a link to the confirm action of this controller
     * is sent to the entered email of the user.
     */
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
                    //$pw = password_hash($user->getPassword(), PASSWORD_DEFAULT);
                    $pw = $this->bcrypt_encode($form->getValue('email'), $user->getPassword());
                    $user->setPassword($pw);
                    $mapper->save($user);
                    
                    //mail configuration
                    $config = array('ssl' => 'tls',
                    		'port' => 587,
                    		'auth' => 'login',
                    		'username' => 'mkesselb@gmail.com',
                    		'password' => 'webtecpass',
                    );
                    $tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
                    Zend_Mail::setDefaultTransport($tr);
                    
                    $mail = new Zend_Mail();
                    $mail->setBodyText('registration url: '
                        . $_SERVER["HTTP_HOST"]
                        . $_SERVER["REQUEST_URI"]
                        . '/confirm?confirmation_code=' . $user->getConfirmation_code())
                        ->setFrom('mkesselb@gmail.com', 'my-ultimate-spendings')
                        ->addTo($user->getEmail(), $user->getEmail())
                        ->setSubject('Registration to my-ultimate-spendings')
                        ->send();
                    
                    //for test only: return confirmation link to user on view
                    $duplicate = 'registration complete. please check your emails!';
                    /*$duplicate = 'registration url: '
                        . $_SERVER["HTTP_HOST"]
                        . $_SERVER["REQUEST_URI"]
                        . '/confirm?confirmation_code=' . $user->getConfirmation_code();*/
                    //redirect to index/index
                    //return $this->_helper->redirector('index', 'index');
                }
            }
        }
        
        $this->view->duplicate = $duplicate;
        $this->view->form = $form;
    }
    
    /**
     * Confirm action reads the confirmation code as get-parameter and tries to confirm the corresponding
     * user so that he/she can login.
     */
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
    
    /** 
     * Note to the encryption: we wanted to use the new php 5.5 methods password_hash
     * and password_verify, but the server was only on php version 5.3. So this is the alternative.
     * 
     * Encrypt function from: http://www.phpgangsta.de/schoener-hashen-mit-bcrypt.
     */
    private function bcrypt_encode ( $email, $password, $rounds='08' )
    {
    	$string = hash_hmac ( "whirlpool", str_pad ( $password, strlen ( $password ) * 4, sha1 ( $email ), STR_PAD_BOTH ), SALT, true );
    	$salt = substr ( str_shuffle ( './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ) , 0, 22 );
    	return crypt ( $string, '$2a$' . $rounds . '$' . $salt );
    }
}