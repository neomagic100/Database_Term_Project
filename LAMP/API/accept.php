<?php
header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
include 'dbconfig.php';
$inData = getRequestInfo();
$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    // Check for superuser access
    $stmt = $conn->prepare("SELECT uid FROM Superusers WHERE uid = ?;");
    $stmt->bind_param("i", $inData['uid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row == NULL) {
        echo alert('You do not have access to this function');
        $stmt->close();
        $conn->close();
        exit();
    }

    else {
        $stmt = $conn->prepare("UPDATE PublicEvents SET is_published=1 WHERE event_name=?");
        $stmt->bind_param("s", $inData['name']);
        $stmt->execute();
        $result = $stmt->get_result();
        returnWithInfo($result->error);
        $stmt->close();
        $conn->close();
    }
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

function returnWithError($err)
{
    $retValue = "";
    if (!empty($err)) {
        $ret = explode(' ', $err, 4);
        $retValue = '{"error":"' . $ret[0] . " " . $ret[1] . " " . $ret[2] . '"}';
    } else {
        $retValue = '{"error":"' . $err . '"}';
    }
    sendResultInfoAsJson($retValue);
}
function returnWithInfo($results)
{
    $retValue = '{"error":""}';
    sendResultInfoAsJson($retValue);
}
