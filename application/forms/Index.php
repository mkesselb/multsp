<?php

class Application_Form_Index extends Zend_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the comment element
        $this->addElement('password', 'password', array(
            'label'      => 'Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(8, 20))
                )
        ));

        // Add the submit button
        $this->addElement('submit', 'login', array(
            'ignore'   => true,
            'label'    => 'login',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}