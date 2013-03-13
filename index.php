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
	public $resultFirst;
	public $resultLast;
	public $resultAge;
	public $resultSex;

	public function __construct($FirstName, $LastName) {
  		$this->FirstName = $FirstName;
  		$this->LastName = $LastName;
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
		$this->resultSex= $resultArray['Sex'];
	}

 	public function __toString() {
        return $this->resultFirst . " " . $this->resultLast . ", Age " . $this->resultAge . ", " . $this->resultSex . "<br />";
    }

}

$traveler1 = new Traveler("Leo","Babauta","95","M");
$traveler1->save();

$traveler2 = new Traveler("Leo","Babauta");
$traveler2->load();

echo $traveler2;

?>