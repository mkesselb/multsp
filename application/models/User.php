<?php
class Application_Model_User
{
    protected $email;
    protected $password;
    protected $confirmationcode;
    
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
            throw new Exception('Invalid user property');
        }
        $this->$method($value);
    }
    
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid user property');
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
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    
    public function getConfirmationcode(){
        return $this->confirmationcode;
    }
    
    public function setConfirmationcode($confirmationcode){
        $this->confirmationcode = $confirmationcode;
        return $this;
    }
}