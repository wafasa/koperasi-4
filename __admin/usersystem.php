<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Konfigurasi Sistem &raquo; <a href='usersystem.php'>Manajemen Usersystem</a></div>";

if ((isset($_POST[tgl])) and (isset($_POST[smpe]))) {
	$dari = $_POST[tgl];
	$smpe = $_POST[smpe];
}
else {
	$dari = date("d/m/Y");
	$smpe = date("d/m/Y");
}
$isi.="<input type=button value=' Tambah ' onClick=location.href='usersystem.php?act=tambah'>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>ID.USER</h3></th>
				<th><h3>USERNAME</h3></th>
				<th><h3>PASSWORD</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>ALAMAT</h3></th>
				<th><h3>NO.TELP</h3></th>
				<th><h3>JABATAN</h3></th>
				<th><h3>LEVEL</h3></th>
				<th><h3>ACTION</h3></th>	
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	$sql = mysql_query("select * from tb_usersystem",$conn);
	while ($bar = mysql_fetch_array($sql)) {
		$isi.="
		<tr>
		<td align=center>$bar[id_user]</td>
		<td>$bar[username]</td>
		<td align=center>**********************</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>".ucwords($bar[alamat])."</td>
		<td>$bar[no_telepon]</td>
		<td>$bar[jabatan]</td>
		<td align=center>$bar[status]</td>
		<td align=center><a href='usersystem.php?id=$bar[id_user]&act=edit' onmouseover= \"Tip('Edit Data Usersystem') \" onmouseout= \"UnTip()\"><img src='../images/b_edit.png'></a> 
		<a onclick=	\"ok=confirm('Anda yakin akan menghapus data anggota ".strtoupper($bar[nama])."?');
		if (ok) {
		return } else {return false} \" href='usersystem.php?act=delete&id=$bar[id_user]' onmouseover= \"Tip('Hapus Data Usersystem') \" onmouseout= \"UnTip()\"><img src='../images/b_drop.png'></a></td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody>
		</table>	
		";
		
	if ($_GET['act'] == 'delete') {
		$sql = mysql_query("delete from tb_usersystem where id_user = '$_GET[id]'",$conn);
		$isi.="<meta http-equiv=refresh content=0;url=data_peminjaman.php>";
	}

	//include_once "instansiasi.php";
	?>
    <script type="text/javascript" src="../__js/script.js"></script>
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",0);
  </script>
    <?
	if ($_GET['act'] == 'edit') {
		if (isset($_POST['simpan'])) {
			if ($_POST[jabatan] == '1') {
				$jabatan = "kasir";
			}
			else {
				$jabatan = "administrasi";
			}
			$sql = mysql_query("update tb_usersystem set nama = '$_POST[nama]', alamat = '$_POST[alamat]', no_telepon = '$_POST[telp]', jabatan = '$jabatan', status = '$_POST[jabatan]' where id_user = '$_GET[id]'",$conn);
			echo "<meta http-equiv=refresh content=0;url=usersystem.php>";
		}
		$data = mysql_fetch_array(mysql_query("select * from tb_usersystem where id_user = '$_GET[id]'",$conn));
		$isi.="
		<form action='' method=post>
		<table id=cari><br><Br><br><Br><br><Br><br>
		<tr><td>Username</td><td>:</td><td><input type=text name=username size=45 class=TextBox value='$data[username]' disabled></td></tr>
		<tr><td>Password</td><td>:</td><td><input type=text name=password size=45 class=TextBox value='********' disabled></td></tr>
		<tr><td>Nama User</td><td>:</td><td><input type=text name=nama size=45 class=TextBox value='$data[nama]'></td></tr>
		<tr><td>Alamat</td><td>:</td><td><input type=text name=alamat size=45 class=TextBox value='$data[alamat]'></td></tr>
		<tr><td>Nomor Telepon</td><td>:</td><td><input type=text name=telp size=45 class=TextBox value='$data[no_telepon]'></td></tr>
		<tr><td>Jabatan</td><td>:</td><td><select name='jabatan' class=TextBox>
		<option value='2' "; if ($data[jabatan] == 'administrasi') { $isi.="selected"; } $isi.=">Administrasi</option>
		<option value='1' "; if ($data[jabatan] == 'kasir') { $isi.="selected"; } $isi.=">Kasir</option>
		</select></td></tr>
		
		<tr><td></td><td></td><td><input type=submit name='simpan' value='    Simpan Perubahan   ' class=tomb></td></tr>
		</table>
		</form>
		";
	}
	else if ($_GET['act'] == 'tambah') {
		if ((isset($_POST['add'])) and (trim($_POST[username]) != '')) {
			if ($_POST[jabatan] == 'administrasi') { $sts = "2"; } else { $sts = "1"; }
			$pass = md5(1234);
			$sql = mysql_query("insert into tb_usersystem values ('','$_POST[username]','$pass','$_POST[nama]','$_POST[alamat]','$_POST[telp]','$_POST[jabatan]','$sts')",$conn);
			$isi.="<meta http-equiv=refresh content=0;url=usersystem.php>";
		}
		$isi.="
		<form action='' method=post>
		<table id=cari align=left>
		<tr><td>Username</td><td>:</td><td><input type=text name=username size=45 class=TextBox value='$_POST[username]'></td></tr>
		<tr><td>Password</td><td>:</td><td><input type=text name=password size=45 class=TextBox value='1234' disabled></td></tr>
		<tr><td>Nama User</td><td>:</td><td><input type=text name=nama size=45 class=TextBox value='$_POST[nama]'></td></tr>
		<tr><td>Alamat</td><td>:</td><td><input type=text name=alamat size=45 class=TextBox value='$_POST[alamat]'></td></tr>
		<tr><td>Nomor Telepon</td><td>:</td><td><input type=text name=telp size=45 class=TextBox value='$_POST[no_telepon]'></td></tr>
		<tr><td>Jabatan</td><td>:</td><td><select name='jabatan' class=TextBox>
		<option value='administrasi' "; if ($_POST[jabatan] == 'administrasi') { $isi.="selected"; } $isi.=">Administrasi</option>
		<option value='kasir' "; if ($_POST[jabatan] == 'kasir') { $isi.="selected"; } $isi.=">Kasir</option>
		</select></td></tr>
		
		<tr><td></td><td></td><td><input type=submit name='add' value='    Tambahkan   ' class=tomb></td></tr>
		</table>
		</form>
		";
	}
include_once "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>