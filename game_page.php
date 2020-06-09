<?php
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
$sql = "SELECT g.Name, u.Nickname, g.Price, g.Rating
        FROM UserID u, Game_uploads g 
        WHERE g.DevID = u.ID";

$games = oci_parse($conn, $sql);

// Execute sql
$r = oci_execute($games, OCI_DEFAULT);

if (!$r) {

    $e = oci_error($stid);
    debug_to_console($e);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch all game data 
$row = oci_fetch_array($games, OCI_BOTH);
$gName = $row[0];
$devName = $row[1];
$price = $row[2];
$rating = $row[3];

//close the connection i guess? Do I always have to do that?
oci_close($conn);
?>