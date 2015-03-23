<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
if (isset($_POST[tgl])) {
	$tgl = $_POST[tgl];
	$tgg = tgdb($_POST[tgl]);
}
else {
	$tgl = date("d/m/Y");
	$tgg = $_GET[tgl];
}
?>
<style type="text/css">
table { font-family:tahoma, "Trebuchet MS";
	font-size:11px;
	width:100%;
	color:#000000;
}
caption {font-weight:bold; }
td, th {padding:2px; }
p {line-height:5px;
}
hr {border:2px groove #E1E1FF;
}
.border {
	padding:10px;
	border:2px outset #000000;
}
.bor {
	border:1px solid #00FFFF;
}
</style>
<body onLoad="window.print()">
<?

echo "<table cellpadding=0 cellspacing=0 border=1 id=table class=sortable>
		<thead><caption>LAPORAN KAS HARIAN TANGGAL ".TglLap($_GET[tgl])."</caption>
			<tr>
				<th>NO</th>
				<th>TANGGAL</th>
				<th>NO.REKENING</th>
				<th>NAMA</th>
				<th>KETERANGAN</th>
				<th>PENDAPATAN</th>
				<th>PENGELUARAN</th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	
/* +++++++++++++hitung kas awal ++++++++++++++++++++++*/
$sqq = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where tanggal = '$tgg'",$conn));
		$no_kas = $sqq[no_kas] - 1;
		$sqq = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where no_kas = '$no_kas'",$conn));
		echo "
		<tr>
		<td align=center>#</td>
		<td align=center>".TglIndo($tgg)."</td>
		<td><i> Rek. Koperasi</td>
		<td><i>Koperasi</i></td>
		<td>SALDO KOPERASI TGL ".TglIndo($sqq[tanggal])."</td>
		<td><b>".duit($sqq[sisa_kas_total]+$sqq[penambahan_kas])."</td>
		<td align=center>-</td>
		</tr>
		";

/* +++++++++++++hitung angsuran ++++++++++++++++++++++*/
$setor = mysql_query("select n.rek_pinjaman, n.no_rekening, n.nama, p.*, a.* from tb_nasabah n, tb_pinjaman p, tb_angsuran a where p.no_anggota = n.no_anggota and p.no_pinjaman = a.no_pinjaman and a.status_lunas = '1' and a.tgl_bayar = '$tgg'",$conn);
while ($bar = mysql_fetch_array($setor)) {
$tgl = TglIndo($bar[tgl_bayar]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>$bar[rek_pinjaman]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>ANGSURAN KE $bar[angsuran_ke] &nbsp;&nbsp;&nbsp; [Pokok : ".nominal($bar[pokok]).", Bahas : ".nominal($bar[jasa])." ]</td>
		<td>".duit($bar[pokok]+$bar[jasa])."</td>
		<td align=center>-</td>
		</tr>
		";
	$no +=1;
}
	

/* +++++++++++++hitung setoran tabungan ++++++++++++++++++++++*/
$sql = mysql_query("select nt.nama, nt.no_rekening, th.* from tb_nasabah_tabungan nt, tb_tabungan t, tb_tab_history th where th.no_tabungan = t.no_tabungan and t.no_agt_tab = nt.no_agt_tab and th.sandi = '1' and th.tanggal = '$tgg'",$conn);
		
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>$bar[no_rekening]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>SETORAN TABUNGAN</td>
		<td>".duit($bar[debet])."</td>
		<td align=center>-</td>
		</tr>
		";
	$no +=1;
	}


/* +++++++++++++hitung Penarikan tabungan ++++++++++++++++++++++*/
$sql = mysql_query("select nt.nama, nt.no_rekening, th.* from tb_nasabah_tabungan nt, tb_tabungan t, tb_tab_history th where th.no_tabungan = t.no_tabungan and t.no_agt_tab = nt.no_agt_tab and th.sandi = '2' and th.tanggal = '$tgg'",$conn);
		
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>$bar[no_rekening]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>PENARIKAN TABUNGAN</td>
		<td align=center>-</td>
		<td>".duit($bar[kredit])."</td>
		</tr>
		";
	$no +=1;
	}
/* +++++++++++++hitung Beban-beban operasional ++++++++++++++++++++++*/

$sql = mysql_query("select * from tb_operasional where tanggal = '$tgg'",$conn);
		
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td><i>Rek. Koperasi</td>
		<td><i>Koperasi</td>
		<td>PENGELUARAN (".strtoupper($bar[keterangan]).")</td>
		<td align=center>-</td>
		<td>".duit($bar[nominal])."</td>
		</tr>
		";
	$no +=1;
	}	
/* +++++++++++++hitung simpanan wajib ++++++++++++++++++++++*/
$sql = mysql_query("select a.nama, a.no_agt, tw.* from tb_anggota a, tb_trans_sw tw, tb_simpanan_wajib sw where a.no_agt = sw.no_agt and tw.no_sw = sw.no_sw and tw.tanggal = '$tgg'",$conn);
while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td><i>anggota ($bar[no_agt])</i></td>
		<td>".strtoupper($bar[nama])."</td>
		<td>SIMPANAN WAJIB ".strtoupper($bar[nama])."</td>
		<td>".duit($bar[jml_simw])."</td>
		<td align=center>-</td>
		</tr>";
	$no +=1;
}

