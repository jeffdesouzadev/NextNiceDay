<?php
echo "Start file.<BR>";
$name = $_POST["name"];
if($name==""){
    $minTemp = $_POST["minTemp"];
    $maxTemp = $_POST["maxTemp"];
    $phoneNumber = $_POST["phoneNumber"];
    $zipcode = $_POST["zipcode"];
    include("TWILIO_AUTH.php");
    include("OPENWEATHER_AUTH.php");
    //$TWILIO_AUTH = 
    
}
else {
    echo "Nice try, spammer!<BR>";
}
//todo
//add pop (no rain, must have rain, etc)
//add email option

//$minTemp = "30";
//$maxTemp = "80";
//$phoneNumber = "+15128108558";
//$zipcode = "78704";

date_default_timezone_set('UTC');
function epoch2Date($epoch){
    $epoch = $epoch-21600;
    return date("D,m/d @ha",$epoch);
}

function formatPOP($popFraction, $temp){
    $pop = $popFraction*100;
    $pop = $pop."%";
    if($temp>=32)
        return "RAIN:$pop";
    else
        return "SNOW:$pop";
}

function sendText($phoneNumber, $message){
global $TWILIO_SID;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$TWILIO_SID.'/Messages.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'To=%2B1'.$phoneNumber.'&Body='.$message.'&MediaUrl=https%3A%2F%2Fmedia.giphy.com%2Fmedia%2FPnUatAYWMEMvmiwsyx%2Fgiphy.gif&From=%2B19165072052',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic QUM3Y2JkODNjMzYzYTFhN2IwNDIwNDZmZjU1ZGYwOWMxNzpjZWRjYmE3ZTZhMWEzODQxNzdjYmFhZmU4ZWMyZWQzYQ==',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
if($response)
    echo "I have tried to send the message.<BR>";
else
    echo "something went wrong.<BR>";

//print_r($response);
}


//query API for the 5Day Forecast
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'api.openweathermap.org/data/2.5/forecast?zip=75248&appid='.$OPENWEATHER_AUTH,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'appid: '.$OPENWEATHER_AUTH
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$obj = json_decode($response);

$niceDays = array();

$count = $obj->cnt;
echo "=Projected temperatures=<BR>";
for($k=0;$k<$count;$k++){
    $time = $obj->list[$k]->dt;
    $time = epoch2Date($time);
    $temp = $obj->list[$k]->main->temp;
    $dtTime = $obj->list[$k]->dt_txt;
    $pop = $obj->list[$k]->pop;
    $f=9/5*($temp-273.15)+32;

    $out = " $time [".formatPOP($pop, $f)."]: ".round($f, 1)."F%0a";
    //echo $out;
    if($f>=$minTemp && $f<=$maxTemp)
        if(!in_array($out, $niceDays))
                array_push($niceDays, $out);
}

    if(count($niceDays)>0){
        $text = "";
        for($k=0;$k<count($niceDays);$k++)
            $text .= $niceDays[$k];        
        sendText($phoneNumber, $text);
        echo $text."<BR>";
    }
    else{
        $out = "No Nice Days ($minTemp - $maxTemp) in the next 5 days for $zipcode.";
        echo $out."<BR>";
        sendText($phoneNumber, $out);
    }



//sendText($message);
//echo "sendText called and finished.<BR>";

//echo $response;

echo "<BR>EOF.";
?>