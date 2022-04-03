    <?php
        
        $inData = getRequestInfo();
        $sanitizedUniName = filter_var($inData["University"], FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedNumStudents = filter_var($inData["NumberStudents"], FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedAddress = filter_var($inData["Address"], FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizedDescription = filter_var($inData["Description"], FILTER_SANITIZE_SPECIAL_CHARS);


        $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port); 
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        else {
            $stmt = $conn->prepare("INSERT INTO University (uni_name, location, num_students, descrip) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $sanitizedUniName, $sanitizedNumStudents, $sanitizedAddress, $sanitizedDescription);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();
        }
        

        function getRequestInfo()
        {
            return json_decode(file_get_contents('php://input'), true);
        }
    ?>
    </body>

</html>