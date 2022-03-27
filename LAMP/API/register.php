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

	$inData = getRequestInfo();
    $user_id = $inData["Login"];
    $user_pass = password_hash($inData["Password"], PASSWORD_ARGON2I);
    $user_name = $inData["Name"];
    $email = $inData["Email"];
	
    if($user_id == NULL) 
    {
        returnWithError("Empty user");
        exit();
    }
    if($user_pass == NULL){ 
        returnWithError("Empty pass");
        exit();
    }
	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("INSERT into Users (user_id, user_pass,
							    user_name, email)
								VALUES(?,?,?,?);");
		$stmt->bind_param("ssss", $user_id, $user_pass, $user_name, $email);
		$stmt->execute();
        $result = $stmt->get_result();
		returnWithError($stmt->error);
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
	}
	
?>