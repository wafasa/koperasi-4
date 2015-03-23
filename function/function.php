<?
function alert_edit($sql) {
	if ($sql) {
		echo "<script>alert('Data Berhasil Diubah')</script>";
		echo "<script>history.go(-2)</script>";
	}
	else {
		echo "<script>alert('Data Gagal Diubah')</script>";
	}
}
function success($sql) {
	if ($sql) {
		echo "<script>alert('Data Berhasil Ditambahkan')</script>";
		echo "<meta http-equiv=refresh content=0;url=form_input_tab.php>";
	}
	else {
		echo "<script>alert('Data Gagal Ditambahkan')</script>";
	}
}

function bulanindo($bln){
	if($bln == "01") { $bulane = "Januari"; }
	if($bln == "02") { $bulane = "Februari"; }
	if($bln == "03") { $bulane = "Maret"; }
	if($bln == "04") { $bulane = "April"; }
	if($bln == "05") { $bulane = "Mei"; }
	if($bln == "06") { $bulane = "Juni"; }
	if($bln == "07") { $bulane = "Juli"; }
	if($bln == "08") { $bulane = "Agustus"; }
	if($bln == "09") { $bulane = "September"; }
	if($bln == "10") { $bulane = "Oktober"; }
	if($bln == "11") { $bulane = "November"; }
	if($bln == "12") { $bulane = "Desember"; }
	return $bulane;
}

function HariIni(){
	$hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
	$bulan = array(1=> "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November",
			 "Desember");
	$tgl = date("d");
	$bln = date("n");
	$hr = date("w");
	$thn = date("Y");
	$sekarang = $hari[$hr].", ".$tgl." ".$bulan[$bln]." ".$thn;
	return $sekarang;
}


function alert_transaksi($sql) {
	if ($sql) {
		echo "<script>alert('Data Transaksi Berhasil Dilakukan')</script>";
		echo "<meta http-equiv=refresh content=0;url=form_penarikan_tab.php>";
	}
	else {
		echo "<script>alert('Data Transasi Gagal Dilakukan')</script>";
	}
}

function new_tab($no_agt,$sts,$debet) {
	$exp = explode(".",$debet);
	$exp = $exp[0] . $exp[1] . $exp[2] . $exp[3] . $exp[4];
	$sql = mysql_query("insert into tb_tabungan values ('','$no_agt',now(),'0','$exp')");
	$que = mysql_query("insert into tb_tab_history values ('',(select no_tabungan from tb_tabungan where no_anggota = '$no_agt'),now(),'$exp','0','1','$exp')");
	if ($sql) {
		echo "<script>alert('Data Berhasil Ditambahkan')</script>";
		echo "<meta http-equiv=refresh content=0;url=form_input_tab.php>";
	}
	else {
		echo "<script>alert('Data Gagal Ditambahkan')</script>";
	}
}

function duit($nom) {
	$fulus = "Rp. ".number_format($nom, 0, '','.');
	return $fulus;
}

function nominal($jml) {
	$fulus = number_format($jml, 0, '','.');
	return $fulus;
}

function add_tab($no_agt,$debet) {
	$exp = explode(".",$debet);
	$exp = $exp[0] . $exp[1] . $exp[2] . $exp[3] . $exp[4];
	$dt  = mysql_fetch_array(mysql_query("select saldo from tb_tab_history where no_tabungan = (select no_tabungan from tb_tabungan where no_agt_tab = '$no_agt') order by no_history desc limit 0, 1"));
	$new_saldo = $dt[saldo] + $exp;
	$sql = mysql_query("insert into tb_tab_history values ('',(select no_tabungan from tb_tabungan where no_agt_tab = '$no_agt'),now(),'$exp','0','1','$new_saldo')");
	$que = mysql_query("update tb_tabungan set tanggal_update = now(), saldo = '$new_saldo' where no_agt_tab = '$no_agt'");
	success($sql);
	
}

