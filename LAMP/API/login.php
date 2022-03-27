<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

	$inData = getRequestInfo();
	$db_server = "db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com";
	$db_user = "guest";
	$db_password = "uHHXEqnnVzpGawRj";
	$db_name = "UniversityEvents";
	$db_port = 25060;


	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{ 
		$stmt = $conn->prepare("SELECT user_id, user_name, user_pass FROM Users WHERE user_id=?");
		$stmt->bind_param("s", $inData["Login"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{   
			if(password_verify($inData["Password"], $row["user_pass"]))
			{
				returnWithInfo( $row['user_id'], $row['user_name']);
			} else {
				returnWithError("Wrong Password");
			}
		}
		else
		{
			returnWithError("No Records Found");
		}

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
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
		exit();
	}

	function returnWithInfo( $user_id, $user_name)
	{
		$retValue = '{"id":"' . $user_id . '","Name":"' . $user_name . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>