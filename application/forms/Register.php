<?php
/**
 * Register form, handling the register workflow of new users.
 * Information the user has to enter:
 * 	email
 *  password
 *  confirm password
 * 
 * @author mkesselb, comoessl, polschan 
 */
class Application_Form_Register extends Zend_Form
{
	/**
	 * Initializes the form fields. The Register form contains the following fields:
	 * 	email text field
	 *  password field
	 *  confirm password field
	 *  submit button
	 *  csfr token
	 *   
	 * @see Zend_Form::init()
	 */
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
        // Add table tag
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));        

        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the password element
        $this->addElement('password', 'password', array(
            'label'      => 'Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(8, 20))
                )
        ));
        
        $this->addElement('password', 'password2', array(
            'label'      => 'Re-enter password:',
            'required'   => true,
            'validators' => array(
                array('validator1' => 'StringLength', 'options' => array(8, 20)),
                array('validator2' => 'identical', false, array('token' => 'password'))
            )
        ));

        // Add the register button
        $this->addElement('submit', 'register', array(
            'ignore'   => true,
            'label'    => 'register',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}