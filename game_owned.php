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
<div class="header">My Collection</div>


<!-- TODO: display game owned with game_name,price,paid by, date, my rate -->
<!-- TODO: button display game record order by start time and AccumPlayTime-->
<!-- TODO: button display my achievement order by game-->
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

    displayGame();
    ?>

    <form method="POST"> <!--refresh page when submitted-->
        <input type="submit" value="Display Game Record" name="display_record"></p>
    </form>

    <form method="POST" > 
        <input type="submit" value="Display My Achievement" name="display_achievement"></p>
    </form>


<?php

    if (isset($_POST['display_record'])) {
        displayOverallInfo();
        displayRecord();
    } else if (isset($_POST['display_achievement'])) {

        displayAchievement();

    }


    OCILogoff($conn);


    // Help function
    function displayOverallInfo(){
        // game_name, AccumPlayTime,CurrStage,AccumScore
        global $conn;

        $stid = oci_parse($conn, 'SELECT g.Name, p.AccumPlayTime, p.CurrStage, p.AccumScore
        FROM plays p, Game_uploads g
		WHERE p.PlayerID = :userid AND p.GID = g.GID order by name');

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
        echo "<div class='tname'>". 'Overall Info' ."</div>";
		echo "<table>";
		echo "<tr><th>Game Name</th><th>Accumulative Play Time</th><th>CurrStage</th><th>Accumulative Score</th></tr>";
		// Fetch data

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; 
        }
		// Store userid to server and pass to next page
        echo "</table>";
		oci_free_statement($stid);
    }

    function displayRecord(){
        //game_name, StartTime , EndTime,Score
        global $conn;
        
        $stid = oci_parse($conn, 'SELECT g.Name, r.StartTime, r.EndTime, r.Score
        FROM GameRecord_recordedTo r, Game_uploads g
		WHERE r.PlayerID = :userid AND r.GID = g.GID order by name');

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
        echo "<div class='tname'>". 'Game Records' ."</div>";
		echo "<table>";
		echo "<tr><th>Game Name</th><th>StartTime</th><th>EndTime</th><th>Score</th></tr>";
		// Fetch data

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; 
        }
		// Store userid to server and pass to next page
        echo "</table>";
		oci_free_statement($stid);
    }


    function displayAchievement(){
        //game_name , Achievement_name,Achievement_Dare,
        global $conn;

        $stid = oci_parse($conn, 'SELECT g.Name, a.Name, c.Achieve_Date
        FROM associates s, achieves c, Achievement a, Game_uploads g
		WHERE c.UserID = :userid AND c.AID = s.AID AND s.GID = g.GID And s.AID = a.AID order by g.Name');

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
        echo "<div class='tname'>". 'My Achievement' ."</div>";
		echo "<table>";
		echo "<tr><th>Game Name</th><th>Achievement Name</th><th>Achievement Date</th></tr>";
		// Fetch data

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; 

        }
		// Store userid to server and pass to next page
        echo "</table>";
		oci_free_statement($stid);
    }


    function displayGame(){
        //game_name , price , paid by , pay date , my rate
        global $conn;
		// Parse sql
		// select i.User2ID, f.Nickname, f.Gender, f.Birthday, f.Role from Purchases_profits_detail p, 
		// Game_uploads g where i.User1ID = 1 AND i.User2ID = f.ID order by nickname;
		$stid = oci_parse($conn, 'SELECT g.Name, g.Price, p.PayMethod , p.Purchase_Date , r.Rating
        FROM Purchases_profits_detail p, Game_uploads g, rates r
		WHERE p.PlayerID = :userid AND p.GID = g.GID AND r.PlayerID = p.PlayerID And r.GID = p.GID order by name');

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
		echo "<tr><th>Name</th><th>Price</th><th>Pay Method</th><th>Purchase Date</th><th>My rates</th></tr>";
		// Fetch data

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] 
            . "</td></tr>"; //or just use "echo $row[0]" 
            // echo $row[0];
        }
		// Store userid to server and pass to next page
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
