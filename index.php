<?php
require_once 'mysql.php';

class Traveler {
	// Properties of a traveler
	// Note: UserID isn't necessary as it's auto-incremented
	public $firstName;
	public $lastName;
	public $age;
	public $sex;

	public function __construct($first, $last, $age = null, $sex = null) {
		$this->firstName = $first;
		$this->lastName = $last;
		if (isset($age)) {
			$this->age = $age;
		}
		if (isset($sex)) {
			$this->sex = $sex;
		}
	}

	public function save() {
		global $link;
		// make variables from included mysql.php accessible to this function

		// Escape strings
		$firstName = mysqli_real_escape_string($link, $this->firstName);
		$lastName = mysqli_real_escape_string($link, $this->lastName);
		$age = mysqli_real_escape_string($link, $this->age);
		$sex = mysqli_real_escape_string($link, $this->sex);

		// Insert into table only if not duplicate
		If (!$this->dupeCheck()) {
			$sqlquery = "INSERT INTO travelers
			(FirstName,LastName,Age,Sex) VALUES('"
				. $firstName . "','"
				. $lastName . "','"
				. $age . "','"
				. $sex . "')";
		$results = mysqli_query($link, $sqlquery);
			echo "Success ... added " . $firstName . " " . $lastName . " to database. </br />";
		} else {
			echo "Sorry, " . $this->firstName . " " . $this->lastName . " is already in our database.<br />";
		}
	}

	public function dupeCheck() {
		global $link;

		// checks to see if there's already a record with same first & last name
		$dupeSql = "SELECT * FROM travelers WHERE firstName = '"
		. $this->firstName . "'
		AND lastName = '" . $this->lastName . "'";

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
		$result = mysqli_query($link,"SELECT * FROM travelers 
			WHERE FirstName = '"
			. $this->firstName . "'
			AND LastName = '" . $this->lastName . "'");

		// next puts the result of the query into an array, then into individual variables
		$resultArray = mysqli_fetch_array($result);
		$this->firstName = $resultArray['FirstName'];
		$this->lastName = $resultArray['LastName'];
		$this->age = $resultArray['Age'];
		$this->sex = $resultArray['Sex'];
	}

	public function __toString() {
		// change sex to full word
		if ($this->sex == 'M') {
			$fullSex = 'Male';
		} elseif ($this->sex == 'F') {
			$fullSex = 'Female';
		}

		// then create string to return when class is echoed as a string
		return $this->firstName . " " . $this->lastName . ", age " . $this->age . ", " . $fullSex . "<br />";
	}

}

class Trip {
	// Properties of a trip
	// Note: UserID isn't necessary as it's auto-incremented
	public $departureCity;
	public $destinationCity;
	public $startDate;
	public $endDate;

	public function __construct($departureCity, $destinationCity, $startDate = null, $endDate = null) {
		$this->departureCity = $departureCity;
		$this->destinationCity = $destinationCity;
		if (isset($startDate)) {
			$this->startDate = $startDate;
		}
		if (isset($endDate)) {
			$this->endDate = $endDate;
		}
	}

	public function save() {
		global $link;

		// Escape strings
		$departureCity = mysqli_real_escape_string($link, $this->departureCity);
		$destinationCity = mysqli_real_escape_string($link, $this->destinationCity);
		$startDate = mysqli_real_escape_string($link, $this->startDate);
		$endDate = mysqli_real_escape_string($link, $this->endDate);

		// Insert into table only if not duplicate
		If (!$this->dupeCheck()) {
			$sqlquery = "INSERT INTO trips
			(DepartureCity,DestinationCity,StartDate,EndDate) VALUES('"
				. $departureCity . "','"
				. $destinationCity . "','"
				. $startDate . "','"
				. $endDate . "')";			$results = mysqli_query($link, $sqlquery);
			echo "Success ... added " . $departureCity . " to " . $destinationCity . " trip to our database. </br />";
		} else {
			echo "Sorry, my friend, " . $departureCity . " to " . $destinationCity . " trip is already in our database.<br />";
		}
	}

	public function dupeCheck() {
		global $link;

		// checks to see if there's already a record with same first & last name
		$dupeSql = "SELECT * FROM trips WHERE DepartureCity = '"
		. $this->departureCity . "'
		AND DestinationCity = '" . $this->destinationCity . "'";

		$result = mysqli_query($link, $dupeSql);
		$dupeCount = mysqli_num_rows($result);

		if ($dupeCount > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function load() {
		// function looks up name in trip database, loads data into variables
		global $link;

		// first looks up the row that has a match between departureCity and destinationCity
		$result = mysqli_query($link,"SELECT * FROM trips 
		WHERE DepartureCity = '"
			. $this->departureCity . "'
			AND DestinationCity = '" . $this->destinationCity . "'");

		// next puts the result of the query into an array, then into individual variables
		$resultArray = mysqli_fetch_array($result);
		$this->departureCity = $resultArray['DepartureCity'];
		$this->destinationCity = $resultArray['DestinationCity'];
		$this->startDate = $resultArray['StartDate'];
		$this->endDate = $resultArray['EndDate'];
	}

	public function __toString() {
		// convert date format
		$old_date_timestamp = strtotime($this->startDate);
		$newStartDate = date('m/d', $old_date_timestamp);
		$old_date_timestamp2 = strtotime($this->endDate);
		$newEndDate = date('m/d', $old_date_timestamp2);

		// create string to return when class is echoed as a string
		return "Trip data: " . $this->departureCity . " to " . $this->destinationCity . " from " . $newStartDate . " to " . $newEndDate . "<br />";
	}

	public function addTraveler($addedTraveler) {
		// to associate traveler with current trip, adds traveler to addTraveler table
		global $link;

		// Escape strings - put traveler and trip data into variables
		$firstName = mysqli_real_escape_string($link, $addedTraveler->firstName);
		$lastName = mysqli_real_escape_string($link, $addedTraveler->lastName);
		$departureCity = mysqli_real_escape_string($link, $this->departureCity);
		$destinationCity = mysqli_real_escape_string($link, $this->destinationCity);

		// look up traveler ID from traveler table
		$result = mysqli_query($link,"SELECT * FROM travelers 
		WHERE FirstName = '"
			. $firstName . "'
			AND LastName = '" . $lastName . "'");

		// put traveler's UserId into travelerID variable
		$resultArray = mysqli_fetch_array($result);
		$travelerID = $resultArray['UserId'];

		// look up trip ID from trip table
		$result = mysqli_query($link,"SELECT * FROM trips 
		WHERE DepartureCity = '"
			. $departureCity . "'
			AND DestinationCity = '" . $destinationCity . "'");

		// put trip's TripID into tripID variable
		$resultArray = mysqli_fetch_array($result);
		$tripID = $resultArray['TripId'];

		// Insert trip and traveler IDs into traveler_trip table
		$sqlquery = "INSERT INTO traveler_trip
			(tripID,travelerID) VALUES('"
				. $tripID . "','"
				. $travelerID . "')";			$results = mysqli_query($link, $sqlquery);
		echo "Success ... added " . $firstName . " " . $lastName . " to the trip from " . $departureCity . " to " . $destinationCity . " in our database. </br />";
	}

	public function showSummary() {
		// function displays relevant info about a trip, including trip dates, traveler info, and average age
		global $link;

		// Escape strings - put trip data into variables
		$departureCity = mysqli_real_escape_string($link, $this->departureCity);
		$destinationCity = mysqli_real_escape_string($link, $this->destinationCity);

		// look up trip info from trip table
		$result = mysqli_query($link,"SELECT * FROM trips 
		WHERE DepartureCity = '"
			. $departureCity . "'
			AND DestinationCity = '" . $destinationCity . "'");

		// put trip's TripID and dates variables
		$resultArray = mysqli_fetch_array($result);
		$tripID = $resultArray['TripId'];
		$startDate = $resultArray['StartDate'];
		$endDate = $resultArray['EndDate'];

		// look up associated travelers from traveler_trip table
		$result = mysqli_query($link,"SELECT travelerID FROM traveler_trip 
		WHERE tripID = '"
			. $tripID . "'");

		// put travelerIDs into variables
		// NOTE: NEED TO FIGURE OUT HOW TO PUT MULTIPLE TRIPS INTO MULTIPLE VARIABLES
		while ($row = mysql_fetch_assoc($result)) {
			$travlerIDs[] = $row['id'];
			echo $travelerIDs['id'];
		}

		// convert date format
		$old_date_timestamp = strtotime($startDate);
		$newStartDate = date('m/d', $old_date_timestamp);
		$old_date_timestamp2 = strtotime($endDate);
		$newEndDate = date('m/d', $old_date_timestamp2);

		echo $destinationCity . " trip from " . $newStartDate . " - " . $newEndDate . " with travelers:<br />";
		// echo $firstName . " " . $lastName . ", " . $fullSex . ", age " . $age . "<br />";

	}
}

$japanTrip = new Trip("San Francisco","Tokyo","2013-03-26","2013-04-07");
$japanTrip->save();
$leo = new Traveler("Hiyao", "Miyazaki", "80", "M");
$leo->save();

echo $japanTrip;
echo $leo;

$japanTrip->addTraveler($leo);
$japanTrip->showSummary();

?>