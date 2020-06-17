<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
	<title>My Profile</title>
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
    form { display: inline-block; text-align: left; }
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

<h1>My Profile</h1>

<?php
	//extract userid from login page
    session_save_path("/tmp");
	session_start();
?>

<form method="post">
  <label for="nickname">Nickname:</label>
  <input type="text" required="required" name="nickname" value="<?php echo $_SESSION['userName'];?>"><br><br>
  <label for="gender">Gender:</label>
  <input type="text" required="required" name="gender" value="<?php echo $_SESSION['userGender'];?>"><br><br>
  <label for="birthday">Birthday:</label>
    <!-- TODO: Change type text to date, convert date type to fit in value -->
  <input type="text" required="required" name="birthday" value="<?php echo $_SESSION['userBirthday'];?>"><br><br>
  <label for="accCreation">Account Created At:</label>
  <input type="text" required="required" name="accCreation" readonly="readonly" value="<?php echo $_SESSION['userAccCreation'];?>"><br><br>
  <label for="role">Role:</label>

  <input type="text" required="required" name="role" readonly="readonly" value="<?php echo $_SESSION['userRole'];?>"><br><br>
  <button type="submit" class="btn btn-primary btn-block btn-large" name="update">Update</button>
  <button type="submit" class="btn btn-primary btn-block btn-large" name="logout">Log Out</button>
</form>

<?php

        if(isset($_POST['update'])){
                $conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
                if (!$conn) {
                    $e = oci_error();   // For oci_connect errors do not pass a handle
                    debug_to_console("Database is NOT Connected");
                    trigger_error(htmlentities($e['message']), E_USER_ERROR);
                }
                    debug_to_console("Database is Connected");

                    // Parse sql
                    //'UPDATE UserID Set Nickname = Oliver , Gender = :gender, Birthday = :birthday WHERE ID = 1
                    $stid = oci_parse($conn, 'UPDATE UserID Set Nickname = :nn , Gender = :gender, Birthday = :birthday
                     WHERE ID = :userid');

                    $nickname = $_POST['nickname'];
                    $gender = $_POST['gender'];
                    $birthday = $_POST['birthday'];
                    $id =  $_SESSION['userid'];

                    // Set input

                    $ba = array(':nn' => $nickname, ':gender' => $gender, ':birthday' => $birthday, ':userid'=>$id);

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

                    // Commit the changes to both tables

                    $r = oci_commit($conn);
                    if (!$r) {
                        $e = oci_error($conn);
                        trigger_error(htmlentities($e['message']), E_USER_ERROR);
                    }
                    // Update _SESSION
                    $_SESSION['userName'] = $nickname;
                    $_SESSION['userGender'] = $gender;
                    $_SESSION['userBirthday'] = $birthday;
                    oci_free_statement($stid);
                    OCILogoff($conn);
                    header('Location: player_profile.php'); 

        }

        if (isset($_POST['logout'])) {
            session_destroy();
            header('Location: login_page.php'); 
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
