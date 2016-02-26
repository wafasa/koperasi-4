<?php 
    header_excel('rekap-pajak.xls');
    foreach($data as $value); 
?>
<table width="100%"><tr><td align="center" colspan="10">BUKU PEMBANTU PAJAK<br/>Bulan: <?= tampil_bulan(get_safe('tanggal').'-01') ?></td></tr></table>
<table width="100%">
    <tr><td colspan="2">Nama Madrasah</td><td colspan="8">: <?= $attr->nama ?></td></tr>
    <tr><td colspan="2">Desa/Kecamatan</td><td colspan="8">: <?= $attr->kelurahan.'/'.$attr->kecamatan ?></td></tr>
    <tr><td colspan="2">Kabupaten</td><td colspan="8">: <?= $attr->kabupaten ?></td></tr>
    <tr><td colspan="2">Provinsi</td><td colspan="8">: <?= $attr->provinsi ?></td></tr>
</table>
<br/>
<table width="100%" border="1">
    <thead>
        <tr>
            <th width="7%" rowspan="2">Tanggal</th>
            <th width="7%" class="left" rowspan="2">No. Kode</th>
            <th width="7%" class="left" rowspan="2">No. Bukti</th>
            <th width="30%" class="left" rowspan="2">Uraian</th>
            <th width="28%" colspan="4">Penerimaan (D)</th>
            <th width="7%" class="right" rowspan="2">Pengeluaran (K)</th>
            <th width="7%" class="right" rowspan="2">Saldo</th>
        </tr>
        <tr>
            <th width="7%" class="right">PPN</th>
            <th width="7%" class="right">PPh21</th>
            <th width="7%" class="right">PPh22</th>
            <th width="7%" class="right">PPh23</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
        </tr>
    </thead>
    <tbody>
<!--                <tr>
            <td align="center"></td>
            <td></td>
            <td></td>
            <td>Saldo Bulan Sebelumnya</td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right"><?= formatcurrency($last_saldo->sisa) ?></td>
        </tr>-->
        <?php 
        $saldo = 0;
        foreach ($data as $key => $value) { 
            $ppn = ''; $pph21 = ''; $pph22 = ''; $pph23 = ''; $pengeluaran= '';
            if ($value->jenis_transaksi === 'Penerimaan') {
                if ($value->jenis_pajak === 'PPN') {
                    $ppn = $value->hasil_pajak;
                }
                if ($value->jenis_pajak === 'PPh21') {
                    $pph21 = $value->hasil_pajak;
                }
                if ($value->jenis_pajak === 'PPh22') {
                    $pph22 = $value->hasil_pajak;
                }
                if ($value->jenis_pajak === 'PPh23') {
                    $pph23 = $value->hasil_pajak;
                }
                $saldo += $value->hasil_pajak;
            } else {
                $pengeluaran = $value->hasil_pajak;
                $saldo -= $value->hasil_pajak;
            }
            ?>
        <tr>
            <td align="center"><?= datefmysql($value->tanggal) ?></td>
            <td><?= $value->kode_akun_pajak ?></td>
            <td><?= $value->no_bukti ?></td>
            <td><?= $value->uraian ?></td>
            <td align="right"><?= ($ppn !== '')?formatcurrency($ppn):'' ?></td>
            <td align="right"><?= ($pph21 !== '')?formatcurrency($pph21):'' ?></td>
            <td align="right"><?= ($pph22 !== '')?formatcurrency($pph22):'' ?></td>
            <td align="right"><?= ($pph23 !== '')?formatcurrency($pph23):'' ?></td>
            <td align="right"><?= ($pengeluaran !== '')?formatcurrency($pengeluaran):'' ?></td>
            <td align="right"><?= formatcurrency($saldo) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<br/>
<table align="right" width="100%">
    <tr><td colspan="4" align="center">Mengetahui<br/>Kepala Madrasah</td><td colspan="2">&nbsp;</td><td colspan="4" width="33%" align="center">Dibuat Oleh,<br/>Bendahara</td> </tr>
    <tr><td colspan="10">&nbsp;</td></tr>
    <tr><td colspan="10">&nbsp;</td></tr>
    <tr><td colspan="10">&nbsp;</td></tr>
    <tr><td align="center" colspan="4">( <?= $attr->kepala ?> )</td><td colspan="2"></td><td align="center" colspan="4">( <?= $attr->bendahara ?> )</td></tr>
</table>