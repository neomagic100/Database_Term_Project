<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $uid = parseInt(localStorage.getItem('uid'));

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Get RSOs from User's University
        $stmt = $conn->prepare("SELECT A.uni_id FROM Owns O, Affiliated_with A WHERE O.uid = A.uid;");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $uni_id = $row['uni_id'];
        $stmt->close();

        $stmt = $conn->prepare("SELECT DISTINCT rso_id, rname, rtype FROM RSOs R, Affiliated_with A WHERE A.uni_id = ? AND A.uid = ?;");
        $stmt->bind_param("ii", $uni_id, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $rsos = array();
		while($row = $result->fetch_assoc())
		{
            array_push($rsos, array("RSOID" => $row["rso_id"], "RSOName" => $row["rname"], "RSOType" => $row["rtype"]));
		}
        returnWithInfo(json_encode($rsos));
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