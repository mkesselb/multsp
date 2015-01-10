<?php
class Application_Model_AccountEntry
{
    protected $id;
    protected $account_id;
    protected $cost_category_id;
    protected $cost_category_name;
    protected $user_id;
	protected $user_email;
    protected $date;
    protected $price;
    protected $comment;
    
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
            throw new Exception('Invalid account entry property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid account entry property');
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

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getAccountId(){
        return $this->account_id;
    }
    
    public function setAccountId($account_id){
        $this->account_id = $account_id;
        return $this;
    }
    
    public function getCostCategoryId(){
        return $this->cost_category_id;
    }
    
    public function setCostCategoryId($cost_category_id){
        $this->cost_category_id = $cost_category_id;
        return $this;
    }
    
    public function getCostCategoryName(){
        return $this->cost_category_name;
    }
    
    public function setCostCategoryName($cost_category_name){
        $this->cost_category_name = $cost_category_name;
        return $this;
    }
    
    public function getUserId(){
        return $this->user_id;
    }
    
    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }
	
	 public function getUserEmail(){
        return $this->user_email;
    }
    
    public function setUserEmail($user_email){
        $this->user_email = $user_email;
        return $this;
    }
    
    public function getDate(){
        return $this->date;
    }
    
    public function setDate($date){
        $this->date = $date;
        return $this;
    }
    
    public function getPrice(){
        return $this->price;
    }
    
    public function setPrice($price){
        $this->price = $price;
        return $this;
    }
    
    public function getComment(){
        return $this->comment;
    }
    
    public function setComment($comment){
        $this->comment = $comment;
        return $this;
    }
    
}