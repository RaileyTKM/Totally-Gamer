<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>Forum</title>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <!-- https://www.w3schools.com/tags/tag_center.asp -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
        .w3-container{
            text-align: center;
        }
        .content-card{
            text-align: center;
        }
        .w3-container.w3-card-4 p { display: inline-block; }
        hr { 
            position: relative; 
            top: 20px; 
            border: none; 
            height: 4px; 
            background: black; 
            margin-top: -2%; 
            margin-bottom: 5%; 
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
<div class="header">Forum</div>

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



</div>
<form method="post">
    <label for="type">Type:</label>
    <select name="type">
        <option value="All">All</option>        
        <option value="Action">Action</option>
        <option value="RPG">RPG</option>
        <option value="Simulation">Simulation</option>
        <option value="Sports">Sports</option>
        <option value="Strategy">Strategy</option>
        <option value="Puzzle">Puzzle</option>
        <option value="FPS">FPS</option>
        <option value="Visual Novel">Visual Novel</option>
        <option value="Crafting">Crafting</option>
        <option value="Parkour">Parkour</option>
    </select>
    <input type="text" name = "forum" placeholder="Forum Name">
    <button type="submit" class="btn btn-primary btn-block btn-large" name="search">Search</button>
</form>


<?php
    if (isset($_POST['search'])) {
        if("" != trim($_POST['forum'])){
            searchForum(); 
        }else{
            displayAllForum();
        }
    }
    else {
        displayAllForum();
    }


function displayAllForum(){
    global $conn;

    $sql = "SELECT p.Title, f.Name, u.Nickname, p.Time, p.Content, p.Views
            FROM ForumArticle_posts p
            INNER JOIN Forum_category_creates f
            ON f.Name = p.Forum
            INNER JOIN UserID u
            ON p.AuthorID = u.ID
            ORDER BY p.Time DESC";


    $allForum = oci_parse($conn, $sql);

    // Execute sql
    $r = oci_execute($allForum, OCI_DEFAULT);

    if (!$r) {

        $e = oci_error($allForum);
        debug_to_console($e);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    while ($row = OCI_Fetch_Array($allForum, OCI_BOTH)) {
        echo '<div class="w3-container"style="display: inline-block;width:60%;max-width:60%" >
                <div class="w3-card-4" style="display: inline-block;width:60%;max-width:60%">
                    <header class="w3-container w3-blue">
                        <h3>'. $row[0] .'</h3>
                    </header>
                    <div class="w3-container" style="display: inline-block">
                        <p style="margin: 0px 20px 0px 0px;display: inline;float:left">Forum:'.$row[1].'</p>
                        <p style="margin:0;display: inline;float:middle">Author:'.$row[2].'</p>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Time:'.$row[3].'</p>
                        <hr >
                        <p>'.$row[4].'</p><br>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Views:'.$row[5].'</p>
                    </div>
                    <button class="w3-button w3-block w3-dark-grey">Follow Up</button>
                </div>
            </div>';

    }
    // Store userid to server and pass to next page
    echo "</table>";

    oci_free_statement($allForum);

}

function searchForum(){
    switch  ($_POST['type']):
        case "All":
            searchName();
            break;
        default:
            searchTypeName();
    endswitch;
}

function searchName(){
    global $conn;

    $sql = "SELECT p.Title, f.Name, u.Nickname, p.Time, p.Content, p.Views
            FROM ForumArticle_posts p
            INNER JOIN Forum_category_creates f
            ON f.Name = p.Forum
            INNER JOIN UserID u
            ON p.AuthorID = u.ID
            Where f.Name LIKE :gn_bv
            ORDER BY p.Time DESC";


    $found = oci_parse($conn, $sql);
    $gn = "%". $_POST['forum']. "%";
    oci_bind_by_name($found, ":gn_bv", $gn);

    // Execute sql
    $r = oci_execute($found, OCI_DEFAULT);

    if (!$r) {

        $e = oci_error($found);
        debug_to_console($e);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    while ($row = OCI_Fetch_Array($found, OCI_BOTH)) {
        echo '<div class="w3-container"style="display: inline-block;width:60%;max-width:60%" >
                <div class="w3-card-4" style="display: inline-block;width:60%;max-width:60%">
                    <header class="w3-container w3-blue">
                        <h3>'. $row[0] .'</h3>
                    </header>
                    <div class="w3-container" style="display: inline-block">
                        <p style="margin: 0px 20px 0px 0px;display: inline;float:left">Forum:'.$row[1].'</p>
                        <p style="margin:0;display: inline;float:middle">Author:'.$row[2].'</p>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Time:'.$row[3].'</p>
                        <hr >
                        <p>'.$row[4].'</p><br>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Views:'.$row[5].'</p>
                    </div>
                    <button class="w3-button w3-block w3-dark-grey">Follow Up</button>
                </div>
            </div>';

    }
    // Store userid to server and pass to next page
    echo "</table>";

    oci_free_statement($found);

}

function searchTypeName(){

    global $conn;

    $sql = "SELECT p.Title, f.Name, u.Nickname, p.Time, p.Content, p.Views
            FROM ForumArticle_posts p
            INNER JOIN Forum_category_creates f
            ON f.Name = p.Forum
            INNER JOIN UserID u
            ON p.AuthorID = u.ID
            Where f.Category = :type AND f.Name LIKE :gn_bv  
            ORDER BY p.Time DESC";


    $found = oci_parse($conn, $sql);
    $gn = "%". $_POST['forum']. "%";
    oci_bind_by_name($found, ":gn_bv", $gn);
    oci_bind_by_name($found, ":type", $_POST['type']);
    // Execute sql
    $r = oci_execute($found, OCI_DEFAULT);

    if (!$r) {

        $e = oci_error($found);
        debug_to_console($e);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    while ($row = OCI_Fetch_Array($found, OCI_BOTH)) {
        echo '<div class="w3-container"style="display: inline-block;width:60%;max-width:60%" >
                <div class="w3-card-4" style="display: inline-block;width:60%;max-width:60%">
                    <header class="w3-container w3-blue">
                        <h3>'. $row[0] .'</h3>
                    </header>
                    <div class="w3-container" style="display: inline-block">
                        <p style="margin: 0px 20px 0px 0px;display: inline;float:left">Forum:'.$row[1].'</p>
                        <p style="margin:0;display: inline;float:middle">Author:'.$row[2].'</p>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Time:'.$row[3].'</p>
                        <hr >
                        <p>'.$row[4].'</p><br>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Views:'.$row[5].'</p>
                    </div>
                    <button class="w3-button w3-block w3-dark-grey">Follow Up</button>
                </div>
            </div>';

    }
    // Store userid to server and pass to next page
    echo "</table>";

    oci_free_statement($found);

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
