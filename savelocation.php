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
//TODO Note: Make a fallback in case nominatim is no longer available
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
//Convert timestamp to time
$timestamp = strtotime(timestamp);
$late = $type == "IN" ? date('H:i:s', $timestamp) : null;

//TODO Jomel 02192021 Finish this code for double entry notif
try
{
    $query = $pdo->prepare(" Select id,capturetype,capturedate from location where id = (select MAX(id) from location where empId = ?)");
    $query->execute(array($empId));
    $res = $query->fetch(PDO::FETCH_ASSOC);

    $userdbid=$res["id"];
    $userdbtype=$res["capturetype"];
    $userdbdate=$res["capturedate"];
    $date1 = date('Y-m-d', strtotime($userdbdate));
    $date2 = date('Y-m-d');

    if($userdbtype === "IN"){
        if ($type === "IN" && $date1 === $date2) {
            $_SESSION["err1"] =  "Multiple In \r\n Continue?";
            return;
        }

        if($type === "IN" && $date1 < $date2) {
            echo "Multiple In \r\n Continue?";
            return;
        }
    }elseif ($userdbtype === "OUT"){

        if($type === "OUT" && $date1 === $date2){
            echo "Multiple Out \r\n Continue?";
            return;
        }
    }elseif ($userdbtype === "TRA"){
        if ($type === "OUT" && $date1 < $date2) {
            echo "Multiple Out \r\n Continue?";
            return;
        }

        if($type === "IN" && $date1 < $date2) {
            echo "Multiple Out \r\n Continue?";
            return;
        }

        echo "lul";
    }
}catch (Exception $exception)
{
    echo "Error ".$exception;
}

if ($pdo->beginTransaction())
{
    try
    {
      //Save to db
      $stmt = $pdo->prepare("INSERT INTO location (empId, capturetype, lat, lng, address, notes, capturedate, late ) VALUES (?,?,?,?,?,?,?,?)");
      $stmt->bindParam(1,$empId);
      $stmt->bindParam(2,$type);
      $stmt->bindParam(3,$lat);
      $stmt->bindParam(4,$lng);
      $stmt->bindParam(5,$address);
      $stmt->bindParam(6,$notes);
      $stmt->bindParam(7,$date);
      $stmt->bindParam(8,$late);
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