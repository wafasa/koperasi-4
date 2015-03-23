<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
	header("location:list_nasabah.php");
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>