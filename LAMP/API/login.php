<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/%27');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

$inData = getRequestInfo();

$sanitizedLogin = filter_var($inData["Login"], FILTER_SANITIZE_SPECIAL_CHARS);
$sanitizedPassword = filter_var($inData["Password"], FILTER_SANITIZE_SPECIAL_CHARS);
# $conn = new mysqli("db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com", "guest", "puHHXEqnnVzpGawRj", "UniversityEvents", 25060);
$conn = new mysqli("db-mysql-nyc3-02487-do-user-11025506-0.b.db.ondigitalocean.com", "doadmin", "HKOkbUAlyxXg5vKs", "UniversityEvents", 25060);
if( $conn->connect_error )
{
    returnWithError( $conn->connect_error );
}
else
{ 
    $stmt = $conn->prepare("SELECT user_id, user_name, user_pass FROM Users WHERE user_id=?");
    $stmt->bind_param("s", $sanitizedLogin);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0) returnWithError("Non-existent credentials, or typed wrong.");
    if( $row = $result->fetch_assoc()  )
    {   
        if(password_verify($sanitizedPassword, $row["user_pass"]))
        {
            returnWithInfo( $row['user_id'], $row['user_name']);
        } else {
            returnWithError("Wrong Password");
        }
    }
    else
    {
        returnWithError($stmt->error);
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