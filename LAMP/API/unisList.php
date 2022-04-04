<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        $stmt = $conn->prepare("SELECT uni_id, uni_name FROM University;");
        $stmt->execute();
        $result = $stmt->get_result();
        $unis = array();
		while($row = $result->fetch_assoc())
		{
            array_push($unis, array("UniversityID" => $row["uni_id"], "UniversityName" => $row["uni_name"]));
		}
        returnWithInfo(json_encode($unis));
        $stmt->close();
        $conn->close();

        
    }
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
    function returnWithInfo( $results )
	{
		$retValue = '{"results":' . $results . ', "error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>