<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Check Out</title>
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
		.warning{
			margin : 1%;
			text-align: center;
			font-size: 200%;
			background-color: #da283d;
			color: #ffffff;
		}
        table, th, td {
        	border: 1px solid black;
        }
		.hide{
			display: none;
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
<div class="header">Check Out</div>


<?php
	//extract userid from login page
    session_save_path("/tmp");
    session_start();

    $gid = $_GET['buy'];

	$conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
	if (!$conn) {
		$e = oci_error();   // For oci_connect errors do not pass a handle
		debug_to_console("Database is NOT Connected");
		trigger_error(htmlentities($e['message']), E_USER_ERROR);
	}
	debug_to_console("Database is Connected");

	//display purchase information 
	gameInfo($gid);
	$bought = (int)checkBought($gid);

	
	function checkBought($gid) {
		global $conn;
		$stid = oci_parse($conn, 'SELECT COUNT(*) FROM Purchases_profits_detail p
		WHERE p.GID = :gid AND p.PlayerID = :userid');
		
		$userid = $_SESSION['userid'];
		$ba = array(':gid' => $gid, ':userid' => $userid);
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
		$row = OCI_Fetch_Array($stid, OCI_BOTH);
		return $row[0];
		oci_free_statement($stid);
	}
?>

<div class='tname'>Payment Methods</div>
<form method="POST" <?php if ($bought){ echo 'class="hide"';} ?>> <!--refresh page when submitted -->
    <input type="submit" value="PayPal" name="paypal"></p>

    <input type="submit" value="Credit Card" name="credit"></p>

    <input type="submit" value="Debit Card" name="debit"></p>
</form>

<div class='warning <?php if (!$bought){ echo 'hide';} ?>'>You have already purchased this game</div>

<form method="POST"> <!--refresh page when submitted -->
    <input type="submit" value="Return to Games" name="return"></p>
</form>


<?php
	//insert purchase into database, jump to the updated game page
    if (isset($_POST['paypal'])) {
        $stid = oci_parse($conn, "INSERT INTO Purchases_profits_detail VALUES (:userid, :gid, SYSDATE, 'PayPal')");
        purchase($stid,$gid);
	} else if (isset($_POST['credit'])) {
        $stid = oci_parse($conn, "INSERT INTO Purchases_profits_detail VALUES (:userid, :gid, SYSDATE, 'CreditCard')");
        purchase($stid,$gid);
    } else if (isset($_POST['debit'])) {
        $stid = oci_parse($conn, "INSERT INTO Purchases_profits_detail VALUES (:userid, :gid, SYSDATE, 'DebitCard')");
        purchase($stid,$gid);
	}
	
	if (isset($_POST['return'])) {
		OCILogoff($conn);
        header('Location: game_page.php');
	}

    OCILogoff($conn);

	function gameInfo($gid) {
        global $conn;

        $stid = oci_parse($conn, 'SELECT g.Name, u.Nickname, g.Price
        FROM Game_uploads g, UserID u
		WHERE g.GID = :gid AND g.DevID = u.ID');

		$ba = array(':gid' => $gid);
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

		echo "<div class='tname'>". 'Payment Info' ."</div>";
		
		echo "<table>";
		echo "<tr><th>Game Name</th><th>Developer</th><th>Price/CA$</th></tr>";
		while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; 
		}
		echo "</table>";
		oci_free_statement($stid);
    }
    

    function purchase($stid,$gid){
        global $conn;
        $rate = oci_parse($conn, "INSERT INTO rates VALUES (:userid, :gid, 0)");

        $userid = $_SESSION['userid'];

        $ba = array(':userid' => $userid, ':gid' => $gid);
        foreach ($ba as $key => $val) {
            oci_bind_by_name($stid, $key, $ba[$key]);
            oci_bind_by_name($rate, $key, $ba[$key]);
        }

    
        if (!$stid) {
            $e = oci_error($conn);
            debug_to_console($e);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $z = OCIExecute($stid, OCI_NO_AUTO_COMMIT);
        $r = OCIExecute($rate, OCI_NO_AUTO_COMMIT);

        if (!$r) {
            $e = oci_error($rate);
            debug_to_console($e);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        if (!$z) {
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
        oci_free_statement($rate);
        OCILogoff($conn);
        header('Location: game_owned.php');
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
