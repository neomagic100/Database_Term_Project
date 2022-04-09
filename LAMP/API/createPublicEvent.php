<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $inData = getRequestInfo();
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
    if(is_null($uid) || is_null($eventcat) || is_null($eventname) || is_null($descrip) || is_null($lname) || is_null($latitude) || is_null($long)){ 
            returnWithError("hey uh put some values in here");
            die();
    }
    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Add location
        $stmt = $conn->prepare("INSERT INTO Location (lname, latitude, longitude, address) VALUES (?,?,?,?);");
        $stmt->bind_param("sdds", $lname, $latitude, $long, $addr);
        $stmt->execute();
        $result = $stmt->get_result();
		$stmt->close();
        // Get location id
        $stmt = $conn->prepare("SELECT LAST_INSERT_ID();");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $lid = $row["LAST_INSERT_ID()"];
        $stmt->close();
        // add it
        $stmt = $conn->prepare("CALL addPublicEvent( ?, ?,  ?, ?, ?,?,  ?);");
        $stmt->bind_param("ssssssi", $eventname, $eventcat, $descrip, $date, $start, $end, $lid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        //eid
        $stmt = $conn->prepare("SELECT LAST_INSERT_ID();");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $eid = $row["LAST_INSERT_ID()"];
        $stmt->close();
        //put in located at
        $stmt = $conn->prepare("INSERT INTO Located_at (event_id, lid) VALUES (?,?)");
        $stmt->bind_param("ii", $eid, $lid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        returnWithError($result->error);
        $conn->close();

    }
    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
