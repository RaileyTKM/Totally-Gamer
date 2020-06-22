<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>My Forums</title>
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
<div class="header">My Forums</div>


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

    displayForum();
?>

<form method="POST"> 
    <label for="type">Forum Type:</label>
    <select name="type">
        <?php
            global $conn; 
            $stid = oci_parse($conn, 'SELECT Name FROM Type');
            if (!$stid) {
                $e = oci_error($conn);
                debug_to_console($e);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $r = OCIExecute($stid, OCI_DEFAULT);
            if (!$r) {
                $e = oci_error($stid);
                debug_to_console($e);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
                echo "<option value='".$row[0]."'>".$row[0]."</option>"; 
            }
            oci_free_statement($stid);
        ?>
    </select>
    <label for="type">Forum Name:</label>
    <input type="text" required="required" name="name"></p>
	<input type="submit" value="Create New Forum" name="upload"></p>
</form>

<?php

    if (isset($_POST['upload'])) {
        $stid = oci_parse($conn, 'INSERT INTO Forum_category_creates VALUES (:name, :type, :userid, SYSDATE)');

        $userid = $_SESSION['userid'];
        $type = $_POST['type'];
        $name = $_POST['name'];

		// Set input

		$ba = array(':userid' => $userid, ':name' => $name, ':type' => $type);
		foreach ($ba as $key => $val) {
            oci_bind_by_name($stid, $key, $ba[$key]);
        }
        
		if (!$stid) {
			$e = oci_error($conn);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
        $r = OCIExecute($stid, OCI_DEFAULT);
        $s = OCIExecute($stid, OCI_DEFAULT);
		if (!$r) {
			$e = oci_error($stid);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $r = oci_commit($conn);
        if (!$r) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message']), E_USER_ERROR);
        }
        oci_free_statement($stid);
        header('Location: myForum_page.php');
	}
	
	if (isset($_GET['delete'])) {
		deleteForum();
	}

    OCILogoff($conn);

    function displayForum() {
        global $conn;

        $stid = oci_parse($conn, 'SELECT Name, Category, CreateDate FROM Forum_category_creates f
		WHERE CreatorID = :userid order by CreateDate DESC');

        $userid = $_SESSION['userid'];
		$ba = array(':userid' => $userid);
		foreach ($ba as $key => $val) {
			oci_bind_by_name($stid, $key, $ba[$key]);
		}

		if (!$stid) {

			$e = oci_error($conn);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = OCIExecute($stid, OCI_DEFAULT);

		if (!$r) {
			$e = oci_error($stid);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
	
		echo "<table>";
		echo "<tr><th>Forum Name</th><th>Category</th><th>Date Created</th></tr>";

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . 
            "</td><td><form method='get'><button type='summit' name='delete' value='".$row[0]."'>Delete</button></form></td></tr>"; 
        }

		echo "</table>";

        oci_free_statement($stid);
	}
	
	function deleteForum() {
		global $conn;
		$del = oci_parse($conn, 'DELETE FROM Forum_category_creates WHERE name=:name');

        $name = $_GET['delete'];
		$ba = array(':name' => $name);

		foreach ($ba as $key => $val) {
			oci_bind_by_name($del, $key, $ba[$key]);
		}

		if (!$del) {

			$e = oci_error($conn);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// Excute sql

		$r = OCIExecute($del, OCI_DEFAULT);

		if (!$r) {
			$e = oci_error($del);
			debug_to_console($e);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$r = oci_commit($conn);
        if (!$r) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message']), E_USER_ERROR);
		}
		oci_free_statement($del);
		OCILogoff($conn);
		header('Location: myForum_page.php');
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