<?php
echo "Start file.<BR>";
$name = $_POST["name"];
$password = $_POST["password"];//anti-bot password

$servername = "localhost";
$username = "AddSchedule";
include("ADDSCHEDULE.php");
$addPass = $ADDSCHEDULE_AUTH;

if($name=="" && strtoupper($password)=="KRISTIN"){
    $minTemp = $_POST["minTemp"];
    $maxTemp = $_POST["maxTemp"];
    $phoneNumber = $_POST["phoneNumber"];
    $zipcode = $_POST["zipcode"];
    $wind = $_POST["wind"];
    $clouds = $_POST["clouds"];
    $pop = $_POST["pop"];
    $humidity = $_POST["humidity"];
    
    //count the number of preference schedules there are for this number
    //if there are less than five, insert
    //if there are five, offer to exchange these settings with another record.
}
else {
    echo "Nice try, spammer!<BR>";
}


echo "<BR>EOF.";
?>