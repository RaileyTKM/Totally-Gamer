<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
    <title>Project</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login">
    <h1>Login</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required="required" />
        <input type="password" name="password" placeholder="Password" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Let me in.</button>
    </form>
    <?php

        session_save_path("/tmp");
        session_start();

        if(isset($_POST['username'])&&isset($_POST['password'])){
                $conn = OCILogon("ora_reyred", "a74388869", "dbhost.students.cs.ubc.ca:1522/stu");
                if (!$conn) {
                    $e = oci_error();   // For oci_connect errors do not pass a handle
                    debug_to_console("Database is NOT Connected");
                    trigger_error(htmlentities($e['message']), E_USER_ERROR);
                }
                    debug_to_console("Database is Connected");

                    // Parse sql

                    $stid = oci_parse($conn, 'SELECT ID , Role FROM UserID WHERE Nickname = :username AND Password = :password');

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
                    $role = $row[1];

                    if($id){ // TODO: player page and developer page

                    // Store userid to server and pass to next page
                    $_SESSION['userid'] = $id;
                    oci_free_statement($stid);
                    OCILogoff($conn);
                    header('Location: https://www.students.cs.ubc.ca/~zpengwei/home_page.php');
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