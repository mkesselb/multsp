<?php
/**
 * Model class for the relationship of user_in_account.
 * 
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_UserInAccount
{
	/** Database user_id of this relationship. */
    protected $user_id;
    
    /** Database account_id of this relationship. */
    protected $account_id;
    
    /** Confirmed flag of this relationship. */
    protected $confirmed;

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
            throw new Exception('Invalid user_in_account property');
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
            throw new Exception('Invalid user_in_account property');
        }
        return $this->$method();
    }

    /**
     * Calls the setter specified in the parameter array with the corresponding values.
     * Returns the resulting object.
     * @param array $options	name-value, giving the name of the setter to be called with the corr. value
     * @return Application_Model_UserInAccount	result from constructing a new instance and
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
     * Returns the user_id.
     */
    public function getUserId(){
        return $this->user_id;
    }

    /**
     * Sets the user_id with parameter. 
     * @param int $user_id		the user_id to be set
     * @return Application_Model_UserInAccount	the changed object
     */
    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Returns the account_id.
     */
    public function getAccountId(){
        return $this->account_id;
    }

    /**
     * Sets the account_id with parameter.
     * @param int $account_id	the account_id to be set
     * @return Application_Model_UserInAccount	the changed object
     */
    public function setAccountId($account_id){
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * Returns the confirmed flag.
     */
    public function getConfirmed(){
        return $this->confirmed;
    }

    /**
     * Sets the confirmed flag with parameter.
     * @param int $confirmed	the confirmed flag to be set
     * @return Application_Model_UserInAccount	the changed object
     */
    public function setConfirmed($confirmed){
        $this->confirmed = $confirmed;
        return $this;
    }
}