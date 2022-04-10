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
    if ($end - $start <= 0) {
        returnWithError("Please make sure event times are in the same date");
        die();
    }
    if (
        is_null($uid) || is_null($eventcat) || is_null($eventname) || is_null($descrip) || is_null($lname) || is_null($latitude) || is_null($long) || is_null($date) || is_null($start) ||
        is_null($end) || is_null($addr)) {
        returnWithError("hey uh put some values in here");
        die();
    }
    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        returnWithError("connection failed");
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Make sure user is Owner of RSO
            // Add location
            $stmt = $conn->prepare("INSERT INTO Location (lname, latitude, longitude, address) VALUES (?,?,?,?);");
            $stmt->bind_param("sdds", $lname, $latitude, $long, $addr);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if ($stmt->affected_rows == -1) {
                returnWithError($stmt->error);
                die();
            }
            // Get location id
            $lid = 0;
            if ($result == FALSE) {
                $stmt = $conn->prepare("SELECT lid FROM Location WHERE address = ?;");
                $stmt->bind_param("s", $addr);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $lid = $row["lid"];
                $stmt->close();
            } else {
                // Get location id
                $stmt = $conn->prepare("SELECT LAST_INSERT_ID();");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $lid = $row["LAST_INSERT_ID()"];
                $stmt->close();
            }

            // add it
            $stmt = $conn->prepare("CALL addRSOEvent( ?, ?,  ?, ?, ?,?,  ?);");
            $stmt->bind_param("ssssssi", $eventname, $eventcat, $descrip, $date, $start, $end, $lid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($stmt->affected_rows == -1) {
                returnWithError($stmt->error);
                die();
            }
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
            if ($stmt->affected_rows == -1) {
                returnWithError($stmt->error);
                die();
            }
            $stmt->close();

            //put in creates RSO Event
            $stmt = $conn->prepare("INSERT INTO Creates_RSOEvent (rso_id, event_id) VALUES (?,?)");
            $stmt->bind_param("ii", $rsoid, $eid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($stmt->affected_rows == -1) {
                returnWithError($stmt->error);
                die();
            }
            returnWithError("");
            $stmt->close();
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
?>