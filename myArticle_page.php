<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>My Articles</title>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
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
            margin-top: 1%; 
            margin-bottom: 5%; 
        }
        .warning{
			margin : 1%;
			text-align: center;
			font-size: 200%;
			background-color: #da283d;
			color: #ffffff;
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
<div class="header">My Articles</div>

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

		$stid = oci_parse($conn, 'SELECT a.Title, a.Forum, f.Category, a.Time, a.Content, a.Views 
        FROM ForumArticle_posts a, Forum_category_creates f
		WHERE a.AuthorID=:userid AND f.Name=a.Forum ORDER BY a.Time DESC');

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

		if (!$rr = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo "<div class='warning'>You have not posted any article yet</div>";
        } else {
            echo '<div class="w3-container"style="display: inline-block;width:60%;max-width:60%" >
                <div class="w3-card-4" style="display: inline-block;width:60%;max-width:60%">
                    <header class="w3-container w3-blue">
                        <h3>'. $rr[0] .'</h3>
                    </header>
                    <div class="w3-container" style="display: inline-block">
                        <p style="margin: 0px 20px 0px 0px;display: inline;float:left">Forum:'.$rr[1].'</p>
                        <p style="margin:0;display: inline;float:middle">Category:'.$rr[2].'</p>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Time:'.$rr[3].'</p>
                        <hr>
                        <p>'.$rr[4].'</p><br>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Views:'.$rr[5].'</p>
                    </div>
                </div>
            </div>';
        }

        while ($row = OCI_Fetch_Array($stid, OCI_BOTH)) {
            echo '<div class="w3-container"style="display: inline-block;width:60%;max-width:60%" >
                <div class="w3-card-4" style="display: inline-block;width:60%;max-width:60%">
                    <header class="w3-container w3-blue">
                        <h3>'. $row[0] .'</h3>
                    </header>
                    <div class="w3-container" style="display: inline-block">
                        <p style="margin: 0px 20px 0px 0px;display: inline;float:left">Forum:'.$row[1].'</p>
                        <p style="margin:0;display: inline;float:middle">Time:'.$row[2].'</p>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Game Mentioned:'.$row[3].'</p><br><br>
                        <hr>
                        <p>'.$row[4].'</p><br>
                        <p style="margin: 0px 0px 0px 20px;display: inline;float:right">Views:'.$row[5].'</p>
                    </div>
                </div>
            </div>';
        }
        
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
