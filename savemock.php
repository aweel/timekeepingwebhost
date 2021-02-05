<?php
session_start();
require('connection.php');

//for db time -- use time now
date_default_timezone_set('Asia/Manila');
define("timestamp",date("Y-m-d H:i:s", time()));

//For nominatim.openstreetmap.org fake http request
ini_set('allow_url_fopen', 1);

$context = stream_context_create(
  array('http' => array('user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:82.0',),
));

//Reverse Geocoding to get location address using json decoding
//Note: Make a fallback in case nominatim is no longer available
//$json = "https://nominatim.openstreetmap.org/reverse?format=json&lat=".$_POST['lat']."&lon=".$_POST["lng"]."&zoom=16&addressdetails=1";
//$jsondec = file_get_contents($json, false, $context);
//$obj = json_decode($jsondec);

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$empId = $obj['u'];
$date = $obj['d'];
$mocktype = $obj['t'];

if ($pdo->beginTransaction())
{
    try
    {
      //Save to db
      $stmt = $pdo->prepare("INSERT INTO mock (empId, date, mock) VALUES (?,?,?)");
      $stmt->bindParam(1,$empId);
      $stmt->bindParam(2,$date);
      $stmt->bindParam(3,$mocktype);
      $res = $stmt->execute();
      $pdo->commit();
    }
    catch (PDOException $e)
    {
      if ($pdo->inTransaction())
      {
        $pdo->rollBack();
      }
      if ($e->getCode() == 1062) {
      // Take some action if there is a key constraint violation, i.e. duplicate name
        printf("awtsuu ".$e);
      } else {
        printf("huhu ".$e);
        throw $e;
      }
    }
}