<?php 
//check if there are any schedules that meet the local forecasts

$servername = "localhost";
$username = "ReadSchedule";
include("READSCHEDULE.php");
$password = $READSCHEDULE_AUTH;

// Create connection
$conn = new mysqli($servername, $username, $password, "NextNiceDay");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully<BR>";

//Brute Force Algorithm:
//Great for 10 users, horrible for 10 million users
//1. for each user, store their preferences
//2. pull the 40 data points
//3A. for each data point, compare against each user's min max
//3B. store the day in each user's running list of NiceDays

//Since this is the test case, let's go 1 schedule / phone number


echo "Starting query.<BR>";
/*
$queryPref = "select * from Schedules";
$resultPref = mysql_query($queryPref);
$num_rowsPref = mysql_num_rows($resultPref);
echo "$num_rowsPref rows returned<BR>";
for($k=0;$k<$num_rowsPref;$k++){
    $rowPref = mysql_fetch_array($resultPref);
    $scheduleNumber = $rowPref["scheduleNumber"];
    echo "Schedule number is $scheduleNumber<BR>";
 * //YIKES! Need to do things a bit differently in PHP7!
}
*/

if ($resultPref = $conn->query("SELECT * FROM Schedules")) {

    $num_rowsPref = mysqli_num_rows($resultPref);
    //$num_rowsPref = $resultPref->num_rows;
    echo "$num_rowsPref rows returned<BR>";
    for($k=0;$k<$num_rowsPref;$k++){
        $rowPref = mysqli_fetch_assoc($resultPref);
        $scheduleNumber = $rowPref["scheduleNumber"];
        $phoneNumber = $rowPref["phoneNumber"];
        $zipcode = $rowPref["zipcode"];
        $minTemp = $rowPref["minTemp"];
        $maxTemp = $rowPref["maxTemp"];
        $wind = $rowPref["wind"];
        $clouds = $rowPref["clouds"];
        $pop = $rowPref["pop"];
        $humidity = $rowPref["humidity"];
        
        //echo "$scheduleNumber $phoneNumber $zipcode $minTemp $maxTemp $wind $clouds $pop $humidity<BR>"; //DEBUG
    }
    
    $result->close();
}

echo "<BR>EOF.<BR>";
?>