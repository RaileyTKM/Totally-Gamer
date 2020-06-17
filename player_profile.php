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
  <select name="gender">
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Other">Other</option>
      <option selected="selected"><?php echo $_SESSION['userGender'];?></option>
  </select><br><br>

  <fieldset>
      <legend>Birthday:</legend>
      <label for="day">Day:</label>
      <input type="number" required="required" name="day" min='1' max='31' value=<?php echo substr($_SESSION['userBirthday'], 0, 2);?>>
      <label for="month">Month:</label>
      <select name="month">
        <option value="JAN">JAN</option>
        <option value="FEB">FEB</option>
        <option value="MAR">MAR</option>
        <option value="APR">APR</option>
        <option value="MAY">MAY</option>
        <option value="JUN">JUN</option>
        <option value="JUL">JUL</option>
        <option value="AUG">AUG</option>
        <option value="SEP">SEP</option>
        <option value="OCT">OCT</option>
        <option value="NOV">NOV</option>
        <option value="DEC">DEC</option>
        <option selected="selected"><?php echo substr($_SESSION['userBirthday'], 3, 3);?></option>
      </select>
      <label for="year">Year(last 2 digits):</label>
      <input type="number" required="required" name="year" min='00' max='99' value=<?php echo substr($_SESSION['userBirthday'], 7, 2);?>>
  </fieldset><br>
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
                    $birthday = $_POST['day'] . "-" . $_POST['month'] . "-" . $_POST['year'];
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
