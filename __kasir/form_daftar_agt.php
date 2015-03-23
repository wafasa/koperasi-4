<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Anggota &raquo; <a href='anggota_form.php'>Formulir Daftar Anggota</a></div>";

?>
<script>
function cekisian(isian){
	if ( isian.nama.value == ""){
		alert("Data Nama Tidak Boleh Kosong");
		isian.nama.focus();
		return false;
	}
	if ( isian.tmpl.value == ""){
		alert("Data Tempat Lahir Tidak Boleh Kosong");
		isian.tmpl.focus();
		return false;
	}
	if (( isian.tgl.value == "") || ( isian.bln.value == "") || ( isian.thn.value == "")){
		alert("Tanggal Bulan Tahun Lahir Tidak Boleh Kosong");
		isian.tgl.focus();
		return false;
	}
	if( isian.alamat.value == ""){
		alert("Data Alamat Tidak Boleh Kosong");
		isian.alamat.focus();
		return false;
	}
	if ( isian.no_id.value == ""){
		alert("No Identitas Tidak Boleh Kosong");
		isian.no_id.focus();
		return false;
	}
	if ( isian.simp.value == ""){
		alert("Jumlah Simpanan Pokok Tidak Boleh Kosong");
		isian.simp.focus();
		return false;
	}
	if ( isian.no_id.value == ""){
		alert("Jumlah Simpanan Wajib Tidak Boleh Kosong");
		isian.simw.focus();
		return false;
	} 
}

</script>
<body onLoad="document.add_agt.nama.focus();">
<?
if (isset($_POST['submit'])) {
	$ttl = "$_POST[thn]-$_POST[bln]-$_POST[tgl]";
	$simp= gabung($_POST[simp]);
	$simw= gabung($_POST[simw]);
	$krgp= 1000000 - $simp;
	$krgw= 420000 - $simw;
	//$isi.="'$_POST[nama]','$_POST[tmpl]','$ttl','$_POST[alamat]','$_POST[no_id]'";
	$a = mysql_query("insert into tb_anggota values ('','$_POST[nama]','$_POST[tmpl]','$ttl',now(),'$_POST[alamat]','$_POST[no_id]')",$conn);
	$b = mysql_query("insert into tb_simpanan_pokok values ('',(select no_agt from tb_anggota order by no_agt desc limit 0,1),'1000000','$simp','$krgp')",$conn);
	$c = mysql_query("insert into tb_simpanan_wajib values ('',(select no_agt from tb_anggota order by no_agt desc limit 0,1),'420000','$simw','$krgw')",$conn);
	$d = mysql_query("insert into tb_trans_sw values ('',(select no_sw from tb_simpanan_wajib order by no_sw desc limit 0, 1), now(),'$simw')",$conn);
	if (!$a) {
		echo "<script>alert('Data gagal ditambahkan')</script>";
	} else {
		echo "<meta http-equiv=refresh content=0;url=list_anggota.php>";
	}
}
$data=mysql_fetch_array(mysql_query("select * from tb_nasabah order by no_anggota desc limit 0, 1",$conn));
$dgt = strlen($data[no_anggota]);
$str = 8 - $dgt;
$rek = substr($data[rek_pinjaman], 0, $str);
$pre = substr($data[rek_pinjaman], $str, $dgt);
$nex = $pre + 1;
$rekening = $rek . $nex;
$isi.="
	<form name=add_agt action='' method=post onSubmit='return cekisian(this);'>
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Anggota Koperasi</td></tr>
	<tr>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Masuk <span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' disabled onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Simpanan Pokok * (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=simp type=text id=simp value='' size=45 maxlength=50 onkeyup=FormNum(this); /></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='' size=45 maxlength=50 /></td>
	<td height=25 valign=middle class=CellLabel>Simpanan Wajib * (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=simw type=text id=simw value='' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Tempat Lahir <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=tmpl type=text value='' size=45 maxlength=50 /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Tanggal Lahir<span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian>
	<select name='tgl'><option value=''>- Tanggal -</option>"; 
	for ($i=1;$i<=31;$i++) { 
		$isi.="<option value='$i'>$i</option>"; 
	} 
	$isi.="</select>
	<select name='bln'><option value=''>- Bulan -</option>
	<option value='01'>Januari</option>
	<option value='02'>Februari</option>
	<option value='03'>Maret</option>
	<option value='04'>April</option>
	<option value='05'>Mei</option>
	<option value='06'>Juni</option>
	<option value='07'>Juli</option>
	<option value='08'>Agustus</option>
	<option value='09'>September</option>
	<option value='10'>Oktober</option>
	<option value='11'>November</option>
	<option value='12'>Desember</option></select>
	<select name='thn'><option value=''>- Tahun -</option>";
	for ($i=1950; $i<=date("Y"); $i++) {
		$isi.="<option value='$i'>$i</option>";
	}
	$isi.="</select>
	</td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
		
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4></textarea></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
		
	<tr>
	<td height=25 class=CellLabel>No. Identitas *</td>
	<td height=25 class=cellIsian><input name=no_id type=text value='' size=45 maxlength=50 onKeyup=Angka(this); /></td>
	<td height=25 class=CellLabel></td>
	<td height=25 class=cellIsian></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input name=submit type=submit class=tombol value=Simpan onclick=	\"ok=confirm('Yakin data yang dimasukkan sudah benar?');
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