    <?php
    include 'dbconfig.php';
        $inData = getRequestInfo();
        $sanitizedUniName = filter_var($inData["University"], FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedNumStudents = $inData["NumberStudents"];
        $sanitizedAddress = filter_var($inData["Address"], FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedDescription = filter_var($inData["Description"], FILTER_SANITIZE_SPECIAL_CHARS);
        $suID = $inData["uid"];


        $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port); 
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        else {
            // Check to see if user is superuser with access
            $stmt = $conn->prepare("SELECT uid FROM Superusers WHERE uid = ?;");
            $stmt->bind_param("i", $suID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if (row == NULL) {
                echo alert('You do not have access to this function');
                $stmt->close();
                $conn->close();
                exit();
            }

            // Insert user into university
            $stmt = $conn->prepare("INSERT INTO University (uni_name, location, num_students, descrip) VALUES (?,?,?,?)");
            $stmt->bind_param("ssis", $sanitizedUniName, $sanitizedAddress, $sanitizedNumStudents, $sanitizedDescription);
            $stmt->execute();
            $result = $stmt->get_result();
            returnWithInfo($result->error);
            $stmt->close();

            // get last university id added
            $stmt = $conn->prepare("SELECT LAST_INSERT_ID();");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $uniID = $row["LAST_INSERT_ID()"];
            $stmt->close();

            // Add to creates_profile table in DB
            $stmt = $conn->prepare("INSERT INTO Creates_Profile(uid, uni_id) VALUES (?,?)");
            $stmt->bind_param("ii", $suID, $uniID);
            $stmt->execute();
            $result = $stmt->get_result();
            returnWithInfo($result->error);
            $stmt->close();

            $conn->close();
        }
    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }
    function returnWithInfo($results)
    {
        $retValue = '{"error":"' . $results . '"}';
        sendResultInfoAsJson($retValue);
    }
        function getRequestInfo()
        {
            return json_decode(file_get_contents('php://input'), true);
        }
    ?>
