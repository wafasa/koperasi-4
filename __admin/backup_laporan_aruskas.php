<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
include "counter.php";
$isi.="<div class=kepala>Laporan &raquo; <a href='laporan_aruskas.php'>Laporan Arus Kas </a> </div>";
$isi.="<center>
<form action='' method='post' name='form1'>
<table align='center' id='cari'><tr><td>Cari Laporan</td><td>
<select name='bln' class='TextBox'><option value=''> -- Pilih Bulan --</option>
<option value='01' "; if ($_POST[bln] == '01') { $isi.="selected"; } $isi.=">Januari</option>
<option value='02' "; if ($_POST[bln] == '02') { $isi.="selected"; } $isi.=">Februari</option>
<option value='03' "; if ($_POST[bln] == '03') { $isi.="selected"; } $isi.=">Maret</option>
<option value='04' "; if ($_POST[bln] == '04') { $isi.="selected"; } $isi.=">April</option>
<option value='05' "; if ($_POST[bln] == '05') { $isi.="selected"; } $isi.=">Mei</option>
<option value='06' "; if ($_POST[bln] == '06') { $isi.="selected"; } $isi.=">Juni</option>
<option value='07' "; if ($_POST[bln] == '07') { $isi.="selected"; } $isi.=">Juli</option>
<option value='08' "; if ($_POST[bln] == '08') { $isi.="selected"; } $isi.=">Agustus</option>
<option value='09' "; if ($_POST[bln] == '09') { $isi.="selected"; } $isi.=">September</option>
<option value='10' "; if ($_POST[bln] == '10') { $isi.="selected"; } $isi.=">Oktober</option>
<option value='11' "; if ($_POST[bln] == '11') { $isi.="selected"; } $isi.=">November</option>
<option value='12' "; if ($_POST[bln] == '12') { $isi.="selected"; } $isi.=">Desember</option>
</select>
</td><td><select name='thn' class='TextBox'><option value=''>-- Pilih Tahun --</option>";

$y = date('Y');
for ($i=2005;$i<=2020;$i++) {
$isi.="<option value='$i' "; if ($_POST[thn] == $i) { $isi.="selected"; } $isi.=">$i</option>";
}

$isi.="</select> </td><td><input type='submit' name='cari' value='Search' /></td></tr></table></form><br />
";

