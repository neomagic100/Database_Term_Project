<?php
    header('Access-Control-Allow-Origin: http://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


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
	$conn = new mysqli("db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com", "doadmin", "HKOkbUAlyxXg5vKs", "UniversityEvents", 25060);
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        $salt = "321";
		$stmt = $conn->prepare("INSERT into Users (user_id, user_pass, salt,
							    user_name, email)
								VALUES(?,?,?,?,?);");
		$stmt->bind_param("sssss", $user_id, $user_pass, $salt, $user_name, $email);
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