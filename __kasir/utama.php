<?php
session_start();
if ($_SESSION['user'] and $_SESSION['pass'] and $_SESSION['leve'] == '1') {
include "template.php";
	header("location:list_nasabah.php");
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>