function min_tab($no_agt,$kredit) {
	$exp = explode(".",$kredit);
	$exp = $exp[0] . $exp[1] . $exp[2] . $exp[3] . $exp[4];
	$dt  = mysql_fetch_array(mysql_query("select saldo from tb_tab_history where no_tabungan = (select no_tabungan from tb_tabungan where no_agt_tab = '$no_agt') order by no_history desc limit 0, 1"));
	$new_saldo = $dt[saldo] - $exp;
	$sql = mysql_query("insert into tb_tab_history values ('',(select no_tabungan from tb_tabungan where no_agt_tab = '$no_agt'),now(),'0','$exp','2','$new_saldo')");
	$que = mysql_query("update tb_tabungan set tanggal_update = now(), saldo = '$new_saldo' where no_agt_tab = '$no_agt'");
	alert_transaksi($sql);
	
}

function gabung($duit) {
	$mon = explode(".",$duit);
	$mon = $mon[0] . $mon[1] . $mon[2] . $mon[3] . $mon[4] . $mon[5];
	return $mon;
}

function TglIndo($tgl) {
	$ti = explode("-",$tgl);
	$ti = "$ti[2]/$ti[1]/$ti[0]";
	return $ti;
}

function TglLap($tgl) {
	$ti = explode("-",$tgl);
	$ti = "$ti[2]-$ti[1]-$ti[0]";
	return $ti;
}

function tgdb($tanggal){
	$kar = explode("/",$tanggal);
	$tgl = "$kar[2]-$kar[1]-$kar[0]";
	return $tgl;
}

function upp($word) {
	$kata = strtoupper($word);
	return $kata;
}

function ucw($word) {
	$kata = ucwords($word);
	return $kata;
}

function Inisial($ling) {
$inisial = "<div><table id=inisial width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
	<td width=\"3%\" align=\"center\" id=\"alpha_1\" >
	<a href=\"$ling?inisial=A\" class=\"menukiri\">A</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_2\" >
	<a href=\"$ling?inisial=B\" class=\"menukiri\">B</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_3\" >
	<a href=\"$ling?inisial=C\" class=\"menukiri\">C</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_4\" >
	<a href=\"$ling?inisial=D\" class=\"menukiri\">D</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_5\" >
	<a href=\"$ling?inisial=E\" class=\"menukiri\">E</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_6\" >
	<a href=\"$ling?inisial=F\" class=\"menukiri\">F</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_7\" >
	<a href=\"$ling?inisial=G\" class=\"menukiri\">G</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_8\" >
	<a href=\"$ling?inisial=H\" class=\"menukiri\">H</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_9\" >
	<a href=\"$ling?inisial=I\" class=\"menukiri\">I</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_10\" >
	<a href=\"$ling?inisial=J\" class=\"menukiri\">J</a></td>
	<td width=\"3%\" align=\"center\" id=\"alpha_11\" >
	<a href=\"$ling?inisial=K\" class=\"menukiri\">K</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_12\" >
	<a href=\"$ling?inisial=L\" class=\"menukiri\">L</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_13\" >
	<a href=\"$ling?inisial=M\" class=\"menukiri\">M</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_14\" >
	<a href=\"$ling?inisial=N\" class=\"menukiri\">N</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_15\" >
	<a href=\"$ling?inisial=O\" class=\"menukiri\">O</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_16\" >
	<a href=\"$ling?inisial=P\" class=\"menukiri\">P</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_17\" >
	<a href=\"$ling?inisial=Q\" class=\"menukiri\">Q</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_18\" >
	<a href=\"$ling?inisial=R\" class=\"menukiri\">R</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_19\" >
	<a href=\"$ling?inisial=S\" class=\"menukiri\">S</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_20\" >
	<a href=\"$ling?inisial=T\" class=\"menukiri\">T</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_21\" >
	<a href=\"$ling?inisial=U\" class=\"menukiri\">U</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_22\" >
	<a href=\"$ling?inisial=V\" class=\"menukiri\">V</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_23\" >
	<a href=\"$ling?inisial=W\" class=\"menukiri\">W</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_24\" >
	<a href=\"$ling?inisial=X\" class=\"menukiri\">X</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_25\" >
	<a href=\"$ling?inisial=Y\" class=\"menukiri\">Y</a></td>
	<td width=\"4%\" align=\"center\" id=\"alpha_26\" >
	<a href=\"$ling?inisial=Z\" class=\"menukiri\">Z</a></td>
  </tr>
</table></div>";
return $inisial;
}

