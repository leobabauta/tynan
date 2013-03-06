<?php
error_reporting(E_ALL); 

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

	public function __construct($FirstName, $LastName, $Age, $Sex) {
  		$this->FirstName = $FirstName;
  		$this->LastName = $LastName;
  		$this->Age = $Age;
  		$this->Sex = $Sex;
 	}

  	public function save() {
  		// testing whether variables actually contain data
  		echo $FirstName . " is set<br />";
  		echo $LasttName . " is set<br />";
  		echo $Age . " is set<br />";
  		echo $Sex . " is set<br />";
 
  		// Database info and connection
  		$DBhost = "localhost";
		$DBuser = "root";
		$DBpass = "root";
		$DBName = "root_trips";
		$table = "travelers";
		$link = mysqli_connect($DBhost, $DBuser, $DBpass, $DBName);

		if (!$link) {
    		die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
		}
		echo 'Success... ' . mysqli_get_host_info($link) . "<br />";

  		// Insert into table
		$sqlquery = "INSERT INTO $table ('FirstName','LastName','Age','Sex') 
		VALUES('$FirstName','$LastName','$Age','$Sex')";

		echo "$sqlquery";
		$results = mysqli_query($sqlquery) or die(mysqli_error());

  		// Close connection
		mysqli_close($link);
	}
}

$trip1 = new Trip("Japan", 10);
$trip1->display();

$trip1->addTraveler("Leo");
$trip1->display();

$trip1->addTraveler("Eva");
$trip1->display();

$trip1->addTraveler("Tynan");
$trip1->display();

$leo = new Traveler("Leo", "Babauta", "65", "M");
$leo->save();

?>