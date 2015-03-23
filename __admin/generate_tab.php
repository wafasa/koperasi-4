<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";

$isi.="<div class=kepala>Transaksi &raquo; <a href=''>Generate Bunga Tabungan</a></div>";
if (isset($_POST[generate])) {
	$month = date("Y-m");
	$sql = mysql_query("select no_tabungan from tb_tabungan",$conn);
	while ($data = mysql_fetch_array($sql)) {
	$wewe = mysql_fetch_array(mysql_query("select * from tb_tab_history where sandi <> 4 and no_tabungan = '$data[no_tabungan]' and tanggal like ('$month%') group by no_tabungan",$conn));
	
	if ($data[no_tabungan] == $wewe[no_tabungan]) {
	$dt = mysql_fetch_array(mysql_query("select min(saldo) as saldo from tb_tab_history where no_tabungan = '$data[no_tabungan]' and tanggal like ('$month%')",$conn));
	} else {
	
	$dt = mysql_fetch_array(mysql_query("select saldo from tb_tabungan where no_tabungan = '$data[no_tabungan]'",$conn));
	}
	$bunga = ($dt[saldo] * 0.06) / 12;
	
	$tab = mysql_fetch_array(mysql_query("select saldo from tb_tabungan where no_tabungan = '$data[no_tabungan]'",$conn));
	$new_tab = $bunga + $tab[saldo];
		if ($tab[saldo] > 10000) {
	
		$b_tab  = mysql_query("insert into tb_tab_history values ('','$data[no_tabungan]',now(),'$bunga','0','4','$new_tab')",$conn);
		$upt	= mysql_query("update tb_tabungan set tanggal_update = now(), status_bunga = '1', saldo = '$new_tab' where no_tabungan = '$data[no_tabungan]'",$conn);
		}
	}
	header("location:generate_tab.php?msg=sukses");
}
$bln = date("F Y");
$mon = date("Y-m");
$pnb = mysql_num_rows(mysql_query("select * from tb_tabungan",$conn));
$jml = mysql_num_rows(mysql_query("select * from tb_tab_history where tanggal like ('$mon%') and sandi = 1",$conn));
$jum = mysql_num_rows(mysql_query("select * from tb_tab_history where tanggal like ('$mon%') and sandi = 2",$conn));
$sum = mysql_fetch_array(mysql_query("select sum(saldo) as total from tb_tabungan",$conn));
$sta = mysql_num_rows(mysql_query("select * from tb_tab_history where sandi = 4 and tanggal like ('$mon%')",$conn)); 
$tgl = date("Y-m-25");
	if (strtotime($tgl) > strtotime(date("Y-m-d")) or ($sta <> 0)) {
		$sts = "disabled";
	}
	if ($_GET['msg'] == 'sukses') {
		$isi.="<div class=ok>Sukses men-gerate bunga tabungan bulan $bln</div>";
	}
$isi.="&curren; Total data penabung <b>$pnb Nasabah</b><br>";
$isi.="&curren; Total transaksi <b>setoran</b> tabungan bulan $bln sejumlah <b>$jml Transaksi</b><br>";
$isi.="&curren; Total transaksi <b>penarikan</b> tabungan bulan $bln sejumlah <b>$jum Transaksi</b><br>";
$isi.="&curren; Total uang nasabah penabung sejumlah <b>".duit($sum[total])."</b><br>";
$isi.="<form action='' method=post>
	<input type=submit $sts name='generate' value='   Generate Bunga   '  onclick=	\"ok=confirm('Yakin akan men-generate bunga tabungan?');
		if (ok) {
		return } else {return false} \"></form>";
include "instansiasi.php";
}
else {
header("location:../index.php");
}
?>