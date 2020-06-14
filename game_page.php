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
	margin-bottom: 1%;
    }
    .tname{
        margin : 1%;
        text-align: center;
        font-size: 200%;
    }
    table, th, td {
  border: 1px solid black;
}
    button{
        margin-bottom: 1%;
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
<div class="header">All Games</div>


<form method="post">
    <select name="option">
        <option value="Type">Type</option>
        <option value="GameName">Game Name</option>
        <option value="Developer">Developer</option>
    </select>
    <input type="text" name = "content" required="required" placeholder="Search..">
    <button type="submit" class="btn btn-primary btn-block btn-large" name="search">Search</button>
</form>


<?php
    session_save_path("/tmp");
	session_start();
    $conn = OCILogon("ora_reyred", "a74388869", "dbhost.students.cs.ubc.ca:1522/stu");
    if (!$conn) {
        $e = oci_error();   // For oci_connect errors do not pass a handle
        debug_to_console("Database is NOT Connected");
        trigger_error(htmlentities($e['message']), E_USER_ERROR);
    }
    debug_to_console("Database is Connected");




    if (isset($_POST['search'])) {
        searchGame();
    }
    else {
        displayAllGame();
    }
    OCILogoff($conn);

function searchGame(){
    switch  ($_POST['option']):
        case "Type":
            searchByType();
            break;
        case "Developer":
            searchByDev();
            break;
        default:
            searchByName();
    endswitch;
}

//TODO
function searchByType(){

}
function searchByDev(){

}
function searchByName(){

}

// query for Game_uploads
// Game page would display a game's:
    // Name
    // Developer Nickname
    // Price
    // Rating
    function displayAllGame(){
        global $conn;
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

        echo "<tr><th>Game</th><th>Developer</th><th>Price</th><th>Rating</th></tr>";
        // Fetch data

        while ($row = OCI_Fetch_Array($allGames, OCI_BOTH)) {
            if ($row[3] == null) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . '-' . '</td>
                <td><form action="purchase_game.php" method="get"><button type="submit" name="buy" value='.$row[0].' >Buy</button></form></td>
                </tr>';
            } else {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" .$row[3]. '</td>
                <td><form action="purchase_game.php" method="get"><button type="submit" name="buy" value='.$row[0].' >Buy</button></form></td>
                </tr>';
            }
        }
        // Store userid to server and pass to next page
        echo "</table>";

        oci_free_statement($allGames);
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
