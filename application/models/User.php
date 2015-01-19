<?php
/**
 * User model class, representing a user working with the application.
 * User_id and email are the two most important fields, filling various roles in the application.
 *
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_User
{
	/** Database id of the user. */
    protected $id;
    
    /** Email of the user. */
    protected $email;
    
    /** Password of the user. */
    protected $password;
    
    /** Confirmation_code of the user. */
    protected $confirmation_code;
    
    /**
     * Constructor, setting all values from the parameter array that represent valid values.
     * @param array $options 	array of name-value pairs that shall be set for the new instance
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
    /**
     * 'Magic' setter method, providing a way to access the various set methods of this class.
     * @param $name		name of the setter method to be invoked
     * @param $value	value to be set
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid user property');
        }
        $this->$method($value);
    }
    
    /**
     * 'Magic' getter method, providing a way to access the various get methods of this class.
     * @param $name		name of the getter method to be invoked
     * @throws Exception
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid user property');
        }
        return $this->$method();
    }
    
    /**
     * Calls the setter specified in the parameter array with the corresponding values.
     * Returns the resulting object.
     * @param array $options	name-value, giving the name of the setter to be called with the corr. value
     * @return Application_Model_User	result from constructing a new instance and
     * 											invoking all specified setter
     */
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
    
    /**
     * Returns the email.
     */
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * Sets the email with parameter.
     * @param string $email	the email to be set
     * @return Application_Model_User	the changed object
     */
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }
    
    /**
     * Returns the password 
     */
    public function getPassword(){
        return $this->password;
    }
    
    /**
     * Sets the password with parameter.
     * @param string $password	the password to be set
     * @return Application_Model_User	the changed object
     */
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    
    /**
     * Returns the confirmation_code.
     */
    public function getConfirmation_code(){
        return $this->confirmation_code;
    }
    
    /**
     * Sets the confirmation_code with parameter.
     * @param string $confirmation_code	the confirmation_code to be set
     * @return Application_Model_User	the changed object
     */
    public function setConfirmation_code($confirmation_code){
        $this->confirmation_code = $confirmation_code;
        return $this;
    }
    
    /**
     * Returns the id.
     * @return unknown
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Sets the id with parameter.
     * @param int $id	the id to be set
     * @return Application_Model_User	the changed object
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }
}