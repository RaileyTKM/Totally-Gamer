<html>
<?php
	if ($c=OCILogon("ora_zpengwei", "a73569758", "dbhost.students.cs.ubc.ca:1522/stu")) {
		echo "Successfully connected to Oracle.\n";
		OCILogoff($c);
	} else {
		$err = OCIError();
		echo "ora_reyred";
		echo "a74388869";
		echo "Oracle Connect Error " . $err['message'];
	}
?>
</html>
