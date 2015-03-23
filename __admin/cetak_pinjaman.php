<?php
mysql_connect("localhost","root","");
mysql_select_db("sumberbaru");
$s = mysql_fetch_array(mysql_query("select n.nama, n.alamat, n.rek_pinjaman, n.no_ktp, p.*, at.* from tb_pinjaman p, tb_nasabah n, tb_atribut_peminjaman at where p.no_anggota = n.no_anggota and at.kd_atribut = n.kd_atribut and p.no_pinjaman = '$_GET[id_pinj]'"));
include "../function/function.php";
?>
<style type="text/css">
table { font-family:tahoma, "Trebuchet MS";
	font-size:10px;
	width:420px;
	color:#000000;
}
th {background-color:#E1E1FF;
	font-family:tahoma, "Trebuchet MS";
	color:#000000;
	font-weight:normal;
}
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

<table width="100%" class="border" align="center"><tr><td>
<table border="0" width="100%"><tr><td><img src="../images/sb.png" width="70px" /></td><td align="center"><h2><b>BMT SUMBER BARU EROMOKO</b></h2><p>Badan Hukum : 703/BH/XIV.30/IX/2007</p><p>Alamat: Jl. Raya Eromoko, Wonogiri Telp (0273) 461424</p></td><td></td></tr></table>
<hr />
<table width="100%" height="70px"><caption><h2>KARTU ANGSURAN</h2></caption>
<tr><td width="20%">No REKENING</td><td>:</td><td><?=$s[rek_pinjaman];?></td></tr>
<tr><td>NAMA</td><td>:</td><td><?=ucwords($s[nama]);?></td></tr>
<tr><td>ALAMAT</td><td>:</td><td><?=ucwords($s[alamat]);?></td></tr>
<tr><td>NO.IDENTITAS</td><td>:</td><td><?=$s[no_ktp];?></td></tr></table>
<hr />
<table align="left" width="100%"  border="0" cellspacing="0" cellpadding="1" class=kotaktabel id=tabel>
      <tr>		<th>KE</th>
				<th>JTH TEMPO</th>
				<th>TGL BAYAR</th>
				<th>POKOK</th>
				<th>BAHAS</th>
				<th>SISA POKOK</th>
<?
$no = 1;
$sql = mysql_query("select n.rek_pinjaman, n.nama, a.* from tb_nasabah n, tb_angsuran a, tb_pinjaman p where p.no_anggota = n.no_anggota and a.no_pinjaman = p.no_pinjaman and a.no_pinjaman = '$_GET[id_pinj]'");
while ($data = mysql_fetch_array($sql)) {
	if($no % 2 == 0) { 
		$kol = "alt";
		$over = "this.className='over';"; 
		$out ="this.className='alt';";	
	}else { 
		$kol = "ori";
		$over = "this.className='over';"; 
		$out ="this.className='ori';";
	}
	$baris = "row".$no;
	$cek = "cbRow".$no;
	if( $data[lunas] == 1 ){ $st = "lunas"; } else if ($data[lunas] == '2') { $st = "kurang"; } else { $st = "blm lunas"; }
?>
      <tr height="20px" id="<?=$baris;?>" onMouseOver=<? echo $over;?> onmouseout=<? echo $out;?> class="<?=$kol;?>">
        <td class="bor" align="center" ><?=$no;?></td>
        <td class="bor" align="center" ><?=TglIndo($data[jatuh_tempo]);?></td>
        <td class="bor" align="center" ><? if ($data[tgl_bayar] == '0000-00-00') { echo "-"; } else { echo TglIndo($data[tgl_bayar]);} ?></td>
		<td class="bor" align='left'><? if ($data[tgl_bayar] == '0000-00-00') { echo "<center>-</center>"; } else { echo duit($data[pokok]);}?></td>
        <td class="bor" align="left" ><? if ($data[tgl_bayar] == '0000-00-00') { echo "<center>-</center>"; } else { echo duit($data[jasa]);}?></td>
        <td class="bor" align="center"><? if ($data[tgl_bayar] == '0000-00-00') { echo "<center>-</center>"; } else if ($data[sisa_pokok] < 0 ) { echo "Rp. 0"; } else { echo duit($data[sisa_pokok]);}?></td>
		<td class="bor" align="center"></td>
        
        </tr>
      <? 
	  $no += 1;;
	  }?>
    <tr><td colspan="6" width="100%" align="right"><br /><b>CUSTOMER SERVICE</b></td></tr>
    </table></td></tr></table>
   </body>