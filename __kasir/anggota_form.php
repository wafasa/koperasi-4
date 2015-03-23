<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Nasabah &raquo; <a href='anggota_form.php'>Tambah Nasabah</a></div>";

?>
<script>
function cekisian(isian){
	if ( isian.nama.value == ""){
		alert("Data Nama Tidak Boleh Kosong");
		isian.nama.focus();
		return false;
	}
	if ( isian.pekerjaan.value == ""){
		alert("Data Pekerjaan Tidak Boleh Kosong");
		isian.pekerjaan.focus();
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
<body onLoad="document.form1.nama.focus();">
<?
if (isset($_POST['submit'])) {
	
	$sql = mysql_query("insert into tb_atribut_peminjaman values('','$_POST[agama]','$_POST[psg]','$_POST[pkrj_psg]','$_POST[rumah]','$_POST[penghasilan]','$_POST[pengeluaran]','$_POST[jaminan]','$_POST[rencana]','$_POST[info]')",$conn);
	
	$query = mysql_query("insert into tb_nasabah values ('',(select kd_atribut from tb_atribut_peminjaman order by kd_atribut desc limit 0, 1),'$_POST[noRek]','$_POST[rek_pinjaman]','$_POST[ktp]','$_POST[nama]','$_POST[alamat]','$_POST[telp]',now(),'$_POST[pekerjaan]')",$conn);

	echo "<meta http-equiv=refresh content=0;url=list_nasabah.php>";
}
$data=mysql_fetch_array(mysql_query("select * from tb_nasabah order by no_anggota desc limit 0, 1",$conn));
$dgt = strlen($data[no_anggota]);
$str = 8 - $dgt;
$rek = substr($data[rek_pinjaman], 0, $str);
$pre = substr($data[rek_pinjaman], $str, $dgt);
$nex = $data[no_anggota] + 1;
//$isi.=" $dgt $rek $nex";
$rekening = $rek . $nex;
if ($rek == '') {
$rekening = SB000001;
}
else if ($data[no_anggota] == '99') {
$str = 8 - 3;
$rek = substr($data[rek_pinjaman], 0, $str);
$pre = substr($data[rek_pinjaman], $str, $dgt);
$nex = $data[no_anggota] + 1;

$rekening = $rek . $nex;
}
else if ($data[no_anggota] == '999') {
$str = 8 - 4;
$rek = substr($data[rek_pinjaman], 0, $str);
$pre = substr($data[rek_pinjaman], $str, $dgt);
$nex = $data[no_anggota] + 1;

$rekening = $rek . $nex;
}

else if ($data[no_anggota] == '9999') {
$str = 8 - 5;
$rek = substr($data[rek_pinjaman], 0, $str);
$pre = substr($data[rek_pinjaman], $str, $dgt);
$nex = $data[no_anggota] + 1;

//$rekening = $rek . $nex;
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
	<td height=25 valign=middle class=CellLabel>No. Rekening Pinjaman<span class=merah>*</span><span id='noAnggotaExc' class=merah></span></td>
	<td height=25 valign=middle class=cellIsian>
	<input name='rek_pinjaman' type=text value='$rekening' size=45  disabled /><input name='rek_pinjaman' type=hidden value='$rekening' />
	</td>
	<td height=25 class=CellLabel>Jaminan / Agunan</td>
	<td height=25 class=cellIsian><input name=jaminan type=text id=jaminan value='' size=45  /></td>
	</tr>
	
	
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='' size=45  /></td>
	<td height=25 valign=middle class=CellLabel>Informasi BMT dari</td>
	<td height=25 valign=middle class=cellIsian><input name=info type=text id=info value='Temen' size=45 /></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>No. Telp </td>
	<td height=25 class=cellIsian><input name=telp type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Pekerjaan <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=pekerjaan type=text value='' size=45  /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Alamat Pekerjaan</td>
	<td height=25 class=cellIsian><input name=almt_pkrj type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='' size=45  onKeyup=Angka(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Agama </td>
	<td height=25 class=cellIsian><select name='agama' class=TextBox'>
	<option value='islam'>Islam</option><option value='kristen'>Kristen</option><option value='katholik'>Katholik</option>
	<option value='budha'>Budha</option><option value='hindu'>Hindu</option></select></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Nama Suami / Istri </td>
	<td height=25 class=cellIsian><input name=psg type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Pekerjaan Suami / Istri</td>
	<td height=25 class=cellIsian><input name=pkrj_psg type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Status Rumah</td>
	<td height=25 class=cellIsian><select name='rumah' class=TextBox><option value='kontrak'> Kontrak</option><option value='sendiri'>Sendiri</option><option value='menumpang'>Menumpang</option></select></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Penghasilan / Bulan</td>
	<td height=25 class=cellIsian><input name=penghasilan type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Pengeluaran / Bulan </td>
	<td height=25 class=cellIsian><input name=pengeluaran type=text value='' size=45  /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
		
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4></textarea></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
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
