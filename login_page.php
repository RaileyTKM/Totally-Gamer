<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login">
    <h1>Login</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required="required" />
        <input type="password" name="password" placeholder="Password" required="required" />
        <button type="submit" name="login" class="btn btn-primary btn-block btn-large">Sign in</button>
    </form>
    <?php

        session_save_path("/tmp");
        session_start();

        if(isset($_POST['login'])){
                $conn = OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu");
                if (!$conn) {
                    $e = oci_error();   // For oci_connect errors do not pass a handle
                    debug_to_console("Database is NOT Connected");
                    trigger_error(htmlentities($e['message']), E_USER_ERROR);
                }
                    debug_to_console("Database is Connected");

                    // Parse sql

                    $stid = oci_parse($conn, 'SELECT * FROM UserID WHERE Nickname = :username AND Password = :password');

                    $username = $_POST['username'];
                    // echo $username;
                    $password = $_POST['password'];
                    // echo $password;

                    // Set input

                    $ba = array(':username' => $username, ':password' => $password);

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


                    // Fetch data
                    $row = OCI_Fetch_Array($stid, OCI_BOTH);
                    $id = $row[0];
                    if($id){ // TODO: player page and developer page
                    $nickname = $row[1];
                    $gender = $row[3];
                    $birthday = $row[4];
                    $accCreation = $row[5];
                    $role = $row[6];
                    // Store userid to server and pass to next page
                    $_SESSION['userid'] = $id;
                    $_SESSION['userName'] = $nickname;
                    $_SESSION['userGender'] = $gender;
                    $_SESSION['userBirthday'] = $birthday;
                    $_SESSION['userAccCreation'] = $accCreation;
                    $_SESSION['userRole'] = $role;
                    oci_free_statement($stid);
                    OCILogoff($conn);
                    header('Location: home_page.php');
                    }else{
                        $message = "Wrong Username/Password";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                    }
            }


        


        function debug_to_console($data) {
            $output = $data;
            if (is_array($output))
                $output = implode(',', $output);
        
            echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
        }



    ?>

    
</div>
</body>
</html>