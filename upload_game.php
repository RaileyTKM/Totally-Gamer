<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Upload New Game</title>
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
<div class="header">Upload New Game</div>


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
?>

<form method="post">
  <label for="name">Game Name:</label>
  <input type="text" required="required" name="name"><br><br>
  <label for="price">Price/CA$:</label>
  <input type="number" step="0.01" required="required" name="price"><br><br>

  <label for="type">Game Type:</label>
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
  </select><br><br>

  <button type="submit" class="btn btn-primary btn-block btn-large" name="upload">Upload</button>
</form>

<?php

    if (isset($_POST['upload'])) {
        createGame();
    }

    OCILogoff($conn);

    function createGame() {
        global $conn;

        $stid = oci_parse($conn, 'INSERT INTO Game_uploads VALUES (GID_generate.nextval, :userid, :gname, 0, :price, SYSDATE)');
        $isof = oci_parse($conn, 'INSERT INTO isOf VALUES (GID_generate.currval, :gtype)');

        $userid = $_SESSION['userid'];
        $gtype = $_POST['type'];
        $gname = $_POST['name'];
        $price = $_POST['price'];

		// Set input

		$ba = array(':userid' => $userid, ':gname' => $gname, ':price' => $price, ':gtype' => $gtype);
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
        oci_free_statement($isof);
        OCILogoff($conn);
        header('Location: game_created.php');
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