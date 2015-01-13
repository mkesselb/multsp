<?php

class Application_Form_EditEntry extends Zend_Form
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
        
        $namespace = new Zend_Session_Namespace('myUltimateSession');
        if(isset($namespace->categories)){
             $options = $namespace->categories;
        } else {
            $options = array('' => '');
        }
        
        $this->addElement('select','category',array(
            'ignore' => true,
            'label' => 'category',
            'multioptions' => $options         
            ));
        
        $this->addElement('text','date',array(
            'ignore' => true,
            'label' => 'date',
            'validators' => array('Date')
        ));
        
        $this->addElement('text','price',array(
            'ignore' => true,
            'label' => 'price',
            'validators' => array('Float'),
        ));
        
        $this->addElement('text','comment',array(
            'ignore' => true,
            'label' => 'comment'
        ));

        // Add the register button
        $this->addElement('submit', 'save', array(
            'ignore'   => true,
            'label'    => 'save',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}