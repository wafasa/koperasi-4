<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Nasabah &raquo; <a href='tambah_tabungan.php'>Tambah Penabung</a></div>";

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
	if ( isian.telp.value == ""){
		alert("Data telepon Tidak Boleh Kosong");
		isian.telp.focus();
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
	if ( isian.nom.value == ""){
		alert("Data Alamat Tidak Boleh Kosong");
		isian.nom.focus();
		return false;
	}	
}

</script>
<body onLoad="document.form1.no_rek.focus();">
<?
if (isset($_POST['submit'])) {
	$data = mysql_fetch_array(mysql_query("Select no_rekening, nama from tb_nasabah_tabungan where no_rekening = '$_POST[no_rek]'",$conn));
	if ($data[no_rekening] == $_POST[no_rek]) {
	$error = "<i style='color:red; text-decoration:blink;'>Nomor Tabungan ".strtoupper($_POST[no_rek])." Sudah dipakai</i>";
	}
	else {
	$duit  = gabung($_POST[nom]);
	$query = mysql_query("insert into tb_nasabah_tabungan values ('','$_POST[no_rek]','$_POST[ktp]','$_POST[nama]','$_POST[alamat]',now())",$conn);
	$tab  = mysql_query("insert into tb_tabungan values ('',(select no_agt_tab from tb_nasabah_tabungan order by no_agt_tab desc limit 0, 1),now(),'0','$duit')",$conn);
	$hist  = mysql_query("insert into tb_tab_history values ('',(select no_tabungan from tb_tabungan order by no_tabungan desc limit 0, 1),now(),'$duit','0','1','$duit')",$conn);
	}
	if ($tab) {
		echo "<script>alert('Berhasil menambah tabungan')</script>";
		echo "<meta http-equiv=refresh content=0;url=tambah_tabungan.php>";
	}
	
}
$isi.="
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Anggota Koperasi</td></tr>
	<tr>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Masuk <span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>&nbsp;</td>
	<td height=25 valign=middle class=cellIsian>&nbsp;</td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. Rekening Tabungan<span class=merah>*</span><span id='noAnggotaExc' class=merah></span></td>
	<td height=25 valign=middle class=cellIsian>
	<input name='no_rek' type=text value='$_POST[no_rek]' size=25  />&nbsp;$error
	</td>
	<td height=25 class=CellLabel>&nbsp;</td>
	<td height=25 class=cellIsian>&nbsp;</td>
	</tr>
	
	
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$_POST[nama]' size=45  /></td>
	<td height=25 valign=middle class=CellLabel>&nbsp;</td>
	<td height=25 valign=middle class=cellIsian>&nbsp;</td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>No. Telp </td>
	<td height=25 class=cellIsian><input name=telp type=text value='$_POST[telp]' size=45  /></td>
	<td height=25 class=CellLabel>&nbsp;</td>
	<td height=25 class=cellIsian>&nbsp;</td>
	</tr>

	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$_POST[ktp]' size=45  onKeyup=Angka(this); /></td>
	<td height=25 valign=middle class=CellLabel>&nbsp;</td>
	<td height=25 valign=middle class=cellIsian>&nbsp;</td>
	</tr>
		
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4>$_POST[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel>&nbsp;</td>
	<td height=25 valign=middle class=cellIsian>&nbsp;</td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Saldo Awal Tabungan (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=nom type=text value='$_POST[nom]' size=45  onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel>&nbsp;</td>
	<td height=25 valign=middle class=cellIsian>&nbsp;</td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input name=submit type=submit class=tmbl value=Simpan onclick=	\"ok=confirm('Yakinkah data yang dimasukkan sudah benar?');
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
