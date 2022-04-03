<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    include 'dbconfig.php';
    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $inData = getRequestInfo();
    $event_id = $inData['eid'];
    $uid = $inData['uid'];
    $cmnt = $inData['cmnt'];
    $rating = $inData['rating'];
    $stmt = $conn->prepare("INSERT into Comments (uid, event_id, cmnt, rating)
                            VALUES(?,?,?,?)");
    $stmt->bind_param("iisi", $uid, $event_id, $cmnt, $rating);
    $stmt->execute();
    returnWithInfo($stmt->error);
    $stmt->close(); 
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithInfo($results)
    {
        $res = explode(' ', $results, 3);
        $retValue = '{"error":"'. $res[0] . ' '. $res[1] . '"}';
        sendResultInfoAsJson($retValue);
    }
