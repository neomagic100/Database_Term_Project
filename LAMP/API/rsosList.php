<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $inData = getRequestInfo();
    $uid = $inData['uid'];
    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        $stmt = $conn->prepare("SELECT rso_id, rname, rtype FROM RSOs;");
        $stmt->execute();
        $result = $stmt->get_result();
        $rsos = array();
		while($row = $result->fetch_assoc())
		{
            array_push($rsos, array("RSOID" => $row["rso_id"], "RSOName" => $row["rname"], "RSOType" => $row["rtype"], "uid" => $uid));
		}
        $stmt->close();
        returnWithInfo(json_encode($rsos));
        $conn->close();
    }

    function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
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

?>