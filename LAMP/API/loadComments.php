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
    $event_id = $inData['event_id'];
    $stmt = $conn->prepare("SELECT uid, timestmp, cmnt, rating FROM Comments WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $res = $stmt->get_result(); 
    $results = array();
    $stmt->close(); 
    $searchCount = 0;
    while($row = $res->fetch_assoc())
    {
        // if ($searchCount > 0) {
        //     $results .= ",";
        // }
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE uid= ?");
        $stmt->bind_param("i", $row['uid']);
        $stmt->execute();
        $name = $stmt->get_result();
        $name = $name->fetch_assoc();
        $stmt->close();
        array_push($results, (array('user' => $name['user_id'], 'time'=> $row['timestmp'], 'cmnt' => $row['cmnt'], 'rating'=> $row['rating'])));
        //$searchCount++;
        // $results .= '{"EventName": "' . $row["event_name"]  . '", "Eventid":' . $row["event_id"] . ', "Description": "' . $row["descrip"] . '", "EventDate": "' .
        // $row["event_date"]  . '", "EventStart": "' . $row["event_start"] . '", "EventType":"Public", "EventEnd": "' . $row["event_end"] . '"}';
    }
    returnWithInfo(json_encode($results));
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
        $retValue = '{"comments":' . $results . ', "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>