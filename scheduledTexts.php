<?php 
//check if there are any schedules that meet the local forecasts

$servername = "localhost";
$username = "ReadSchedule";
include("READSCHEDULE.php");
$password = $READSCHEDULE_AUTH;

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

?>