/* +++++++++++++hitung simpanan pokok ++++++++++++++++++++++*/
$sql = mysql_query("select a.nama, a.no_agt, a.tgl_daftar, sp.tersetor_p from tb_anggota a, tb_simpanan_pokok sp where a.no_agt = sp.no_agt and a.tgl_daftar = '$tgg'",$conn);
while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_daftar]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td><i>anggota ($bar[no_agt])</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>SIMPANAN POKOK ".strtoupper($bar[nama])."</td>
		<td>".duit($bar[tersetor_p])."</td>
		<td align=center>-</td>
		</tr>";
	$no +=1;
}

/* +++++++++++++hitung pembiayaan ++++++++++++++++++++++*/
$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.status_lunas = '0' and p.tgl_pinjam = '$tgg'",$conn);
while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_pinjam]);
		echo "
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>$bar[rek_pinjaman]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>PEMBIAYAAN AKAD ".strtoupper($bar[nama])."</td>
		<td align=center>-</td>
		<td>".duit($bar[jml_pinjaman])."</td>
		</tr>";
	$no +=1;
}
$adm = mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro where tgl_input = '$tgg'",$conn);
$no = $no;
$row = mysql_fetch_array($adm);
$jml = mysql_num_rows($adm);
if ($row[adm] != '') {
		echo "
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center>&nbsp;</td>
		<td align=center>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;&nbsp; - Administrasi</td>
		<td>".duit($row[adm])."</td>
		<td align=center>-</td>
		</tr>";
		echo "
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center>&nbsp;</td>
		<td align=center>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;&nbsp; - Administrasi Calon Anggota</td>
		<td>".duit($row[ca])."</td>
		<td align=center>-</td>
		</tr>";
		echo "
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center>&nbsp;</td>
		<td align=center>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;&nbsp; - Stofmap</td>
		<td>".duit($row[stofmap])."</td>
		<td align=center>-</td>
		</tr>";
		echo "
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center>&nbsp;</td>
		<td align=center>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;&nbsp; - Survey</td>
		<td>".duit($row[survey])."</td>
		<td align=center>-</td>
		</tr>";
}
	//++++++++++++++++++++++++++++++++++++++++++++++jumlah total ++++++++++++++++++++++++++++++++++++++++++++
$a = mysql_fetch_array(mysql_query("select sum(pokok) as pokok, sum(jasa) as jasa, sum(denda) as denda from tb_angsuran where status_lunas = '1' and tgl_bayar = '$tgg'",$conn));
$b = mysql_fetch_array(mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro where tgl_input = '$tgg'",$conn)); 
	$adpro = $b[adm] + $b[ca] + $b[survey] + $b[stofmap];
$c = mysql_fetch_array(mysql_query("select sum(debet) as debet from tb_tab_history where tanggal = '$tgg' and sandi = '1'",$conn));
$d = mysql_fetch_array(mysql_query("select sum(jml_simw) as simw from tb_trans_sw where tanggal = '$tgg'",$conn));
$e = mysql_fetch_array(mysql_query("select sum(sp.tersetor_p) as simp from tb_anggota a, tb_simpanan_pokok sp where a.no_agt = sp.no_agt and a.tgl_daftar = '$tgg'",$conn));
$f= mysql_fetch_array(mysql_query("select * from tb_kas_koperasi order by no_kas desc limit 0, 1",$conn));


$k = mysql_fetch_array(mysql_query("select sum(nominal) as opp from tb_operasional where tanggal = '$tgg'",$conn));
$l = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as pinjaman from tb_pinjaman where tgl_pinjam = '$tgg'",$conn));
$m = mysql_fetch_array(mysql_query("select sum(kredit) as kredit from tb_tab_history where tanggal = '$tgg' and sandi = '2'",$conn));

$total_input = $a[pokok] + $a[jasa] + $a[denda] + $adpro + $c[debet] + $sqq[sisa_kas_total] + $sqq[penambahan_kas];
$total_output= $k[opp] + $l[pinjaman] + $m[kredit];
	echo "</tbody>
	<tr><td colspan=5>JUMLAH</td><td><b>".duit($total_input)."</td><td><b>".duit($total_output)."</td></tr>
	</table>";
$sisa = $total_input - $total_output;
echo "&nbsp;<form action='' method=post><input type=hidden name='saldo' value='$sisa'>
<table style='width:250px;'><tr><td>Total Pendapatan</td><td>:</td><td align=right>".duit($total_input)."</td></tr>
<tr><td>Total Pengeluaran</td><td>:</td><td align=right>".duit($total_output)."</td></tr>
<tr><td colspan=3>________________________________ __</td></tr>
<tr><td><b>Sisa Kas Koperasi</td><td>:</td><td align=right><b>".duit($total_input-$total_output)."</td></tr>
</table></form>
";

}
else {
	header("location:../login.php");
}
?>