function nextmont($tgl) {
	$selisih = (strtotime(date("Y-m-d")) - strtotime($tgl))/24/60/60;
	$x   = mktime(0, 0, 0, date("m")+$selisih, date("d"), date("Y"));
	$day = date("l",$x);
	$newday = getHari($day);
	return $newday;
}

function flat($jml,$lama,$agt) {
	$jum  = gabung($jml);
	$data = mysql_fetch_array(mysql_query("select * from tb_jasa where jenis = '1'"));
	$jasa = $jum * ($data[jasa]/100);
	$pokok= ceil($jum / $lama);
	$adm  = $jum * 0.03;
	$janji= ($pokok * $lama) + ($jasa * $lama);
	$angs = $jasa + $pokok;
	$varia= mktime(0, 0, 0, date("m")+$lama, date("d"), date("Y"));
	$tempo= date("Y-m-d",$varia);
	if ($jum <= 2000000) {
		//$pokok = ceil($jum/12);
		$sql = mysql_query("insert into tb_pinjaman values ('','$agt',now(),'$tempo','$jum','$janji','$angs','$pokok','$jasa','1','12','$janji','0')");
		for ($i=1; $i<=$lama;$i++) {
		$x= mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
		$y= date("Y-m-d",$x);
		$newsisa = $pokok * ($lama - $i);
		$que = mysql_query("insert into tb_angsuran values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0,1),'$i','$y','','$pokok','$jasa','$angs','$newsisa','0','0')");
		}
	}
	else if ($jum > 2000000) {
		$sql = mysql_query("insert into tb_pinjaman values ('','$agt',now(),'$tempo','$jum','$janji','$angs','$pokok','$jasa','1','$lama','$janji','0')");
		for ($i=1; $i<=$lama;$i++) {
		$x= mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
		$y= date("Y-m-d",$x);
		$newsisa = $pokok * ($lama - $i);
		$isi.="'$i','$y','','$pokok','$jasa','$angs','$newsisa','0','0'";
		$que = mysql_query("insert into tb_angsuran values ('',(select no_pinjaman from tb_pinjaman order by no_pinjaman desc limit 0,1),'$i','$y','','$pokok','$jasa','$angs','$newsisa','0','0')");
		}
	}
	//echo "<meta http-equiv=refresh content=0;url=list_anggota.php>";
}

function inuitas($agt,$jml,$lama) {
	$jum  = gabung($jml);
	$data = mysql_fetch_array(mysql_query("select * from tb_jasa where jenis = '2'"));
	$jasa = $data[jasa]/100;
	$var1 = $jum * $jasa;
	$pmbg1= pow((1+$jasa),$lama);
	$pmbg2= 1-(1/$pmbg1);
	$jml_angsuran = floor($var1 / $pmbg2); // total angsuran
	$pokok= $jml_angsuran - $var1;
	$sisa1= $jum - $pokok;
	$janji= $jml_angsuran * $lama;
	
	$y    = mktime(0, 0, 0, date("m")+$lama, date("d"), date("Y"));
	$tgg  = date("Y-m-d",$y);
	
	$x    = mktime(0, 0, 0, date("m")+1, date("d"), date("Y"));
	$tgl  = date("Y-m-d",$x);
	if ($jml <= 2000000) {
		$que = mysql_query("insert into tb_pinjaman values ('',now(),'$tgg','$jum','$janji','$jml','$pokok','$var1','2','12','0')");
	}
	else if ($jml >= 2500000) {
		$que = mysql_query("insert into tb_pinjaman values ('',now(),'$tempo','$jum','$janji','$angs','$pokok','$jasa','1','$lama','0')");
	}
	$sql  = mysql_query("insert into tb_angsuran values ('','$tgl','','$pokok','$var1','$jml_angsuran','$sisa1')");
	for ($i=2;$i<=$lama;$i++) {
	$x    = mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
	$tgl  = date("Y-m-d",$x);
	//$dt	  = mysql_fetch_array(mysql_query("select sisa_pokok from tb_angsuran 
	//$sql  = mysql_query("insert into tb_angsuran values ('','$tgl','',''
	}
	
	
}
?>