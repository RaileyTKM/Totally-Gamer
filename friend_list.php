<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Where ARE MY FRIENDS?</title>
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
<div class="header">My Friends</div>
<!-- TODO: we can have unfriend button here -->
<?php
	//extract userid from login page
    session_save_path("/tmp");
	session_start();

	$conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
	if (!$conn) {
		$e = oci_error();   // For oci_connect errors do not pass a handle
		debug_to_console("Database is NOT Connected");
		trigger_error(htmlentities($e['message']), E_USER_ERROR);
	}
		debug_to_console("Database is Connected");

		// Parse sql

		// select i.User2ID, f.Nickname, f.Gender, f.Birthday, f.Role from isFriend i, 
		// UserID f where i.User1ID = 1 AND i.User2ID = f.ID order by nickname;
		$stid = oci_parse($conn, 'SELECT i.User2ID, f.Nickname, f.Gender, f.Birthday, f.Role FROM isFriend i, UserID f
		 WHERE i.User1ID = :userid AND i.User2ID = f.ID order by nickname');

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
		echo "<tr><th>ID</th><th>Nickname</th><th>Gender</th><th>Birthday</th><th>Role</th></tr>";
		// Fetch data

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] 
            . "</td></tr>"; //or just use "echo $row[0]" 
            // echo $row[0];
        }
		// Store userid to server and pass to next page
        echo "</table>";
		oci_free_statement($stid);
		OCILogoff($conn);

		function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
        
            echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
        }
?>




</body>
</html>
