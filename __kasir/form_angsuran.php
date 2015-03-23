<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Transaksi &raquo; <a href='anggota_form.php'>Form Angsuran Pembiayaan</a></div>";

if (isset($_POST['cari_rek'])) {
	$dt   = mysql_fetch_array(mysql_query("select * from tb_pinjaman where no_anggota = (select no_anggota from tb_nasabah where rek_pinjaman = '$_POST[no_rek]') and status_lunas = '0'",$conn));
	$fer = mysql_query("select * from tb_angsuran where no_pinjaman = '$dt[no_pinjaman]' and status_lunas = '0'",$conn);
	$row = mysql_fetch_array($fer);
	$sgs  = mysql_num_rows($fer);
	if ($row[no_pinjaman] != null) {
	$tgl = explode("-",$row[jatuh_tempo]);
	$x  = mktime(0, 0, 0, date($tgl[1]), date($tgl[2])+5, date($tgl[0]));
	$day = date("Y-m-d",$x);
	$now = date("Y-m-d");
	}
	//$selisih = 
	
	if (strtotime($day) < strtotime($now)) {
		$denda = 0.03 * $row[pokok]; 	
	}
	
	if ($dt[jenis_pinjaman] != null) {
	$data = mysql_fetch_array(mysql_query("select * from tb_nasabah where rek_pinjaman = '$_POST[no_rek]'",$conn));
	}
	else {
	echo "<script>alert('Data ".strtoupper($_POST[no_rek])." tidak ditemukan')</script>";
	
	}
	
}
if (isset($_POST['submit'])) {
	$dt = mysql_fetch_array(mysql_query("select min(angsuran_ke) as ke, count(*) as max_ke from tb_angsuran where no_pinjaman = '$_POST[no_pinjaman]' and status_lunas = '0'",$conn));
	$dd = mysql_fetch_array(mysql_query("select count(*) as max_ke from tb_angsuran where no_pinjaman = '$_POST[no_pinjaman]'",$conn));
	$now = date("Y-m-d");
	if ($dt[max_ke] == $_POST[jml_angsur]) {
		for ($i=$dt[ke]; $i<=$dd[max_ke]; $i++) {
		if ($i < ($dt[ke]+2)) {
			$sql = mysql_query("update tb_angsuran set tgl_bayar = '$now', status_lunas = '1' where angsuran_ke = '$i' and no_pinjaman = '$_POST[no_pinjaman]'",$conn);
			
		}
		else {
			$sql = mysql_query("update tb_angsuran set tgl_bayar = '$now', jasa = '0', status_lunas = '1' where angsuran_ke = '$i' and no_pinjaman = '$_POST[no_pinjaman]'",$conn);
		}
		}
		$query = mysql_query("update tb_pinjaman set sisa_angsuran = '0', status_lunas = '1' where no_pinjaman = '$_POST[no_pinjaman]'",$conn);
		header("location:form_angsuran.php?msg=ok");
	}
	if ($dt[max_ke] > $_POST[jml_angsur]) {
		for ($i=$dt[ke]; $i<=$_POST[jml_angsur]+$dt[ke]-1; $i++) {
		$a = mysql_fetch_array(mysql_query("select sisa_angsuran, bsr_angsuran from tb_pinjaman where no_pinjaman = '$_POST[no_pinjaman]'",$conn));
		$sisa = $a[sisa_angsuran] - $a[bsr_angsuran];
		$b = mysql_query("update tb_pinjaman set sisa_angsuran = '$sisa' where no_pinjaman = '$_POST[no_pinjaman]'",$conn);
			$dnd = gabung($_POST[denda]);
			$sql = mysql_query("update tb_angsuran set tgl_bayar = '$now', status_lunas = '1', denda = '$dnd' where angsuran_ke = '$i' and no_pinjaman = '$_POST[no_pinjaman]'",$conn);
		}
		header("location:form_angsuran.php?msg=ok");
	}
}
if ($_GET['msg'] == 'ok') {
	$isi.="<div style='color:blue;'><img src='../images/s_success.png' align=left>Angsuran Berhasil Dilakukan</div>";
}
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Angsuran Pembiayaan </td></tr>
	<tr>
	<td width=18% height=35 valign=middle class=CellLabel>Nomor Rekening</td>
	<td height=35 class=cellIsian><form action='form_angsuran.php' method=post><input name='no_rek' type=text value='$_POST[no_rek]' size=45 maxlength=50> &nbsp; <input type=submit name='cari_rek' value=' Go '></form></td>
	<td height=25 class=CellLabel>Sisa Angsuran </td>
	<td height=25 class=cellIsian><form name=form1 action='' method=post onSubmit='return cekisian(this);'><input name=nama type=text id=nama value='$sgs' size=10 maxlength=50 disabled/> Kali</td>
	</tr>
	<tr>
	
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Peminjaman<span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".TglIndo($dt[tgl_pinjam])."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' disabled onclick=displayCalendar(document.forms[1].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Besar Angsuran (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='".number_format($dt[bsr_angsuran], 0, '','.')."' size=45 maxlength=50 disabled/></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$data[nama]' size=45 maxlength=50 disabled/></td>
	<td height=25 valign=middle class=CellLabel>Jenis Angsuran</td>
	<td height=25 valign=middle class=cellIsian><select name='jns_angsuran' disabled>
	<option value=''> -- pilih methode -- </option>
	<option value='1' "; if ($dt[jenis_pinjaman] == '1') { $isi.="selected"; } $isi.="> Flat </option>
	<option value='2' "; if ($dt[jenis_pinjaman] == '2') { $isi.="selected"; } $isi.="> Diagonal </option></select></td></tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Pekerjaan <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=pekerjaan type=text value='$data[pekerjaan]' size=45 maxlength=50 disabled/></td>
	<td height=25 valign=middle class=CellLabel>Sisa Pinjaman &nbsp;(Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=bsr_pinj type=text value='".number_format($dt[sisa_angsuran], 0, '','.')."' size=45 maxlength=46 disabled/></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$data[no_ktp]' size=45 maxlength=50 onKeyup=Angka(this); disabled/></td>
	<td height=25 valign=middle class=CellLabel>Lama Pinjaman</td>
	<td height=25 valign=middle class=cellIsian><select name='lama_pinj' disabled><option value=''> -- pilih --</option>";
	$que = mysql_query("select * from tb_durasi",$conn);
	while ($bar=mysql_fetch_array($que)) {
		$isi.="<option value='$bar[durasi]'"; if ($dt[lama_pinjaman] == $bar[durasi]) { $isi.="selected";} $isi.=">$bar[durasi]</option>";
	}
	$isi.="</select> Bulan</td>
	</tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Rekening Tabungan <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=rek_tab type=text value='$data[no_rekening]' size=45 maxlength=50 onKeyup=Angka(this); disabled/></td>
	<td height=25 valign=middle class=CellLabel>Jatuh Tempo & Denda</td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='".TglIndo($row[jatuh_tempo])."' size=20 maxlength=50 onKeyup=Angka(this); disabled/>&nbsp;<input name=denda type=text class=penting value='".nominal($denda)."' size=18 maxlength=50 onKeyup=Angka(this); readonly/></td>
	</tr>
	<tr>
	<td height=25 class=CellLabel>No. Telp </td>
	<td height=25 class=cellIsian><input name=telp type=text value='$data[no_telp]' size=45 maxlength=50 disabled/></td></form>
	<td height=25 class=CellLabel>Jumlah Angsuran</td>
	<td height=25 class=cellIsian><form action='' method=post name=form2>
	<input type=hidden name='no_rek' value='$_POST[no_rek]'>
	<input type=hidden name='cari_rek' value=' Go '>
	<select name='byk_angsur' onchange=submit(form2);><option value=''> -- Jumlah Angsur --</option>";
	for ($i=1;$i<=$sgs;$i++) {
		$isi.="<option value='$i' "; if ($_POST[byk_angsur] == $i) {$isi.="selected";} $isi.=">$i</option>";
	}
	$isi.="</select> &nbsp;Kali Angsuran</form></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4 disabled>$data[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel><b>Jumlah Angsuran</b></td>
	<td height=25 valign=middle class=cellIsian><h1 style><form action='' method=post onSubmit=cekisian(this);>";
	if (isset($_POST[byk_angsur]) != '') {
		
		if ($dt[lama_pinjaman] == $_POST[byk_angsur]) { //jika belum angsur sebelumnya
		$dre = mysql_fetch_array(mysql_query("select sum(jasa) as jasa from tb_angsuran where no_pinjaman = '$dt[no_pinjaman]' and angsuran_ke in ('1','2')",$conn));
		$isi.=duit($dt[jml_pinjaman]+$dre[jasa]+$denda)."<br>";
		$money=$dt[jml_pinjaman]+$dre[jasa];
		$status = "lunas";
		} 
		else if ($_POST[byk_angsur] == $sgs) {
		$ke  = ($dt[lama_pinjaman] - $sgs) + 1;
		$ke2 = $ke + 1;
		$prev= $ke-1;
		$dre = mysql_fetch_array(mysql_query("select sum(jasa) as jasa from tb_angsuran where no_pinjaman = '$dt[no_pinjaman]' and angsuran_ke in ($ke,$ke2)",$conn));
		$ate = mysql_fetch_array(mysql_query("select sisa_pokok from tb_angsuran where no_pinjaman = '$dt[no_pinjaman]' and angsuran_ke = $prev",$conn));
		$isi.=duit($dre[jasa]+$ate[sisa_pokok]+$denda);
		$money=$dre[jasa]+$ate[sisa_pokok];
		$isi.="<br><h4>Pelunasan</h4>";
		} 
		else if ($_POST[byk_angsur] == 0) {
		//$isi.="";
		}
		else {
		$isi.=duit($dt[bsr_angsuran] * $_POST[byk_angsur]+$denda);
		$money=$dt[bsr_angsuran] * $_POST[byk_angsur];
		}
	}
	$isi.="</h1></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle>
	<input type=hidden name='denda' value='".ceil($denda)."'>
	<input type=hidden name='jml_angsur' value='$_POST[byk_angsur]'>
	<input type=hidden name='kd_nasabah' value='$_POST[no_rek]'>
	<input type=hidden name='no_pinjaman' value='$row[no_pinjaman]'>
	<input name=submit type=submit value='  Angsur   ' onclick=	\"ok=confirm('Yakin data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></td>
	</tr>
	</table></form>
	
";
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
