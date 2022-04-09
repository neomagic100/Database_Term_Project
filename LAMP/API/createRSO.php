<?php
	include "dbconfig.php";
	header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $inData = getRequestInfo();
    $rsoName = $inData["RSOName"];
    $rsoType = $inData["RSOType"];
    $uid = $inData["uid"];

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Add new RSO
        $stmt = $conn->prepare("INSERT INTO RSOs (rname, rtype) VALUES (?,?);");
        $stmt->bind_param("ss", $rsoName, $rsoType);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($stmt->affected_rows == -1) {
            returnWithError($stmt->error);
            die();
        }
        // get last RSO id added
        $stmt = $conn->prepare("SELECT LAST_INSERT_ID();");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $rsoID = $row["LAST_INSERT_ID()"];
        $stmt->close();

        // Join new RSO
        $stmt = $conn->prepare("INSERT INTO Member_of (uid, rso_id) VALUES (?,?);");
        $stmt->bind_param("ii", $uid, $rsoID);
        $stmt->execute();
        $result = $stmt->get_result();
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