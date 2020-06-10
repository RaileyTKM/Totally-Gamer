<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Game Collections</title>
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
<!--end of Navigation bar--><?php

session_save_path("/tmp");
session_start();
// Connect to DB
$conn = OCILogon("ora_reyred", "a74388869", "dbhost.students.cs.ubc.ca:1522/stu");

if (!$conn) {
    $e = oci_error();   // For oci_connect errors do not pass a handle
    debug_to_console("Database is NOT Connected");
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
}
debug_to_console("Database is Connected");

// query for Game_uploads
// Game page would display a game's:
    // Name
    // Developer Nickname
    // Price
    // Rating
$sql1 = "SELECT g.Name, u.Nickname, g.Price, g.Rating
        FROM UserID u, Game_uploads g
        WHERE g.DevID = u.ID
        ORDER BY g.Name";

$allGames = oci_parse($conn, $sql1);

// Execute sql
$r = oci_execute($allGames, OCI_DEFAULT);

if (!$r) {

    $e = oci_error($allGames);
    debug_to_console($e);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
echo "<table>";
echo "<tr><th>All Games</th></tr>";
echo "<tr><th>Game</th><th>Developer</th><th>Price</th><th>Rating</th></tr>";
// Fetch data

while ($row = OCI_Fetch_Array($allGames, OCI_BOTH)) {
    if ($row[3] == null) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . '-' . "</td></tr>";
    } else {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>";
        // echo $row[0];
    }
}
// Store userid to server and pass to next page
echo "</table>";

// Fetch all game data
// $row = oci_fetch_array($games, OCI_BOTH);
// $gName = $row[0];
// $devName = $row[1];
// $price = $row[2];
// $rating = $row[3];

//close the connection i guess? Do I always have to do that?
oci_free_statement($games);
oci_close($conn);

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

?>

</body>
</html>
