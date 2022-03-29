<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    include 'dbconfig.php';
    $inData = getRequestInfo();
    $event_id = $inData['event_id'];
    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port); 
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $stmt = $conn->prepare("SELECT lid FROM Located_at WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lid = $result->fetch_assoc()['lid'];
    $stmt->close();
    $stmt = $conn->prepare("SELECT * FROM Location WHERE lid=?");
    $stmt->bind_param("i", $lid);
    $stmt->execute();
    $res = $stmt->get_result();
    $col = $res->fetch_assoc();
    returnWithInfo($col['lname'], $col['latitude'], $col['longitude'], $col['address']);
    
	function returnWithInfo( $lname, $lat, $long, $addr)
	{
		$retValue = '{"lname":"' . $lname . '","latitude":"' . $lat . '","longitude":"' . $long . 
                     '","addr":"'. $addr. '","error:""}';
		sendResultInfoAsJson( $retValue );
	}
    	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
    function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

?>