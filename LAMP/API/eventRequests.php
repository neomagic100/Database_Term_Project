<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    $db_server = "db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com";
	$db_user = "guest";
	$db_password = "uHHXEqnnVzpGawRj";
	$db_name = "UniversityEvents";
	$db_port = 25060;
	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT * FROM PublicEvents WHERE is_published=0");
		$stmt->execute();
        $result = $stmt->get_result();
		$searchCount = 0;
        $results = "";
		while($row = $result->fetch_assoc())
		{
            if( $searchCount > 0 )
			{
                $results .= ",";
			}
			$searchCount++;
			$results .= '{"EventName": "' . $row["event_name"] . '"}';
		}
        returnWithInfo($results);
        $stmt->close();
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
    function returnWithInfo( $results )
	{
		$retValue = '{"results":[' . $results . '], "error":""}';
		sendResultInfoAsJson( $retValue );
	}
