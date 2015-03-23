<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
if (isset($_POST[tgl])) {
	$tgl = $_POST[tgl];
	$tgg = tgdb($_POST[tgl]);
}
else if ($_GET[tgl] != '') {
	$tgl = $_GET[tgl];
	$tgg = tgdb($_GET[tgl]);
}
else {
	$tgl = date("d/m/Y");
	$tgg = date("Y-m-d");
	
}
$isi.="<div class=kepala>Laporan &raquo; <a href='transaksi_kas_harian.php'>Transaksi Kas Harian </a> <a class=kecil href='cetak_kas_harian.php?tgl=$tgg' target=_blank ><img src='../images/cetak.png' width=15px> cetak</a></div>";

$isi.="<center>
	
	<table align=center id=cari><tr><td></td><td></td>
	<td>";
	$isi.="<form action='' method=post><input type=hidden name=newkat value='$_POST[kat]'><input type=hidden name=kat value='$_POST[kat]'>";
	$isi.="&nbsp;Tanggal &nbsp;<input name=tgl type=text class=TextBox id=tgl onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$tgl' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl,'dd/mm/yyyy',this) />
	</td><td><input type=submit name=cari value='   Search   ' $sts></td></tr></table>
	</form></center>
<br>";


$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>TANGGAL</h3></th>
				<th><h3>NO.REKENING</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>KETERANGAN</h3></th>
				<th><h3>PENDAPATAN</h3></th>
				<th><h3>PENGELUARAN</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	
/* +++++++++++++hitung kas awal ++++++++++++++++++++++*/
		
		$tung = date("d/m/Y");
		if ((isset($_POST[tgl])) and ($_POST[tgl] != $tung)){
		$ssl = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where tanggal = '$tgg'",$conn));
		$no_kas=$ssl[no_kas] - 1;
		$sqq = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where no_kas = '$no_kas'",$conn));
		if ($ssl[sisa_kas_total] == 0) {
			echo "<script>alert('Tidak ada transaksi pada Tanggal $tgl')</script>";
			
		}
		} else {
		$ssl = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where tanggal = '$tgg'",$conn));
			if ($ssl[sisa_kas_total] == '') {
			$sqe = mysql_fetch_array(mysql_query("select max(no_kas) as no_kas, sisa_kas_total from tb_kas_koperasi where tanggal < '$tgg' ",$conn));
			$sqq = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where no_kas = '$sqe[no_kas]'",$conn));
			
			} else {
			$no_kas=$ssl[no_kas] - 1;
			$sqq = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi where no_kas = '$no_kas'",$conn));
			}
		}
		$isi.="
		<tr>
		<td align=center>#</td>
		<td align=center>$tgl</td>
		<td><i> Rek. Koperasi</td>
		<td><i>Koperasi</i></td>
		<td>SALDO KOPERASI TGL ".TglIndo($sqq[tanggal])."</td>
		<td>".duit($sqq[sisa_kas_total]+$sqq[penambahan_kas])."</td>
		<td align=center>-</td>
		</tr>
		";

/* +++++++++++++hitung angsuran ++++++++++++++++++++++*/
$setor = mysql_query("select n.rek_pinjaman, n.no_rekening, n.nama, p.*, a.* from tb_nasabah n, tb_pinjaman p, tb_angsuran a where p.no_anggota = n.no_anggota and p.no_pinjaman = a.no_pinjaman and a.status_lunas = '1' and a.tgl_bayar = '$tgg'",$conn);
while ($bar = mysql_fetch_array($setor)) {
$tgl = TglIndo($bar[tgl_bayar]);
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>$bar[rek_pinjaman]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>ANGSURAN KE $bar[angsuran_ke] &nbsp;&nbsp;&nbsp; [Pokok : ".nominal($bar[pokok]).", Bahas : ".nominal($bar[jasa])." "; if ($bar[denda] != 0) { $isi.=", Denda :"; $isi.=nominal($bar[denda]); } $isi.="]</td>
		<td>".duit($bar[pokok]+$bar[jasa]+$bar[denda])."</td>
		<td align=center>-</td>
		</tr>
		";
	$no +=1;
}
	

