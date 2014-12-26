<?php

class Application_Form_InviteUser extends Zend_Form
{
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
            'label'      => 'User email:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'NotEmpty',
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'invite', array(
            'ignore'   => true,
            'label'    => 'invite',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}