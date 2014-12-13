<?php
class AccountdetailController extends Zend_Controller_Action
{
    public function init()
    {
       
    }

    public function indexAction()
    {
        $this->view->text = $_GET['id'];
    }
}
