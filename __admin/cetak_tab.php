<?php
mysql_connect("localhost","root","sumberbaru");
mysql_select_db("sumberbaru");
$s = mysql_fetch_array(mysql_query("select n.*, t.saldo, th.* from tb_nasabah_tabungan n, tb_tabungan t, tb_tab_history th where n.no_agt_tab = t.no_agt_tab and t.no_tabungan = th.no_tabungan and n.no_agt_tab = '$_GET[id]'"));
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
<body onLoad="window.print()">
<table width="100%" class="border" align="center"><tr><td>
<table border="0" width="100%"><tr><td align=left><img src="../images/sb.png" width="70px" /></td><td width=100% align="center"><h2><b>BMT SUMBER BARU EROMOKO</b></h2><p>Badan Hukum : 703/BH/XIV.30/IX/2007</p><p>Alamat: Jl. Raya Eromoko, Wonogiri Telp (0273) 461424</p></td><td></td><td></td><td></td></tr></table>
<hr />
<table width="100%" height="70px"><caption><h2>KARTU TABUNGAN</h2></caption>
<tr><td width="20%">No REKENING</td><td>:</td><td><?=$s[no_rekening];?></td></tr>
<tr><td>NAMA</td><td>:</td><td><?=ucwords($s[nama]);?></td></tr>
<tr><td>ALAMAT</td><td>:</td><td><?=ucwords($s[alamat]);?></td></tr>
<tr><td>NO.IDENTITAS</td><td>:</td><td><?=$s[no_ktp];?></td></tr></table>
<hr />
<table align="left" width="100%"  border="0" cellspacing="0" cellpadding="1" class=kotaktabel id=tabel>
      <tr>
        <th>NO</th>
				<th>TANGGAL</th>
				<th>SANDI</th>
				<th>DEBET</th>
				<th>KREDIT</th>
				<th>SALDO</th>
				<th>TELLER</th>
<?
$no = 1;
$kueri = mysql_query("select n.*, t.saldo, th.* from tb_nasabah_tabungan n, tb_tabungan t, tb_tab_history th where n.no_agt_tab = t.no_agt_tab and t.no_tabungan = th.no_tabungan and n.no_agt_tab = '$_GET[id]'");
$jumdata = mysql_num_rows($kueri);
while ($data = mysql_fetch_array($kueri)) {
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
        <td class="bor" align="center" ><?=TglIndo($data[tanggal]);?></td>
        <td class="bor" align="center" ><?=$data[sandi];?></td>
		<td class="bor" align='left'><?=duit($data[debet]);?></td>
        <td class="bor" align="left" ><?=duit($data[kredit]);?></td>
        <td class="bor" align="center"><?=duit($data[saldo]);?></td>
		<td class="bor" align="center">&nbsp;</td>
        
        </tr>
      <? 
	  $no += 1;;
	  }?>
    <tr><td colspan="6" width="100%" align="right"><br /><b>CUSTOMER SERVICE</b></td></tr>
    </table></td></tr></table>
   </body>