<?php
session_start();
require ('connection.php');

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
$json = "https://nominatim.openstreetmap.org/reverse?format=json&lat=".$_POST['lat']."&lon=".$_POST["lng"]."&zoom=16&addressdetails=1";
$jsondec = file_get_contents($json, false, $context);
$obj = json_decode($jsondec);

$address =  $obj->display_name;
$empId = $_SESSION["empId"];
$img = $_POST['image'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$notes = $_POST['notes'];
$type = $_POST['type'];
$date = timestamp;
  
if ($pdo->beginTransaction())
{
  try
  {
    //Save to db
    $stmt = $pdo->prepare("INSERT INTO location (empId, capturetype, lat, lng, address, notes, capturedate ) VALUES (?,?,?,?,?,?,?)");
    $stmt->bindParam(1,$empId, PDO::PARAM_INT);
    $stmt->bindParam(2,$type, PDO::PARAM_STR);
    $stmt->bindParam(3,$lat, PDO::PARAM_STR);
    $stmt->bindParam(4,$lng,PDO::PARAM_STR);
    $stmt->bindParam(5,$address,PDO::PARAM_STR);
    $stmt->bindParam(6,$notes,PDO::PARAM_STR);
    $stmt->bindParam(7,$date);
    $res = $stmt->execute();
    $lastId = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO images (locId, image) VALUES (?,?)");
    $stmt2->bindParam(1,$lastId);
    $stmt2->bindParam(2,$img);
    $res2 = $stmt2->execute();
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