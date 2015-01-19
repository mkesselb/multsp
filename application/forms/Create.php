<?php
/**
 * Create form, handling the creation workflow of new accounts.
 * Information the user has to enter:
 * 	name
 * 
 * @author mkesselb, comoessl, polschan 
 */
class Application_Form_Create extends Zend_Form
{
	/**
	 * Initializes the form fields. The Create form contains the following fields:
	 * 	name text field
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

        // Add a text element for account name
        $this->addElement('text', 'name', array(
            'label'      => 'Name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'NotEmpty',
            )
        ));

        // Add the register button
        $this->addElement('submit', 'create', array(
            'ignore'   => true,
            'label'    => 'create',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}