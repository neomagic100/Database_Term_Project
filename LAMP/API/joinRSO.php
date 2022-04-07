<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $uid = parseInt(localStorage.getItem('uid'));
    $inData = getRequestInfo();
    $rso_id = $inData['rsoid'];

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        $stmt = $conn->prepare("INSERT INTO Member_of (uid, rso_id) VALUES (?,?);");
        $stmt->bind_param("ii", $uid, $rso_id);
        $stmt->execute();
        $result = $stmt->get_result();
		returnWithError($stmt->error);
		$stmt->close();
        $conn->close();
    }
?>