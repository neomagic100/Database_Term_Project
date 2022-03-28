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
    $uni = $inData["university"];
    
if( $conn->connect_error )
{
    returnWithError( $conn->connect_error );
}
else
{ 
    // How to get uid?
    $stmt = $conn->prepare("REPLACE INTO Affiliated_with (uni_id, uid) VALUES (?, uid)");
    $stmt->bind_param("s", $uni);
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    // Not Sure what to do here
    if($result->num_rows == 0) returnWithError("Error.");
    if( $row = $result->fetch_assoc()  )
    {   
        if(password_verify($sanitizedPassword, $row["user_pass"]))
        {
            returnWithInfo( $row['uid'], $row['user_id'], $row['user_name']);
        } else {
            returnWithError("Wrong Password");
        }
    }
    else
    {
        returnWithError($stmt->error);
    }
}