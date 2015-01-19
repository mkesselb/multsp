<?php
/**
 * NewEntry form, handling the creation workflow of new entries.
 * Information the user has to enter:
 * 	category
 *	date
 *	price
 *	comment
 * 
 * @author mkesselb, comoessl, polschan 
 */
class Application_Form_NewEntry extends Zend_Form
{
	/**
	 * Initializes the form fields. The NewEntry form contains the following fields:
	 * 	category multioption
	 *	date picker (powered by javascript in view)
	 *	price text
	 *	comment text
	 *	submit button
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
        
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->categories)){
             $options = $namespace->categories;
        } else {
            $options = array('' => '');
        }
        
		//Add a text element for category
        $this->addElement('select','category',array(
            'ignore' => true,
            'label' => 'category',
            'multioptions' => $options         
            ));
        
		//Add the date element
        $this->addElement('text','date',array(
            'ignore' => true,
            'label' => 'date',
            'validators' => array('Date')
        ));
        
		//Add a text element for price
        $this->addElement('text','price',array(
            'ignore' => true,
            'label' => 'price',
            'validators' => array('Float'),
        ));
        
		//Add a text element for comment
        $this->addElement('text','comment',array(
            'ignore' => true,
            'label' => 'comment'
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