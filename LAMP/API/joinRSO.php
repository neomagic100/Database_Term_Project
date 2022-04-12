<?php
    include 'dbconfig.php';
    header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $inData = getRequestInfo();
    $uid = $inData['uid'];
    $rso_id = $inData['rsoid'];

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        // Add user to Member_of that rso
        $stmt = $conn->prepare("INSERT INTO Member_of (uid, rso_id) VALUES (?,?);");
        $stmt->bind_param("ii", $uid, $rso_id);
        $stmt->execute();
        $result = $stmt->get_result();
		returnWithError($stmt->error);
		$stmt->close();

        // Check how many users are currently in that RSO
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Member_of WHERE rso_id = ?;");
        $stmt->bind_param("i", $rso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $num_members = $row["COUNT(*)"];
        $stmt->close();

        // Check if there is an Owner of the RSO
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Owns WHERE rso_id = ?;");
        $stmt->bind_param("i", $rso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $ownerExists = $row["COUNT(*)"];
        $stmt->close();

        // If the new addition makes 5 people, and there is no owner of the RSO
        if ($num_members >= 5 && $ownerExists == 0)
        {
            // Check if current user is an Admin already
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Admins WHERE user_id = ?;");
            $stmt->bind_param("i", $uid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $isAdmin = $row["COUNT(*)"];
            $stmt->close();
            
            // If they are not an Admin, add them to admin table
            if ($isAdmin == 0)
            {
                // Add the new user to Admins
                $stmt = $conn->prepare("INSERT INTO Admins SELECT * FROM Users WHERE user_id = ?;");
                $stmt->bind_param("i", $uid);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }
            
            // Add the new Admin to Owns
            $stmt = $conn->prepare("INSERT INTO Owns (uid, rso_id) VALUES (?,?);");
            $stmt->bind_param("ii", $uid, $rso_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
                
        }  

        $conn->close();
    }

    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
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
?>