/* +++++++++++++hitung setoran tabungan ++++++++++++++++++++++*/
$sql = mysql_query("select nt.nama, nt.no_rekening, th.* from tb_nasabah_tabungan nt, tb_tabungan t, tb_tab_history th where th.no_tabungan = t.no_tabungan and t.no_agt_tab = nt.no_agt_tab and th.sandi = '1' and th.tanggal = '$tgg'",$conn);
		
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		$isi.="
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
		$isi.="
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
		$isi.="
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
		$isi.="
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
		$isi.="
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
$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.tgl_pinjam = '$tgg'",$conn);
while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_pinjam]);
		$isi.="
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
if ($row[adm] != null) {
		$isi.="
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center></td>
		<td align=center></td>
		<td></td>
		<td>&nbsp;&nbsp; - Administrasi</td>
		<td>".duit($row[adm])."</td>
		<td align=center>-</td>
		</tr>";
		$isi.="
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center></td>
		<td align=center></td>
		<td></td>
		<td>&nbsp;&nbsp; - Administrasi Calon Anggota</td>
		<td>".duit($row[ca])."</td>
		<td align=center>-</td>
		</tr>";
		$isi.="
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center></td>
		<td align=center></td>
		<td></td>
		<td>&nbsp;&nbsp; - Stofmap</td>
		<td>".duit($row[stofmap])."</td>
		<td align=center>-</td>
		</tr>";
		$isi.="
		<tr>
		<td align=center><div style='visibility:hidden;'>$no</div></td>
		<td align=center></td>
		<td align=center></td>
		<td></td>
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

$total_input = $a[pokok] + $a[jasa] + $a[denda] + $adpro + $c[debet] + $e[simp] + $sqq[sisa_kas_total] + $sqq[penambahan_kas];
$total_output= $k[opp] + $l[pinjaman] + $m[kredit];
	$isi.="</tbody>
	<tr><td colspan=5>JUMLAH</td><td>".duit($total_input)."</td><td>".duit($total_output)."</td></tr>
	</table><div id='controls'>
		<div id='perpage'>
			<select onchange='sorter.size(this.value)'>
			<option value='3'>3</option>
			<option value='10' selected='selected'>10</option>
			<option value='20'>20</option>
			<option value='50'>50</option>
			<option value='100'>100</option>
			</select>
			<span>Baris per halaman</span>
		</div>
		<div id='navigation'>
			<img src='../images/first.gif' width='16' height='16' alt='First Page' onclick='sorter.move(-1,true)' />
			<img src='../images/previous.gif' width='16' height='16' alt='First Page' onclick='sorter.move(-1)' />
			<img src='../images/next.gif' width='16' height='16' alt='First Page' onclick='sorter.move(1)' />
			<img src='../images/last.gif' width='16' height='16' alt='Last Page' onclick='sorter.move(1,true)' />
		</div>
		<div id='text'>Halaman <span id='currentpage'></span> dari <span id='pagelimit'></span></div>
	</div>
	";
	if (isset($_POST[save])) {
		$ddt = mysql_num_rows(mysql_query("select sisa_kas_total from tb_kas_koperasi where tanggal = '$_POST[tgg]'",$conn));
		if ($ddt == 0) {
		$sql = mysql_query("insert into tb_kas_koperasi values ('',now(),'$_POST[saldo]','')",$conn);
		} else {
		$sql = mysql_query("update tb_kas_koperasi set sisa_kas_total = '$_POST[saldo]' where tanggal = '$_POST[tgg]'",$conn);
		}
		header("location:transaksi_kas_harian.php?tgl=$_POST[tgl]");
	}
	/*else {
		$sts = mysql_num_rows(mysql_query("select * from tb_kas_koperasi where tanggal = '$tgg'",$conn));
		if ($sts != 0) {
			$sts = "disabled";
		}
	}*/
$sisa = $total_input - $total_output;
$isi.="&nbsp;<form action='' method=post><input type=hidden name='saldo' value='$sisa'><input type=hidden name='tgg' value='$tgg'><input type=hidden name='tgl' value='$tgl'>
<table align=right width=260px id=cari><tr><td>Total Pendapatan</td><td>:</td><td align=right>".duit($total_input)."</td></tr>
<tr><td>Total Pengeluaran</td><td>:</td><td align=right>".duit($total_output)."</td></tr>
<tr><td></td><td></td><td>________________________</td></tr>
<tr><td>Sisa Kas Koperasi</td><td>:</td><td align=right><h3>".duit($total_input-$total_output)."</td></tr>
<tr><td><input $sts type=submit name='save' value='Simpan Kas' onclick=	\"ok=confirm('Perhatian!! setelah klik tombol OK maka semua transaksi harus sudah selesai, Apakah anda yakin akan melakukan penyimpanan saldo akhir harian ?');
		if (ok) {
		return } else {return false} \"></td><td></td><td align=right></td></tr>
</table></form>
";
	include_once "instansiasi.php";
	?>
    <script type="text/javascript" src="../__js/script.js"></script>
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",0);
  </script>
    <?

include_once "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>