<?php
/**
 * AccountEntry model class, representing individual cost entries made for an account.
 * All relevant information from a single entry are capsuled in this object.
 *
 * @author mkesselb, comoessl, polschan
 */
class Application_Model_AccountEntry
{
	/** Database id of the account entry. */
    protected $id;
    
    /** Database account_id the entry is tied to. */
    protected $account_id;
    
    /** Database cost category id the entry is tied to. */
    protected $cost_category_id;
    //TODO: use costcategory class instead of id/name of costcat
    /** Name of the tied cost category. */
    protected $cost_category_name;
    
    /** Database id of the user that made the entry. */
    protected $user_id;
    //TODO: use user class insted of id/email
    /** Email of the user that made the entry. */
	protected $user_email;
	
	/** Date string when the entry was made. */
    protected $date;
    
    /** Float price of this entry. */
    protected $price;
    
    /** Comment text made for this entry. */
    protected $comment;
    
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
            throw new Exception('Invalid account entry property');
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
            throw new Exception('Invalid account entry property');
        }
        return $this->$method();
    }

    /**
     * Calls the setter specified in the parameter array with the corresponding values.
     * Returns the resulting object.
     * @param array $options	name-value, giving the name of the setter to be called with the corr. value
     * @return Application_Model_AccountEntry	result from constructing a new instance and
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
     * Returns the id.
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Sets the id with parameter.
     * @param int $id	the id to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setId($id){
        $this->id = $id;
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
     * @param int $account_id	the acount_id to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setAccountId($account_id){
        $this->account_id = $account_id;
        return $this;
    }
    
    /**
     * Returns the cost_category_id.
     */
    public function getCostCategoryId(){
        return $this->cost_category_id;
    }
    
    /**
     * Sets the cost_category_id with parameter.
     * @param int $cost_category_id	the id to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setCostCategoryId($cost_category_id){
        $this->cost_category_id = $cost_category_id;
        return $this;
    }
    
    /**
     * Returns the cost_category_name
     */
    public function getCostCategoryName(){
        return $this->cost_category_name;
    }
    
    /**
     * Sets the cost_category_name with parameter.
     * @param name $cost_category_name	the name to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setCostCategoryName($cost_category_name){
        $this->cost_category_name = $cost_category_name;
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
     * @param int $user_id	the id to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }
	
    /**
     * Returns the user_email.
     */
	 public function getUserEmail(){
        return $this->user_email;
    }
    
    /**
     * Sets the user_email with parameter.
     * @param string $user_email	the email to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setUserEmail($user_email){
        $this->user_email = $user_email;
        return $this;
    }
    
    /**
     * Returns the date.
     */
    public function getDate(){
        return $this->date;
    }
    
    /**
     * Sets the date with parameter.
     * @param string $date	the date to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setDate($date){
        $this->date = $date;
        return $this;
    }
    
    /**
     * Returns the price.
     */
    public function getPrice(){
        return str_replace('.', ',', $this->price);
    }
    
    /**
     * Sets the price with parameter.
     * @param float $price	the price to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setPrice($price){
        $this->price = str_replace(',', '.', $price);
        return $this;
    }
    
    /**
     * Returns the comment.
     */
    public function getComment(){
        return $this->comment;
    }
    
    /**
     * Sets the comment with parameter.
     * @param string $comment	the comment to be set
     * @return Application_Model_AccountEntry	the changed object
     */
    public function setComment($comment){
        $this->comment = $comment;
        return $this;
    }
    
}