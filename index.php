<?php
require_once 'mysql.php';

class Traveler {
	// Properties of a traveler
	// Note: UserID isn't necessary as it's auto-incremented
	public $FirstName;
	public $LastName;
	public $Age;
	public $Sex;
	public $table = "travelers";
	// these are used in load and toString fuctions
	public $resultFirst;
	public $resultLast;
	public $resultAge;
	public $resultSex;
	public $fullSex;

	// uses a series of constructs depending on how many parameters are passed to Traveler class
	// this first one decides how many parameters there are, and calls the appropriate subconstruct
 	public function __construct() { 
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    } 
    
    public function __construct1($a1) { 
    	// for if there is just one parameter
  		$this->FirstName = $a1;
    } 
    
    public function __construct2($a1,$a2) { 
    	// for if there are 2 parameters
  		$this->FirstName = $a1;
  		$this->LastName = $a2;
    } 
    
    public function __construct3($a1,$a2,$a3) { 
    	// for if there are 3 parameters
  		$this->FirstName = $a1;
  		$this->LasttName = $a2;
  		$this->Age = $a3;
  	} 
    
    public function __construct4($a1,$a2,$a3, $a4) { 
    	// for if there are 4 parameters
  		$this->FirstName = $a1;
  		$this->LastName = $a2;
  		$this->Age = $a3;
  		$this->Sex = $a4;
    } 

  	public function save() {
  		global $link;
  		// make variables from included mysql.php accessible to this function
		$table = "travelers";

  		// Escape strings
  		$FirstName = mysqli_real_escape_string($link, $this->FirstName);
  		$LastName = mysqli_real_escape_string($link, $this->LastName);
  		$Age = mysqli_real_escape_string($link, $this->Age);
  		$Sex = mysqli_real_escape_string($link, $this->Sex);

  		// Insert into table only if not duplicate
  		If (!$this->dupeCheck()) {
			$sqlquery = "INSERT INTO $this->table
			(FirstName,LastName,Age,Sex) VALUES('$FirstName','$LastName','$Age','$Sex')";
			$results = mysqli_query($link, $sqlquery);
			echo "Success ... added " . $FirstName . " " . $LastName . " to database. </br />";
		} else {
			echo "Sorry, " . $this->FirstName . " " . $this->LastName . " is already in our database.<br />";
		}
	}

	public function dupeCheck() {
		global $link;
		$table = "travelers";

		// checks to see if there's already a record with same first & last name
		$dupeSql = "SELECT * FROM $this->table WHERE FirstName = '$this->FirstName'AND LastName = '$this->LastName'";

        $result = mysqli_query($link, $dupeSql);
        $dupeCount = mysqli_num_rows($result);

        if ($dupeCount > 0) {
        	return true;
        } else {
        	return false;
        }
	}

	public function load() {
		// function looks up name in traveler database, loads data into variable
		global $link;

		// first looks up the row that has a first and last name match
		$result = mysqli_query($link,"SELECT * FROM $this->table WHERE FirstName = '$this->FirstName'AND LastName = '$this->LastName'");

		// next puts the result of the query into an array, then into individual variables
		$resultArray = mysqli_fetch_array($result);
		$this->resultFirst = $resultArray['FirstName'];
		$this->resultLast = $resultArray['LastName'];
		$this->resultAge = $resultArray['Age'];
		$this->resultSex = $resultArray['Sex'];

		// change sex to full word
		if ($this->resultSex == 'M') {
			$this->fullSex = 'Male';
		} elseif ($this->resultSex == 'F') {
			$this->fullSex = 'Female';
		}
	}

 	public function __toString() {
		// then create string to return when class is echoed as a string
        return $this->resultFirst . " " . $this->resultLast . ", Age " . $this->resultAge . ", " . $this->fullSex . "<br />";
    }

}

$traveler1 = new Traveler("Maia","Cruz","13","F");
$traveler1->save();

$traveler2 = new Traveler("Maia","Cruz");
$traveler2->load();

echo $traveler2;

?>