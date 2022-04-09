<?php
	include "dbconfig.php";
	header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $inData = getRequestInfo();
    $rsoid = $inData["RSOID"];
    $uid = $inData['uid'];
    $eventname = $inData['eventname'];
    $eventcat = $inData['eventcat'];
    $descrip = $inData['descrip'];
    $date = $inData['date'];
    $start = $inData['start'];
    $end = $inData['end'];
    $lname = $inData['lname'];
    $latitude = $inData['lat'];
    $long = $inData['long'];
    $addr = $inData['address'];
    $uniid = $inData['uniid'];

    if (is_null($uid) || is_null($eventcat) || is_null($eventname) || is_null($descrip) || is_null($lname) || is_null($latitude) || is_null($long)) {
        returnWithError("hey uh put some values in here");
        die();
    }

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);


?> 