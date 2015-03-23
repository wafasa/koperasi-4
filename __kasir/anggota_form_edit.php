<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Data Anggota &raquo; <a href='anggota_form.php'>Edit Data Nasabah</a></div>";

?>
<script>
function cekisian(isian){
	if ( isian.noRek.value == ""){
		alert("Nomor Rekening Tidak Boleh Kosong");
		isian.noRek.focus();
		return false;
	}
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
	if( isian.telp.value == ""){
		alert("Nomor Telepon Tidak Boleh Kosong");
		isian.telp.focus();
		return false;
	}
	if ( isian.alamat.value == ""){
		alert("Data Alamat Tidak Boleh Kosong");
		isian.alamat.focus();
		return false;
	} 
}

</script>
<body onLoad="document.form1.noRek.focus();">

<?

if (isset($_POST['submit'])) {
	$sql = mysql_query("update tb_nasabah set no_rekening = '$_POST[noRek]',no_ktp = '$_POST[ktp]',nama = '$_POST[nama]',alamat = '$_POST[alamat]',no_telp = '$_POST[telp]', pekerjaan = '$_POST[pekerjaan]' where no_anggota = '$_POST[no_agt]'",$conn);
	$ssl = mysql_query("update tb_atribut_peminjaman set agama = '$_POST[agama]', nama_psg = '$_POST[nm_psg]', pekerjaan_psg = '$_POST[pkj_psg]', status_rumah = '$_POST[rumah]', jaminan = '$_POST[jaminan]', rencana_pembiayaan = '$_POST[rencana_pemb]' where kd_atribut = '$_GET[kd_atb]'",$conn);
	alert_edit($sql);
}
$a = mysql_fetch_array(mysql_query("select * from tb_nasabah where no_anggota = '$_GET[id]'",$conn));
$c = mysql_fetch_array(mysql_query("select * from tb_atribut_peminjaman where kd_atribut = '$_GET[kd_atb]'",$conn));
$b = explode("-",$a[tgl_masuk]);
$isi.="<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Anggota Koperasi</td></tr>
	<tr>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Masuk <span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$b[2]/$b[1]/$b[0]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Agama</td>
	<td height=25 valign=middle class=cellIsian><select name='agama'>
	<option value='islam' "; if ($c[agama] == 'islam') { $isi.="selected"; } $isi.=">Islam</option>
	<option value='kristen' "; if ($c[agama] == 'kristen') { $isi.="selected"; } $isi.=">Kristen</option>
	<option value='katholik' "; if ($c[agama] == 'katholik') { $isi.="selected"; } $isi.=">Katholik</option>
	<option value='hindu' "; if ($c[agama] == 'hindu') { $isi.="selected"; } $isi.=">Hindu</option>
	<option value='budha' "; if ($c[agama] == 'budha') { $isi.="selected"; } $isi.=">Budha</option>
	</select></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. Rekening Pinjaman<span class=merah>*</span><span id='noAnggotaExc' class=merah></span></td>
	<td height=25 valign=middle class=cellIsian>
	<input name='rek_pinjaman' type=text value='$a[rek_pinjaman]' size=45 maxlength=50 disabled /><input name='rek_pinjaman' type=hidden value='$a[rek_pinjaman]' />
	</td>
	<td height=25 class=CellLabel> Nama Suami / Istri</td>
	<td height=25 class=cellIsian><input name='nm_psg' type=text value='$c[nama_psg]' size=45 maxlength=50 /></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$a[nama]' size=45 maxlength=50 /></td>
	<td height=25 class=CellLabel>Pekerjaan Suami / Istri</td>
	<td height=25 class=cellIsian><input name='pkj_psg' type=text value='$c[pekerjaan_psg]' size=45 maxlength=50 /></td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Pekerjaan <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=pekerjaan type=text value='$a[pekerjaan]' size=45 maxlength=50 /></td>
	<td height=25 class=CellLabel> Status Rumah</td>
	<td height=25 class=cellIsian><select name='rumah'>
	<option value='kontrak' "; if ($c[status_rumah] == 'kontrak') {$isi.="selected"; } $isi.=">Kontrak</option>
	<option value='sendiri' "; if ($c[status_rumah] == 'sendiri') {$isi.="selected"; } $isi.=">Sendiri</option>
	<option value='menumpang' "; if ($c[status_rumah] == 'menumpang') {$isi.="selected"; } $isi.=">Menumpang</option>
	</select>
	</td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$a[no_ktp]' size=45 maxlength=50 onKeyup=Angka(this); /></td>
	<td height=25 valign=middle class=CellLabel>Jaminan</td>
	<td height=25 valign=middle class=cellIsian><input name='jaminan' type=text value='$c[jaminan]' size=45 /></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>No. Telp </td>
	<td height=25 class=cellIsian><input name=telp type=text value='$a[no_telp]' size=45 maxlength=50 /></td>
	<td height=25 class=CellLabel>Rencana Pembiayaan</td>
	<td height=25 class=cellIsian><input name='rencana_pemb' type=text value='$c[rencana_pembiayaan]' size=45 maxlength=50 /></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4>$a[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input type=hidden name='no_agt' value='$a[no_anggota]'><input name=submit type=submit class=tmbl value='  Simpan Perubahan    '></td>
	</tr>
	</table>
	</form>
";

include "instansiasi.php";
}
else {
	header("location:../login.php");
}
