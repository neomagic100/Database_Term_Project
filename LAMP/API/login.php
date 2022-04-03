<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	include 'dbconfig.php';

	$inData = getRequestInfo();
	$db_server = "db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com";
	$db_user = "guest";
	$db_password = "uHHXEqnnVzpGawRj";
	$db_name = "UniversityEvents";
	$db_port = 25060;
	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    $sanitizedLogin = filter_var($inData["Login"], FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitizedPassword = filter_var($inData["Password"], FILTER_SANITIZE_SPECIAL_CHARS);
if( $conn->connect_error )
{
    returnWithError( $conn->connect_error );
}
else
{ 
	$user_status = "normal";
    $stmt = $conn->prepare("SELECT uid, user_id, user_name, user_pass FROM Users WHERE user_id=?");
    $stmt->bind_param("s", $sanitizedLogin);
    $stmt->execute();
    $result = $stmt->get_result();
	$stmt->close();
    if($result->num_rows == 0) returnWithError("Non-existent credentials, or typed wrong.");

    if( $row = $result->fetch_assoc()  )
    {   
		$uid = $row['uid'];
		$stmt = $conn->prepare("SELECT uid FROM Superusers WHERE uid=?");
		$stmt->bind_param('i', $uid);
		$stmt->execute();
		if($stmt->get_result()->num_rows == 1) $user_status = "super";
		$stmt->close();
		$stmt = $conn->prepare("SELECT uid FROM Admins WHERE uid=?");
		$stmt->bind_param("i", $uid);
		$stmt->execute();
		if ($stmt->get_result()->num_rows == 1) $user_status = "rso";
		$stmt->close();
        if(password_verify($sanitizedPassword, $row["user_pass"]))
        {
            returnWithInfo( $uid, $row['user_id'], $row['user_name'], $user_status);
        } else {
            returnWithError("Wrong Password");
        }
    }
    else
    {
        returnWithError($stmt->error);
    }
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

	function returnWithInfo( $uid, $user_id, $user_name, $user_status)
	{
		$retValue = '{"uid":' . $uid . ',"id":"' . $user_id . '","Name":"' . $user_name . '","userStatus":"' . $user_status . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>