$isi.="<table width='99%' align='center' border='0' cellpadding='0' cellspacing='10' id='laporan' >
  <tr>";
    $isi.="<td colspan='2' align='center' class='headlaporan'>LAPORAN ARUS KAS BMT SUMBER BARU EROMOKO <BR />";
    if ((isset($_POST[cari])) and ($_POST[bln] != '') and ($_POST[thn] != '')) {
    $isi.="PER ".strtoupper(bulanindo($_POST[bln]))." TAHUN $_REQUEST[thn]"; 
	$now = "$_POST[thn]-$_POST[bln]";
	$x   = mktime(0, 0, 0, date($_POST[bln])+1, date("d"), date($_POST[thn]));
	$mon = date("Y-m",$x);

	//$isi.="$now";
    }
	else {
	$thn = date('Y');
	$bln = date('m');
	$isi.="$PER ".strtoupper(bulanindo($bln))." TAHUN $thn"; 
	$now = date('Y-m');
	$x   = mktime(0, 0, 0, date("m")+1, date("d")+$selisih, date("Y"));
	$mon = date("Y-m",$x);
	}
	
    $isi.="</td>
  </tr>
  <tr>
    <td width='51%' height='20' align='left' class='data'><strong>ARUS KAS MASUK</strong></td>
    <td width='49%' align='right' class='data'><strong>ARUS KAS KELUAR</strong></td>
  </tr>
  
  <tr>
    <td>
	<table width='100%' border='0' cellspacing='0' cellpadding='0' >
	
	 <tr>";
	 	if ($now == '2007-10') {
	  	$a = mysql_fetch_array(mysql_query("select sisa_kas_total from tb_kas_koperasi where tanggal = (select min(tanggal) from tb_kas_koperasi where tanggal like ('$now%'))",$conn));
		} 
		else if ($now == '2007-12') {
		$a = mysql_fetch_array(mysql_query("select sisa_kas_total from tb_kas_koperasi where tanggal = (select max(tanggal) from tb_kas_koperasi where tanggal like ('2007-11%'))",$conn));
		}else {
		$exp= explode("-",$now);
		$x  = mktime(0, 0, 0, date($exp[1])-1, date("d"), date($exp[0]));
		$day = date("Y-m",$x);
		$isi.="$day";
		$a = mysql_fetch_array(mysql_query("select sisa_kas_total from tb_kas_koperasi where tanggal = (select max(tanggal) from tb_kas_koperasi where tanggal like ('$day%'))",$conn));
		}
        $isi.="<td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Saldo Awal</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($a[sisa_kas_total])."</td>
      </tr>";
		$b = mysql_fetch_array(mysql_query("select sum(penambahan_kas) as tambah_kas from tb_kas_koperasi where tanggal like ('$now%')",$conn));
	$isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Kas</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($b[tambah_kas])."</td>
      </tr>";
      
	    $c = mysql_fetch_array(mysql_query("select sum(jasa) as bahas, sum(denda) as denda from tb_angsuran where tgl_bayar like ('$now%')",$conn));
      $isi.="<tr>
		 
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Pendapatan Bagi Hasil</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'> &nbsp;&nbsp;&nbsp;- Akad Mudharabah Muqayyadah</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($c[bahas])."</td>
      </tr>
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>"; 
	    $d = mysql_fetch_array(mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro where tgl_input like ('$now%')",$conn));
      $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Pendapatan</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>&nbsp;</td>
      </tr>
      
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Jasa Administrasi</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($d[adm])."</td>
      </tr>
   		
	<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Survey Lokasi</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($d[survey])."</td>
      </tr>
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Denda & Penalti</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($c[denda])."</td>
      </tr>	
	  
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Stofmap</td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($d[stofmap])."</td>
      </tr>	
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Setoran</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>";
      
      $e = mysql_fetch_array(mysql_query("select sum(pokok) as angsur, sum(denda) as denda from tb_angsuran where status_lunas = '1' and tgl_bayar like ('$now%')",$conn));
      $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Angsuran dan Pelunasan Akad</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($e[angsur])."</td>
      </tr>";
   	  $f = mysql_fetch_array(mysql_query("select sum(debet) as debet from tb_tab_history where tanggal like ('$now%') and sandi = '1'",$conn));
	  
	$isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Tabungan Amanah</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($f[debet])."</td>
      </tr>
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Piutang</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
      
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Administrasi Calon Anggota</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($d[ca])."</td>
      </tr>";
   	$kdr = mysql_fetch_array(mysql_query("select sum(debet) as kadar_keu from tb_tab_history where sandi = '4' and tanggal like ('$now%')",$conn)); 	
	$isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Kadar Keuntungan Ditahan</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($kdr[kadar_keu])."</td>
      </tr>";
	$total_penerimaan = $a[sisa_kas_total] + $b[tambah_kas] + $c[bahas] + $d[adm] + $d[survey] + $c[denda] + $d[stofmap] + $e[angsur] + $f[debet] + $d[ca] + $kdr[kadar_keu];
      
	  
  	   $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
	  
	  <tr>
        <td class='data'>&nbsp;</td>
        <td height='20' align='left' class='data'><b>Jumlah Penerimaan Kas</b></td>
        <td class='data'>&nbsp;</td>
        <td align='right' class='data'><b>".duit($total_penerimaan)."</b></td>
      </tr>
       <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>   
   
    </table>
    </td>
     

    <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
	  	 <td width='3%' class='data'>&nbsp;</td>
        <td height='20' colspan='4' align='left' class=data><b>Penyaluran Pembiayaan</b></td>
        </tr>";
   	  $g = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as pinjaman from tb_pinjaman where tgl_pinjam like ('$now%')",$conn));
      $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Akad Mudharabah Muqayyadah</td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($g[pinjaman])."</td>
      </tr>
		
		<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>";
	  $h =  mysql_fetch_array(mysql_query("select sum(debet) as bunga from tb_tab_history where tanggal like ('$now%') and sandi = '4'",$conn));
     $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Pembayaran Bagi Hasil</td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'></td>
      </tr>
	  
	  <tr>
	 <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Tabungan Amanah</td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($h[bunga])."</td>
      </tr>
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  ";
	  $i =  mysql_fetch_array(mysql_query("select sum(kredit) as kredit from tb_tab_history where tanggal like ('$now%') and sandi = '2'",$conn));
     
	 $isi.="
     <tr>
    
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penyerahan</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
    
      <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Tabungan Amanah </td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($i[kredit])."</td>
      </tr>
       <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>";
	  $gj = mysql_fetch_array(mysql_query("select sum(nominal) as gaji from tb_operasional where kd_jenis = '1' and tanggal like ('$now%')",$conn));
	  $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Pembayaran Beban</td>
        <td width='23%' class='data'></td>
        <td width='24%' align='right' class='data'></td>
      </tr>
    ";
	 // $jml = mysql_fetch_array(mysql_query("select sum(nominal) as atk from tb_operasional where kd_jenis = '2' and tanggal like ('$now%')",$conn));
	  $isi.="
      <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Gaji Karyawan</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($gj[gaji])."</td>
      </tr>
     ";
	  $atk = mysql_fetch_array(mysql_query("select sum(nominal) as atk from tb_operasional where kd_jenis = '2' and tanggal like ('$now%')",$conn));
	  $isi.="
	 <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Alat Tulis Kantor</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($atk[atk])."</td>
      </tr>
	  ";
	  $pk = mysql_fetch_array(mysql_query("select sum(nominal) as pk from tb_operasional where kd_jenis = '3' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Perlengkapan Kantor</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($pk[pk])."</td>
      </tr>
	  
	  ";
	  $telp = mysql_fetch_array(mysql_query("select sum(nominal) as telepon from tb_operasional where kd_jenis = '9' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Telepon</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($telp[telepon])."</td>
      </tr>
	  
	  ";
	  $krn = mysql_fetch_array(mysql_query("select sum(nominal) as koran from tb_operasional where kd_jenis = '5' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Koran</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($krn[koran])."</td>
      </tr>
	  
	  ";
	  $pd = mysql_fetch_array(mysql_query("select sum(nominal) as perj_dinas from tb_operasional where kd_jenis = '6' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Perjalanan Dinas</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($pd[perj_dinas])."</td>
      </tr>
	  
	  ";
	  $listrik = mysql_fetch_array(mysql_query("select sum(nominal) as listrik from tb_operasional where kd_jenis = '7' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Listrik</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($listrik[listrik])."</td>
      </tr>
	  ";
	  $sumb = mysql_fetch_array(mysql_query("select sum(nominal) as sumbangan from tb_operasional where kd_jenis = '8' and tanggal like ('$now%')",$conn));
	  $isi.="
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Sumbangan</td>
        <td width='23%' align='right' class='data'></td>
        <td width='24%' align='right' class='data'>".duit($sum[sumbangan])."</td>
      </tr>";
	  $kon = mysql_fetch_array(mysql_query("select sum(nominal) as konsumsi from tb_operasional where kd_jenis = '4' and tanggal like ('$now%')",$conn));
	  $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Konsumsi</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data' align=right>".duit($kon[konsumsi])."</td>
      </tr>";
	  $lain = mysql_fetch_array(mysql_query("select sum(nominal) as lain_lain from tb_operasional where kd_jenis = '10' and tanggal like ('$now%')",$conn));
	  $isi.="<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Lain-lain</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data' align=right>".duit($lain[lain_lain])."</td>
      </tr>
	  
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
	  ";
	  $total_pengeluaran = $i[kredit] + $g[pinjaman] + $h[bunga] + $gj[gaji] + $atk[atk] + $pk[pk] + $telp[telepon] + $krn[koran] + $pd[perj_dinas] + $listrik[listrik] + $sumb[sumbangan] + $kon[konsumsi] + $lain[lain_lain];
	  
      $isi.="<tr>
     
        <td class='data'>&nbsp;</td>
        <td height='20' align='left' class='data'><b>Jumlah Pengeluaran Kas</b></td>
        <td class='data'>&nbsp;</td>
        <td align='right' class='data'><b>".duit($total_pengeluaran)."</b></td>
      </tr>
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr> 
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>SALDO AKHIR</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'><b>".duit($total_penerimaan-$total_pengeluaran)."</td>
      </tr> 
     
    </table></td>
  </tr>
  </table>";
  /*$prth = mysql_query("select no_kas,sisa_kas_total from tb_kas_koperasi where tanggal between '2007-12-02' and '2007-12-31'",$conn);
  while ($data = mysql_fetch_array($prth)) {
  	$kasbr = $data[sisa_kas_total] - 899000;
  	$ss = mysql_query("update tb_kas_koperasi set sisa_kas_total = '$kasbr' where no_kas = '$data[no_kas]'");
  }*/
$isi.="<a class=kecil href='cetak_lap_aruskas.php?tgl=$now' target=_blank ><img src='../images/cetak.png' width=15px> cetak</a>";
include_once "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>