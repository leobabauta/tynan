<?php
require_once 'mysql.php';

class Trip {
    public $place;
    public $days;
 	public $travelers = Array();

	public function __construct($place, $days) {
  		$this->place = $place;
  		$this->days = $days;
  	}

   	public function addTraveler($newTraveler) {
		$this->travelers[] = $newTraveler;
  	}

  	public function display() {
  		$travelerString ="";
  		$travelerCount = count($this->travelers);
  		if ($travelerCount === 0) {
			$travelerString = "no one";
		}
		foreach ($this->travelers as $key => $value) {
			if ($key < 1) {
				$travelerString = $travelerString . $value;
			} elseif ($key > 0 && $key === $travelerCount-1) {
				$travelerString = $travelerString . " and " . $value;
			} else {
				$travelerString = $travelerString . ", " . $value;
			}
		}
		echo $this->days . " day trip to " . $this->place . " with " . $travelerString . ".<br />";
	}
}

class Traveler {
	// Properties of a traveler
	// Note: UserID isn't necessary as it's auto-incremented
	public $FirstName;
	public $LastName;
	public $Age;
	public $Sex;
	public $table = "travelers";


	public function __construct($FirstName, $LastName, $Age, $Sex) {
  		$this->FirstName = $FirstName;
  		$this->LastName = $LastName;
  		$this->Age = $Age;
  		$this->Sex = $Sex;
 	}
 
 	public function dupecheck() {
		// checks to see if there's already a record with same first & last name
		global $link;

		$dupesql = "SELECT COUNT(*) 
                FROM $this->table 
                WHERE FirstName = '$this->FirstName'
            ";

        $dupe = mysql_query($dupesql);

		// test dupe function
  		echo "dupe equals " . $dupe . "<br />";

    	return $dupe;
	}

  	public function save() {
  		// make variables from included mysql.php accessible to this function
  		global $link;
		$table = "travelers";

  		// Escape strings
  		$FirstName = mysqli_real_escape_string($link, $this->FirstName);
  		$LastName = mysqli_real_escape_string($link, $this->LastName);
  		$Age = mysqli_real_escape_string($link, $this->Age);
  		$Sex = mysqli_real_escape_string($link, $this->Sex);

  		// Insert into table only if not duplicate
  		If ($this->dupecheck() > 0) {
			$sqlquery = "INSERT INTO $table
			(FirstName,LastName,Age,Sex) VALUES('$FirstName','$LastName','$Age','Sex')";
			$results = mysqli_query($link, $sqlquery);
			echo "Success ... added " . $FirstName . " " . $LastName . " to database. </br />";
		} else {
			echo "Sorry, that name is already in our database.<br />";
		}
	}
}

$trip1 = new Trip("Guam", 9);
$trip1->display();

$trip1->addTraveler("Leo");
$trip1->display();

$trip1->addTraveler("Eva");
$trip1->display();

$trip1->addTraveler("Rain");
$trip1->display();

$trip1->addTraveler("Justin");
$trip1->display();

$leo = new Traveler("Leo", "Babauta", "65", "M");
$leo->save();

?>