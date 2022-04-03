    <?php
        
        $inData = getRequestInfo();

        $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port); 
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        else {
            $conn->prepare("INSERT INTO University (uni_name, location, num_students, descrip) VALUES (?,?,?,?)");
        }
        

        function getRequestInfo()
        {
            return json_decode(file_get_contents('php://input'), true);
        }
    ?>
    </body>

</html>