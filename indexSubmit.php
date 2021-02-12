<?php
echo "Start file.<BR>";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$minTemp = $_POST["minTemp"];
$maxTemp = $_POST["maxTemp"];
$phoneNumber = $_POST["phoneNumber"];
$zipcode = $_POST["zipcode"];
//todo
//add pop (no rain, must have rain, etc)
//add email option

$minTemp = "30";
$maxTemp = "80";
$phoneNumber = "+15128108558";
$zipcode = "78704";

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

function sendText($message){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/AC7cbd83c363a1a7b042046ff55df09c17/Messages.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'To=%2B15128108558&Body='.$message.'&MediaUrl=https%3A%2F%2Fmedia.giphy.com%2Fmedia%2FPnUatAYWMEMvmiwsyx%2Fgiphy.gif&From=%2B19165072052',
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
}


//query API for the 5Day Forecast
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'api.openweathermap.org/data/2.5/forecast?zip=75248&appid=373648924e85014a499f922007faf90b',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'appid: 373648924e85014a499f922007faf90b'
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
        sendText($text);
        echo $text."<BR>";
    }
    else{
        $out = "No Nice Days in the next 5 days.";
        echo $out."<BR>";
        sendText($out);
    }



//sendText($message);
//echo "sendText called and finished.<BR>";

//echo $response;

echo "<BR>EOF.";
?>