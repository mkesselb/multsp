<?php

class Application_Form_NewEntry extends Zend_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
        $this->addElement('select','category',array(
            'ignore' => true,
            'label' => 'category'              
            ));
        
        $this->addElement('text','date',array(
            'ignore' => true,
            'label' => 'date',
            'validators' => array('Date')
        ));
        
        $this->addElement('text','price',array(
            'ignore' => true,
            'label' => 'price',
            'validator' => array('Float')
        ));
        
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