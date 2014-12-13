<?php
class Application_Model_UserInAccount
{
    protected $user_id;
    protected $account_id;
    protected $confirmed;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid user_in_account property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid user_in_account property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    public function getAccountId(){
        return $this->account_id;
    }

    public function setAccountId($account_id){
        $this->account_id = $account_id;
        return $this;
    }

    public function getConfirmed(){
        return $this->confirmed;
    }

    public function setConfirmed($confirmed){
        $this->confirmed = $confirmed;
        return $this;
    }
}