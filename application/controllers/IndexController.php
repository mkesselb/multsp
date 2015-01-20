<?php
require_once 'Zend/Session/Namespace.php';

/**
 * Index controller class, handling the basic login action as well as catching lost sessions.
 * 
 * @author mkesselb, comoessl, polschan
 */
class IndexController extends Zend_Controller_Action
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
     * Index action. On get, the action simply shows the login form and register link.
     * On post, the login form information is checked against the database to confirm the login attempt or not.
     * On confirm login, the user is redirected to the AccountController.
     * On successful login, the user_id is entered in the session.
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->id)){
            return $this->_helper->redirector('index', 'account');
        }
        
        $form    = new Application_Form_Login();
        $confirm = null;
        
        if ($this->getRequest()->isPost()){
            if ($form->isValid($request->getPost())){
                $user = new Application_Model_User(null);
                $mapper  = new Application_Model_UserMapper();
                $mapper->findByField('email', $form->getValue('email'), $user);
                if($this->bcrypt_check($user->getEmail(), $form->getValue('password'), $user->getPassword())){
                //if(password_verify($form->getValue('password'), $user->getPassword())){
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
    
    /**
     * Logout action is called by other controllers when the user wishes to logout.
     * Session parameter are unset and the index action is shown.
     */
    public function logoutAction(){
        //show login form, and delete session
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        unset($namespace->id);
        
        return $this->_helper->redirector('index', 'index');
    }
    
    /**
     * Expire action is called when an invalid session is encountered.
     * Session parameter are unset and an info-page is shown.
     */
    public function expireAction(){
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        unset($namespace->id);
    }
    
    /** Decrypt function from: http://www.phpgangsta.de/schoener-hashen-mit-bcrypt. */
    private function bcrypt_check ( $email, $password, $stored )
    {
    	$string = hash_hmac ( "whirlpool", str_pad ( $password, strlen ( $password ) * 4, sha1 ( $email ), STR_PAD_BOTH ), SALT, true );
    	return crypt ( $string, substr ( $stored, 0, 30 ) ) == $stored;
    }
}