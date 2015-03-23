<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Transaksi &raquo; <a href='anggota_form.php'>Form Pembiayaan</a></div>";

?>
<script>
function cekisian(isian){
	if( isian.nama.value == ""){
		alert("Silahkan masukkan nomor rekening kemudian klik tombol GO");
			isian.nama.focus();
		return false;
	}
	if( isian.jns_angsuran.value == ""){
		alert("Jenis angsuran harus dipilih terlebih dahulu");
		isian.jns_angsuran.focus();
		return false;
	}
	if( isian.bsr_pinj.value == ""){
		alert("Besar pinjaman harus diisi");
		isian.bsr_pinj.focus();
		return false;
	} 
	if (isian.lama_pinj.value == ""){
        alert("Lama pinjaman harus dipilih");
		isian.lama_pinj.focus();
		return false;
     }
        
}

</script>
<?

if (isset($_POST['cari_rek'])) {
	$ss   = mysql_fetch_array(mysql_query("select no_anggota from tb_pinjaman where no_anggota = (select no_anggota from tb_nasabah where rek_pinjaman = '$_POST[no_rek]') and status_lunas = '0'",$conn));
	if ($ss != null) {
		echo "<script>alert('Data nasabah dengan no.rekening ".strtoupper($_POST[no_rek])." sedang terkait pinjaman')</script>";
	}
	else {
		$data = mysql_fetch_array(mysql_query("select * from tb_nasabah where rek_pinjaman = '$_POST[no_rek]'",$conn));
	}
}
if (isset($_POST['kd_nasabah'])) {
	if ($_POST['jns_angsuran'] == '1') {
		flat($_POST[bsr_pinj],$_POST[lama_pinj],$_POST[kd_nasabah]);
		$adpro = ($_POST[adpro]/100) * gabung($_POST[bsr_pinj]);
		$qee = mysql_query("insert into tb_adpro values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0, 1),now(),'$adpro','$_POST[bya_ca]','$_POST[survey]','$_POST[stofmap]')",$conn);
		$dana = gabung($_POST[bsr_pinj]) - ($adpro + $_POST[bya_ca] + $_POST[survey] + $_POST[stofmap]);
		if ($qee) {
		echo "<script>alert('Data Berhasil disimpan dana yang dicair kan setelah potongan administrasi dll senilai ".duit($dana)."')</script>";
		echo "<meta http-equiv=refresh content=0;url=data_peminjaman.php>";
		}
		
		
	}
	else if ($_POST['jns_angsuran'] == '2') {
		$jum  = gabung($_POST[bsr_pinj]);
		$data = mysql_fetch_array(mysql_query("select * from tb_jasa where jenis = '2'"));
		$jasa = $data[jasa]/100;
		$var1 = ceil($jum * $jasa);
		$pmbg1= pow((1+$jasa),$_POST[lama_pinj]);
		$pmbg2= 1-(1/$pmbg1);
		$jml_angsuran = floor($var1 / $pmbg2);
		$pokok= $jml_angsuran - $var1;
		$sisa1= ceil($jum - $pokok);
		$ss   = $jum - $pokok;
		$janji= ceil($jml_angsuran * $_POST[lama_pinj]);
		$y    = mktime(0, 0, 0, date("m")+$_POST[lama_pinj], date("d"), date("Y"));
		$tgg  = date("Y-m-d",$y);
		
		$z    = mktime(0, 0, 0, date("m")+1, date("d"), date("Y"));
		$tempo= date("Y-m-d",$z);
		
		$que = mysql_query("insert into tb_pinjaman values ('','$_POST[kd_nasabah]',now(),'$tgg','$jum','$janji','$jml_angsuran','0','0','2','$_POST[lama_pinj]','$janji','0')",$conn);
		$sql = mysql_query("insert into tb_angsuran values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0,1),'1','$tempo','','$pokok','$var1','$jml_angsuran','$sisa1','0','0')",$conn);
		for ($i=2;$i<=$_POST[lama_pinj];$i++) {
		$x    = mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
		$tgl  = date("Y-m-d",$x);
		$dt   = mysql_fetch_array(mysql_query("select sisa_pokok from tb_angsuran order by no_angsuran desc limit 0, 1",$conn));
		$newjasa = floor($dt[sisa_pokok] * $jasa);
		$newpokok= floor($jml_angsuran - ($dt[sisa_pokok] * $jasa));
		$js = $dt[sisa_pokok] * $jasa;
		$pk = $jml_angsuran - $js;
		$newsisa = floor($dt[sisa_pokok] - $pk);
		
		$sql = mysql_query("insert into tb_angsuran values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0,1),'$i','$tgl','','$newpokok','$newjasa','$jml_angsuran','$newsisa','0','0')",$conn);
		$adpro = ($_POST[adpro]/100) * gabung($_POST[bsr_pinj]);
		
		}
		$adpro = ($_POST[adpro]/100) * gabung($_POST[bsr_pinj]);
		$qee = mysql_query("insert into tb_adpro values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0, 1),now(),'$adpro','$_POST[bya_ca]','$_POST[survey]','$_POST[stofmap]')",$conn);
		$dana = gabung($_POST[bsr_pinj]) - ($adpro + $_POST[bya_ca] + $_POST[survey] + $_POST[stofmap]);
		if ($qee) {
		echo "<script>alert('Data Berhasil disimpan dana yang dicair kan setelah potongan administrasi dll senilai ".duit($dana)."')</script>";
		echo "<meta http-equiv=refresh content=0;url=data_peminjaman.php>";
		}
	}
}
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Pengajuan Pembiayaan </td></tr>
	<tr>
	<td width=18% height=35 valign=middle class=CellLabel>Nomor Rekening *</td>
	<td height=35 class=cellIsian><form action='' method=post><input name='no_rek' type=text value='$_POST[no_rek]' size=45 maxlength=50 /> &nbsp; <input type=submit name='cari_rek' class=tmbl value=' Go '></form></td>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<td height=25 valign=middle class=CellLabel>Jenis Angsuran *</td>
	<td height=25 valign=middle class=cellIsian><select name='jns_angsuran'>
	<option value=''> -- pilih methode -- </option>
	<option value='1'> Flat </option>
	<option value='2'> Diagonal </option></select></td>
	</tr>
	<tr>
	
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Peminjaman</td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb class=tmbl type=button class=verd11 value='...' onclick=displayCalendar(document.forms[1].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Besar Pinjaman &nbsp;(Rp. ) *</td>
	<td height=25 valign=middle class=cellIsian><input name=bsr_pinj type=text value='' size=50 maxlength=46 onKeyup=FormNum(this); /></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah></span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$data[nama]' size=45 maxlength=50 disabled/></td>
	<td height=25 valign=middle class=CellLabel>Lama Pinjaman *</td>
	<td height=25 valign=middle class=cellIsian><select name=lama_pinj><option value=''> -- pilih --</option>";
	$que = mysql_query("select * from tb_durasi",$conn);
	while ($bar=mysql_fetch_array($que)) {
		$isi.="<option value='$bar[durasi]'>$bar[durasi]</option>";
	}
	$isi.="</select> Bulan</td>
	</tr>
	<tr>
	";
	$arv = mysql_fetch_array(mysql_query("select * from tb_potongan_admin",$conn));
	$isi.="
	<td height=25 valign=middle class=CellLabel>Pekerjaan <span class=merah></span></td>
	<td height=25 valign=middle class=cellIsian><input name=pekerjaan type=text value='$data[pekerjaan]' size=45 maxlength=50 disabled/></td>
	<td height=25 valign=middle class=CellLabel>Biaya Administrasi & Provisi</td>
	<td height=25 valign=middle class=cellIsian><input type=hidden name='adpro' value='$arv[administrasi]'>
	<select name=adpro disabled><option value='$dt[adpro]'>Ya</option><option value=''>Tidak</option></select></td>
	</tr><tr>
	<td height=25 valign=middle class=CellLabel>No. KTP </td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$data[no_ktp]' size=45 maxlength=50 onKeyup=Angka(this); disabled/></td>
	<td height=25 valign=middle class=CellLabel>Biaya Calon Anggota *</td>
	<td height=25 valign=middle class=cellIsian><select name=bya_ca><option value='$arv[calon_agt]'>Ya</option><option value=''>Tidak</option></select></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>No. Telp </td>
	<td height=25 class=cellIsian><input name=telp type=text value='$data[no_telp]' size=45 maxlength=50 disabled/></td>
	<td height=25 class=CellLabel>Biaya Survey *</td>
	<td height=25 valign=middle class=cellIsian><select name=survey><option value='$arv[survey]'>Ya</option><option value='' selected>Tidak</option></select></td>
	</tr>
	
	<tr>
	<td height=25 class=CellLabel>Alamat rumah </td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4 disabled>$data[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel>Biaya Stopmap</td>
	<td height=25 valign=middle class=cellIsian><select name=stofmap><option value='$arv[stofmap]'>Ya</option><option value=''>Tidak</option></select></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input type=hidden name='jng' value='$data[nama]'><input type=hidden name='kd_nasabah' value='$data[no_anggota]'><input name=submit type=submit value='  Simpan Data  ' onclick=	\"ok=confirm('Yakin data yang dimasukkan sudah benar?');
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
