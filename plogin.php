<?php
session_start();
include "__conf/config.db.php";
if (($_POST['username']) and ($_POST['passwd'])) {
	$password = md5($_POST['passwd']);
	$sql = mysql_query("select * from tb_usersystem where username = '$_POST[username]' and password = '$password'");
	$bar = mysql_fetch_array($sql);
	if (($bar['username'] == $_POST['username']) and ($bar['password'] == $password)) {
		if ($bar['status'] == '1') {
			$_SESSION['user'] = $bar['username'];
			$_SESSION['pass'] = $bar['password'];
			$_SESSION['leve'] = $bar['status'];
			header("location:__kasir/utama.php");
		}
		else if ($bar['status'] == '2') {
			$_SESSION['user'] = $bar['username'];
			$_SESSION['pass'] = $bar['password'];
			$_SESSION['leve'] = $bar['status'];
			header("location:__admin/utama.php");
		}
		else {
			echo "<meta http-equiv=refresh content=0;url='login.php'>";
		}
	}
	else {
		echo "<script>alert('Username atau password tidak sesuai')</script>";
		echo "<script>history.go(-1)</script>";
	}
}
else {
	echo "<script>history.go(-1)</script>";
}
?>