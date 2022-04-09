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

    if (is_null($uid) || is_null($eventcat) || is_null($eventname) || is_null($descrip) || is_null($lname) || is_null($latitude) || is_null($long)) {
        returnWithError("hey uh put some values in here");
        die();
    }

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Make sure user is Owner of RSO
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Owns WHERE uid = ? AND rso_id = ?;");
        $stmt->bind_param("ii", $uid, $rsoid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $isOwner = $row["COUNT(*)"];
        $stmt->close();

        if ($isOwner > 0) {
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
            $stmt = $conn->prepare("CALL addRSOEvent( ?, ?,  ?, ?, ?,?,  ?);");
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

            //put in creates RSO Event
            $stmt = $conn->prepare("INSERT INTO Creates_PrivateEvent (rso_id, event_id) VALUES (?,?)");
            $stmt->bind_param("iii", $rsoid, $eid);
            $stmt->execute();
            $result = $stmt->get_result();
            returnWithError($result->error);
            $stmt->close();
            $conn->close()
        }
        
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