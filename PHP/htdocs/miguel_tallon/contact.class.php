<?php 

class contact {

 public $firstname;
 public $lastname;
 public $address;
 public $email;
 public $phone;

 // CONSTRUCTOR
 public function __construct($firstname,$lastname,$address,$email,$phone) {
	$this->firstname = $firstname;
	$this->lastname = $lastname;
	$this->address = $address;
    $this->email = $email;
	$this->phone = $phone;
   }
   
   public function insert(){
   //First, validate the fields.
		if ($this->validate()){
		
			// Create the sql query
			$sql = "INSERT INTO contact VALUES ('', '$this->firstname', '$this->lastname', '$this->address', '$this->email', '$this->phone')"; 
			
			//Execute query
			if (mysql_query($sql)){
				echo "Record successfully inserted.";
			}else{
				echo "Error: The contact could not be inserted.";
			}
						
		}
      
   }
   
  private function validate(){
	$validFLAG = true;
	if ($this->firstname == ""){		
		echo "Firstname is mandatory";
		return false;
	}
	
	if ($this->lastname == ""){
		//trigger_error("Lastname is mandatory");
		echo "Lastname is mandatory";
		return false;
	}
	
	if ($this->email == ""){
		echo "Email is mandatory";
		return false;
	}else{
		$validFLAG=$this->checkEmail();	
	}
	return $validFLAG;
  }
  
   private function checkEmail(){
	
	if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){ 
		
		echo "Check Email address";
		return false;
		
	}
	return true;
 
 }
 
 
 }

?>