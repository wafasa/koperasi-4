<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Nasabah &raquo; <a href='edit_tabungan.php?id=$_GET[id]'>Edit Penabung</a></div>";

?>
<script>
function cekisian(isian){
	if ( isian.no_rek.value == ""){
		alert("nomer rekening masih kosong");
		isian.no_rek.focus();
		return false;
	}
	if ( isian.nama.value == ""){
		alert("Data Nama Tidak Boleh Kosong");
		isian.nama.focus();
		return false;
	}
	if ( isian.ktp.value == ""){
		alert("Nomor KTP Tidak Boleh Kosong");
		isian.ktp.focus();
		return false;
	}
	if ( isian.alamat.value == ""){
		alert("Data Alamat Tidak Boleh Kosong");
		isian.alamat.focus();
		return false;
	}	
}

</script>
<body onLoad="document.form1.no_rek.focus();">
<?
if (isset($_POST['submit'])) {
	$sql = mysql_query("update tb_nasabah_tabungan set no_ktp = '$_POST[ktp]', nama = '$_POST[nama]', alamat = '$_POST[alamat]' where no_rekening = '$_POST[no_rek]'",$conn);
	if ($sql) {
		echo "<script>alert('Data diri nasabah berhasil diubah')</script>";
	}
	echo "<meta http-equiv=refresh content=0;url=tambah_tabungan.php>";
}
else {
	$data = mysql_fetch_array(mysql_query("select n.*,t.* from tb_nasabah_tabungan n, tb_tabungan t where n.no_agt_tab = t.no_agt_tab and t.no_tabungan = '$_GET[id]'",$conn));
}
$isi.="
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Anggota Koperasi</td></tr>
	<tr>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Masuk <span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. Rekening Tabungan<span class=merah>*</span><span id='noAnggotaExc' class=merah></span></td>
	<td height=25 valign=middle class=cellIsian>
	<input name='no_rek' type=text value='$data[no_rekening]' size=45 readonly />
	</td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$data[nama]' size=45  /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>

	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$data[no_ktp]' size=45  onKeyup=Angka(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
		
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4>$data[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>

	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input name=submit type=submit class=tmbl value='Simpan Perubahan' onclick=	\"ok=confirm('Yakinkah data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></td>
	</tr>
	</table>
	</form>
";

include "instansiasi.php";
}
else {
	header("location:../login.php");
}
