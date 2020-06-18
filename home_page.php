<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Home</title>
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
		.tbody{
			margin : 0%;
			text-align: center;
			font-size: 120%;
			color: #111542;
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
<?php
	//extract userid from login page
    session_save_path("/tmp");
	session_start();
	?>

<div class="header">Hello  <?php echo $_SESSION['userName'];?> </div>
<div class="header">Welcome to Totally Gamer</div>

<h1>Popular Games</h1>
<div class="tbody">These games are purchased by EVERY player</div>
<?php
	$conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
	if (!$conn) {
		$e = oci_error();   // For oci_connect errors do not pass a handle
		debug_to_console("Database is NOT Connected");
		trigger_error(htmlentities($e['message']), E_USER_ERROR);
	}
	debug_to_console("Database is Connected");

	$sql = "SELECT g.Name
	FROM Game_uploads g
	WHERE NOT EXISTS
		(SELECT * FROM Player u
		WHERE NOT EXISTS
		(SELECT p.GID 
		FROM Purchases_profits_detail p
		WHERE p.GID = g.GID AND p.PlayerID = u.ID))";


	$std = oci_parse($conn, $sql);

	// Execute sql
	$r = oci_execute($std, OCI_DEFAULT);

	if (!$r) {

	$e = oci_error($std);
	debug_to_console($e);
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	// Fetch data
	while ($row = OCI_Fetch_Array($std, OCI_BOTH)) {
		echo "<table>";
		echo "<tr><th>".$row[0]."</th></tr>"; 
		echo "</table>";
	}
	oci_free_statement($std);


	function debug_to_console($data) {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}
?>

<h1>Our Game Statistics</h1>
<div class="tbody">Average playtime for each game</div>
<?php
    $sql = "SELECT Name, Rating, Price, a
				FROM Game_uploads g
				INNER JOIN
					(SELECT GID, AVG(AccumPlayTime) a
						FROM plays 
						GROUP BY GID) t
				ON g.GID=t.GID
				ORDER BY a DESC";


    $std = oci_parse($conn, $sql);

    // Execute sql
    $r = oci_execute($std, OCI_DEFAULT);

    if (!$r) {

        $e = oci_error($std);
        debug_to_console($e);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    echo "<table>";
    echo "<tr><th>Game</th><th>Rating</th><th>Price/CA$</th><th>Average Playtime/hr</th></tr>";    
    // Fetch data
    while ($row = OCI_Fetch_Array($std, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" .$row[3]. '</td></tr>';
    }
    // Store userid to server and pass to next page
    echo "</table>";

    oci_free_statement($std);


?>


</body>
</html>
