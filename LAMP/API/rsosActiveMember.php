<?php
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	include 'dbconfig.php';

	$conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    $uid = parseInt(localStorage.getItem('uid'));

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } 
	else
	{
        $stmt = $conn->prepare("SELECT uid, rname, rtype FROM ActiveRSOs WHERE uid = ?;");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $results = array();

        while($row = $result->fetch_assoc())
		{
			array_push($results, array('uid' => $row["uid"],'RSOName' => $row["rname"], 'RSOType' => $row["rtype"]));
		}

        $stmt->close();
        $conn->close();

        returnWithInfo(json_encode($results));
    }

    function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

    function returnWithInfo( $results )
	{
		$retValue = '{"results":' . $results . ', "error":""}';
		sendResultInfoAsJson( $retValue );
	}

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
?>