<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	include 'dbconfig.php';
	$inData = getRequestInfo();
	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT * FROM PublicEventView");
		$stmt->execute();
        $result = $stmt->get_result();
		$searchCount = 0;
        $results = array();
		while($row = $result->fetch_assoc())
		{
			array_push($results, array('EventName' => $row["event_name"], 'Eventid' => $row["event_id"], 'Description' => $row['descrip'], 'EventDate' => $row['event_date'],
										'EventStart' => $row["event_start"], 'EventType' => "Public", "EventEnd" => $row["event_end"]));
		}
        $stmt->close();
		// Get Uni ID.
		$uni_id = 0;
		$stmt = $conn->prepare("SELECT uni_id FROM Affiliated_with WHERE uid = ?");
		$stmt->bind_param("i", $inData['uid']);
		$stmt->execute();
		$res = $stmt->get_result();
		$res = $res->fetch_assoc();
		$uni_id = $res['uni_id'];
		$stmt->close();

		//Get private events based on uni id.
		$stmt = $conn->prepare("SELECT * FROM PrivateEventView WHERE uni_id = ?");
		$stmt->bind_param("i", $uni_id);
		$stmt->execute();
		$res = $stmt->get_result();
		while($row = $res->fetch_assoc())
		{
			array_push($results, array(
			'EventName' => $row["event_name"], 'Eventid' => $row["event_id"], 'Description' => $row['descrip'], 'EventDate' => $row['event_date'],
			'EventStart' => $row["event_start"], 'EventType' => "Private", "EventEnd" => $row["event_end"]));
		}
		$stmt-> close();
		//Get RSO IDs
		$rso_id = 0;
		$stmt = $conn->prepare("SELECT rso_id FROM Member_of WHERE uid = ?");
		$stmt->bind_param("i", $inData['uid']);
		$stmt->execute();
		$res = $stmt->get_result();
		$rsoids = array();
		while($row = $res->fetch_assoc())
		{
			array_push($rsoids, $row['rso_id']);
		}
		$stmt->close();

		//Get RSO Events based on RSO id.
		foreach($rsoids as $rsoid)
		{
			$stmt = $conn->prepare("SELECT * FROM RSOEventView WHERE rso_id = ?");
			$stmt->bind_param("i", $rsoid);
			$stmt->execute();
			$res = $stmt->get_result();
			while ($row = $res->fetch_assoc()) {
				array_push($results, array(
					'EventName' => $row["event_name"], 'Eventid' => $row["event_id"], 'Description' => $row['descrip'], 'EventDate' => $row['event_date'],
					'EventStart' => $row["event_start"], 'EventType' => "RSO", "EventEnd" => $row["event_end"]
				));
			}
			$stmt->close();
		}
        returnWithInfo(json_encode($results), $uni_id);
        $conn->close();
	}
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = "";
		if(!empty($err))
		{
			$ret = explode(' ', $err, 4);
			$retValue = '{"error":"' . $ret[0] . " " . $ret[1] . " " . $ret[2] .'"}';
		} else {
			$retValue = '{"error":"' . $err . '"}';
		}
		sendResultInfoAsJson( $retValue );
	}
    function returnWithInfo( $results, $uni_id )
	{
		$retValue = '{"results":' . $results . ',"uniid":' .$uni_id. ', "error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>