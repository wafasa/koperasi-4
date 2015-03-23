<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
?>
<link rel=stylesheet href=../__css/mystyle.css>
<body onload=window.print()>
<?

echo "<table width='99%' align='center' border='0' cellpadding='0' cellspacing='10' id='laporan' >
  <tr>";
    echo "<td colspan='2' align='center' class='headlaporan'>LAPORAN ARUS KAS BMT SUMBER BARU EROMOKO <BR />";
    if ((isset($_POST[cari])) and ($_POST[bln] != '') and ($_POST[thn] != '')) {
    echo "PER ".strtoupper(bulanindo($_POST[bln]))." TAHUN $_REQUEST[thn]"; 
	$now = '$_REQUEST[thn]-$_POST[bln]';
    }
	else {
	$ymd = explode("-",$_GET[tgl]);
	$thn = date('Y');
	$bln = date('m');
	echo "$PER ".strtoupper(bulanindo($ymd[1]))." TAHUN $ymd[0]"; 
	$now = $_GET[tgl];
	}
	
    echo "</td>
  </tr>
  <tr>
    <td width='51%' height='20' align='left' class='data'><strong>ARUS KAS MASUK</strong></td>
    <td width='49%' align='right' class='data'><strong>ARUS KAS KELUAR</strong></td>
  </tr>
  
  <tr>
    <td>
	<table width='100%' border='0' cellspacing='0' cellpadding='0' >
	
	 <tr>";
	 	$exp= explode("-",$now);
		if ($exp[1] == '01') {
			$th = $exp[0] - 1;
			$awl= "$th-12";
		} else if ($exp[1] == '02') {
			$awl= "$exp[0]-01";
		} else if ($exp[1] == '03') {
			$awl= "$exp[0]-02";
		} else if ($exp[1] == '04') {
			$awl= "$exp[0]-03";
		} else if ($exp[1] == '05') {
			$awl= "$exp[0]-04";
		} else if ($exp[1] == '06') {
			$awl= "$exp[0]-05";
		} else if ($exp[1] == '07') {
			$awl= "$exp[0]-06";
		} else if ($exp[1] == '08') {
			$awl= "$exp[0]-07";
		} else if ($exp[1] == '09') {
			$awl= "$exp[0]-08";
		} else if ($exp[1] == '10') {
			$awl= "$exp[0]-09";
		} else if ($exp[1] == '11') {
			$awl= "$exp[0]-10";
		} else if ($exp[1] == '12') {
			$awl= "$exp[0]-11";
		}
		$a = mysql_fetch_array(mysql_query("select sisa_kas_total from tb_kas_koperasi where tanggal = (select max(tanggal) from tb_kas_koperasi where tanggal like ('$awl%'))",$conn));
        echo "<td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Saldo Awal</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($a[sisa_kas_total])."</td>
      </tr>";
		$b = mysql_fetch_array(mysql_query("select sum(penambahan_kas) as tambah_kas from tb_kas_koperasi where tanggal like ('$now%')",$conn));
	echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Kas</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($b[tambah_kas])."</td>
      </tr>";
      
	    $c = mysql_fetch_array(mysql_query("select sum(jasa) as bahas, sum(denda) as denda from tb_angsuran where tgl_bayar like ('$now%')",$conn));
      echo "<tr>
		 
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Pendapatan Bagi Hasil</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'> &nbsp;&nbsp;&nbsp;- Akad Mudharabah Muqayyadah</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($c[bahas])."</td>
      </tr>
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>"; 
	    $d = mysql_fetch_array(mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro where tgl_input like ('$now%')",$conn));
      echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penerimaan Pendapatan</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>&nbsp;</td>
      </tr>
      
      
      <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Jasa Administrasi</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($d[adm])."</td>
      </tr>
   		
	<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Survey Lokasi</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($d[survey])."</td>
      </tr>
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Denda & Penalti</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($c[denda])."</td>
      </tr>	
	  
	  <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Stofmap</td>
        <td width='23%' class='data'>&nbsp;</td>
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
      echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Angsuran dan Pelunasan Akad</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($e[angsur])."</td>
      </tr>";
   	  $f = mysql_fetch_array(mysql_query("select sum(debet) as debet from tb_tab_history where tanggal like ('$now%') and sandi = '1'",$conn));
	echo "<tr>
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
	echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Kadar Keuntungan Ditahan</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($kdr[kadar_keu])."</td>
      </tr>";
	$total_penerimaan = $a[sisa_kas_total] + $b[tambah_kas] + $c[bahas] + $d[adm] + $d[survey] + $c[denda] + $d[stofmap] + $e[angsur] + $f[debet] + $d[ca] + $kdr[kadar_keu];
      
	  
  	   echo "<tr>
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
      echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Akad Mudharabah Muqayyadah</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($g[pinjaman])."</td>
      </tr>
		
		<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>";
	  $h =  mysql_fetch_array(mysql_query("select sum(debet) as bunga from tb_tab_history where tanggal like ('$now%') and sandi = '4'",$conn));
     echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Pembayaran Bagi Hasil</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>&nbsp;</td>
      </tr>
	  
	  <tr>
	 <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Tabungan Amanah</td>
        <td width='23%' class='data'>&nbsp;</td>
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
     
	 echo "
     <tr>
    
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Penyerahan</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>
    
      <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Tabungan Amanah </td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($i[kredit])."</td>
      </tr>
       <tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data'>&nbsp;</td>
      </tr>";
	  $gj = mysql_fetch_array(mysql_query("select sum(nominal) as gaji from tb_operasional where kd_jenis = '1' and tanggal like ('$now%')",$conn));
	  echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'><b>Pembayaran Beban</td>
        <td width='23%' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>&nbsp;</td>
      </tr>
    ";
	 // $jml = mysql_fetch_array(mysql_query("select sum(nominal) as atk from tb_operasional where kd_jenis = '2' and tanggal like ('$now%')",$conn));
	  echo "
      <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Gaji Karyawan</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>	".duit($gj[gaji])."</td>
      </tr>
     ";
	  $atk = mysql_fetch_array(mysql_query("select sum(nominal) as atk from tb_operasional where kd_jenis = '2' and tanggal like ('$now%')",$conn));
	  echo "
	 <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Alat Tulis Kantor</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($atk[atk])."</td>
      </tr>
	  ";
	  $pk = mysql_fetch_array(mysql_query("select sum(nominal) as pk from tb_operasional where kd_jenis = '3' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Perlengkapan Kantor</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($pk[pk])."</td>
      </tr>
	  
	  ";
	  $telp = mysql_fetch_array(mysql_query("select sum(nominal) as telepon from tb_operasional where kd_jenis = '9' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Telepon</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($telp[telepon])."</td>
      </tr>
	  
	  ";
	  $krn = mysql_fetch_array(mysql_query("select sum(nominal) as koran from tb_operasional where kd_jenis = '5' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Koran</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($krn[koran])."</td>
      </tr>
	  
	  ";
	  $pd = mysql_fetch_array(mysql_query("select sum(nominal) as perj_dinas from tb_operasional where kd_jenis = '6' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Perjalanan Dinas</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($pd[perj_dinas])."</td>
      </tr>
	  
	  ";
	  $listrik = mysql_fetch_array(mysql_query("select sum(nominal) as listrik from tb_operasional where kd_jenis = '7' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Listrik</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($listrik[listrik])."</td>
      </tr>
	  ";
	  $sumb = mysql_fetch_array(mysql_query("select sum(nominal) as sumbangan from tb_operasional where kd_jenis = '8' and tanggal like ('$now%')",$conn));
	  echo "
	  <tr>
     
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Sumbangan</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' align='right' class='data'>".duit($sum[sumbangan])."</td>
      </tr>";
	  $kon = mysql_fetch_array(mysql_query("select sum(nominal) as konsumsi from tb_operasional where kd_jenis = '4' and tanggal like ('$now%')",$conn));
	  echo "<tr>
        <td width='3%' class='data'>&nbsp;</td>
        <td width='50%' height='20' align='left' class='data'>&nbsp;&nbsp;&nbsp;- Konsumsi</td>
        <td width='23%' align='right' class='data'>&nbsp;</td>
        <td width='24%' class='data' align=right>".duit($kon[konsumsi])."</td>
      </tr>";
	  $lain = mysql_fetch_array(mysql_query("select sum(nominal) as lain_lain from tb_operasional where kd_jenis = '10' and tanggal like ('$now%')",$conn));
	  echo "<tr>
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
	  
      echo "<tr>
     
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
//include_once "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>