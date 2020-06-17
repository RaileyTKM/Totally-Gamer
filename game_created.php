<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>My Uploads</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<link rel="stylesheet" href="navStyle.css">
	<style>
		html {
			background: url(UBC.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
		body {
		text-align: center;
		}
		table { display: inline-block; text-align: left; font-size:20px; }
		.header{
		margin-top: 1%;
		font-size: 400%;
		text-align: center;
		margin-bottom: 3%;
		}
		.tname{
			margin : 1%;
			text-align: center;
			font-size: 200%;
		}
		table, th, td {
		border: 1px solid black;
		}
	</style>
</head>
<body>
<!--Navigation bar-->
<div id="nav-placeholder">

</div>

<script>
$(function(){
  $("#nav-placeholder").load("navbar.html");
});
</script>
<!--end of Navigation bar-->
<div class="header">My Uploads</div>


<!-- TODO(optional): Rate a Game if haven't -->
<?php
    session_save_path("/tmp");
    session_start();

    $conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
    if (!$conn) {
        $e = oci_error();   // For oci_connect errors do not pass a handle
        debug_to_console("Database is NOT Connected");
        trigger_error(htmlentities($e['message']), E_USER_ERROR);
    }
    debug_to_console("Database is Connected");

    displayGame();
?>

<form method="POST"> 
	<input type="submit" value="Upload New Game" name="upload"></p>
</form>

<?php

    if (isset($_POST['upload'])) {
        OCILogoff($conn);
		header('Location: upload_game.php');
    }

    OCILogoff($conn);

    function displayGame() {
        global $conn;

        $stid = oci_parse($conn, 'SELECT g.GID, g.Name, g.Price, g.UploadDate, g.Rating
        FROM Game_uploads g
		WHERE g.Devid = :userid order by g.UploadDate');

        $userid = $_SESSION['userid'];

		// Set input

		$ba = array(':userid' => $userid);

		foreach ($ba as $key => $val) {
			oci_bind_by_name($stid, $key, $ba[$key]);
		}

		if (!$stid) {

			$e = oci_error($conn);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// Excute sql

		$r = OCIExecute($stid, OCI_DEFAULT);

		if (!$r) {
			$e = oci_error($stid);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
	
		echo "<table>";
		echo "<tr><th>Game ID</th><th>Name</th><th>Price/CA$</th><th>Upload Date</th><th>Rating</th></tr>";

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] 
            . "</td></tr>"; 
        }

		echo "</table>";

        oci_free_statement($stid);
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
?>

</body>
</html>