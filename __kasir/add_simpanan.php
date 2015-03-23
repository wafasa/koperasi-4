<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Anggota &raquo; <a href='anggota_form.php'>Formulir Daftar Anggota</a></div>";

?>
<script>
function cekisian(isian){
	if ( isian.kat.value == ""){
		alert("Kategori Belum dipilih");
		isian.kat.focus();
		return false;
	}
	if( isian.sim.value == ""){
		alert("Data Simpanan Tidak Boleh Kosong");
		isian.sim.focus();
		return false;
	} 
}

</script>
<body onLoad="document.add_agt.nama.focus();">
<?
if (isset($_POST['submit'])) {
	if ($_POST[kat] == '1') {
		$duit = gabung($_POST[sim]);
		$a = mysql_fetch_array(mysql_query("Select kekurangan_w, tersetor_w from tb_simpanan_wajib where no_agt = '$_GET[id]'",$conn));
		$setor = $a[tersetor_w] + $duit;
		$kurang= $a[kekurangan_w] - $duit;
		$sql = mysql_query("update tb_simpanan_wajib set kekurangan_w = '$kurang', tersetor_w = '$setor' where no_agt = '$_GET[id]'",$conn);
		echo "<meta http-equiv=refresh content=0;url=list_anggota.php>";
	}
}
$data=mysql_fetch_array(mysql_query("select a.*, sp.*, sw.* from tb_anggota a, tb_simpanan_pokok sp, tb_simpanan_wajib sw where a.no_agt = sp.no_agt and sw.no_agt = a.no_agt and a.no_agt = $_GET[id]",$conn));

$isi.="
	<form name=add_agt action='' method=post onSubmit='return cekisian(this);'>
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Anggota Koperasi</td></tr>
	<tr>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Masuk <span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' disabled onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Simpanan Pokok (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input disabled name=simw type=text id=simw value='".nominal($data[kewajiban_p])."' size=45 maxlength=50 /></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama / No Anggota</td>
	<td height=25 valign=middle class=cellIsian><input disabled name=simw type=text id=simw value='$data[nama]' size=25 maxlength=50 />&nbsp;<input name=simw type=text id=simw value='$data[no_agt]' size=15 maxlength=50 disabled /></td>
	<td height=25 valign=middle class=CellLabel>Kekurangan Simpanan Pokok (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input disabled name=simw type=text id=simw value='".nominal($data[kekurangan_p])."' size=45 maxlength=50 /></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Pilih Kategori</td>
	<td height=25 valign=middle class=cellIsian>
	<select name='kat' class=TextBox><option value=''>-- Pilih Kategori --</option><option value='1'> Simpanan Wajib </option><option value='2'> Simpanan Pokok </option></td>
	<td height=25 valign=middle class=CellLabel>Simpanan Wajib (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input disabled name=simw type=text id=simw value='".nominal(420000)."' size=45 maxlength=50 /></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Jumlah Simpanan (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=sim type=text id=sim value='' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel>Kekurangan Simpanan Wajib (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input disabled name=simw type=text id=simw value='".nominal($data[kekurangan_w])."' size=45 maxlength=50 /></td>
	</tr>
	<tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input name='submit' type=submit class=tmbl value=Simpan onclick=	\"ok=confirm('Yakin data yang dimasukkan sudah benar?');
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