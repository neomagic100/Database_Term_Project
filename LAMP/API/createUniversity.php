<html>
    <head> 
		<link href="css/styles.css" rel="stylesheet">	
		<script type="text/javascript" src="js/main.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <?php header('Access-Control-Allow-Origin: https://www.goldenknights.systems/');
                header("Access-Control-Allow-Credentials: true");
                header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
                header('Access-Control-Max-Age: 1000');
                header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
                include 'dbconfig.php'; ?>
	</head>

    <body>
        <form method="post">
        <input type="text" id="createUniName" placeholder="UNIVERSITY NAME" /><br /> 
        <br/>
        <input type="text" id="createNumStudents" placeholder="ESTIMATED NUMBER OF STUDENTS" /><br /> 
        <br/>
        <input type="text" id="createUniAddr" placeholder="ADDRESS" /><br /> 
        <br/>
        <input type="text" id="createUniDescription" placeholder="DESCRIPTION"/><br />
        <br/>
        <button type="button" id="createUniButton" class="buttons4" onclick="createUniversity()">Create University</button>
        <div class="resdiv">
            <span id="resultCreate" class="result"></span>
        </div>
        </form>

    <?php
        
        $inData = getRequestInfo();

        $conn = new mysqli($db_server, $db_user, $db_password, $db_name, $db_port); 
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }   
        

        function getRequestInfo()
        {
            return json_decode(file_get_contents('php://input'), true);
        }
    ?>
    </body>

</html>