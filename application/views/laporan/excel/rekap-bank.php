<?php 
    header_excel('rekap-bank.xls');
    foreach($data as $value); 
?>
<table width="100%"><tr><td align="center" colspan="7">BUKU PEMBANTU BANK<br/>Bulan: <?= tampil_bulan(get_safe('tanggal').'-01') ?></td></tr></table>
<table width="100%">
    <tr><td colspan="2">Nama Madrasah</td><td colspan="5">: <?= $attr->nama ?></td></tr>
    <tr><td colspan="2">Desa/Kecamatan</td><td colspan="5">: <?= $attr->kelurahan.'/'.$attr->kecamatan ?></td></tr>
    <tr><td colspan="2">Kabupaten</td><td colspan="5">: <?= $attr->kabupaten ?></td></tr>
    <tr><td colspan="2">Provinsi</td><td colspan="5">: <?= $attr->provinsi ?></td></tr>
</table>
<table width="100%" border="1">
    <thead>
        <tr>
            <th width="7%">Tanggal</th>
            <th width="7%">No. Kode</th>
            <th width="7%">No. Bukti</th>
            <th width="30%">Uraian</th>
            <th width="10%">Penerimaan (D)</th>
            <th width="10%">Pengeluaran (K)</th>
            <th width="10%">Saldo</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td align="center"></td>
            <td></td>
            <td></td>
            <td>Saldo Bulan Sebelumnya</td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="right"><?= formatcurrency($last_saldo->sisa) ?></td>
        </tr>
        <?php 
        $total = $last_saldo->sisa;
        foreach ($data as $key => $value) { 
            if ($value->jenis === 'Penerimaan') {
                $total += $value->nominal;
            }
            if ($value->jenis === 'Penarikan') {
                $total -= $value->nominal;
            }
            ?>
        <tr>
            <td align="center"><?= datefmysql($value->tanggal) ?></td>
            <td><?= $value->kode ?></td>
            <td><?= $value->nobukti ?></td>
            <td><?= $value->keterangan ?></td>
            <td align="right"><?= ($value->jenis === 'Penerimaan')?formatcurrency($value->nominal):'' ?></td>
            <td align="right"><?= ($value->jenis === 'Penarikan')?formatcurrency($value->nominal):'' ?></td>
            <td align="right"><?= formatcurrency($total) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<br/>
<table align="right" width="100%">
    <tr><td width="100%"  colspan="7" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl(date("Y-m-d")) ?></td> </tr>
    <tr><td colspan="3" align="center">Mengetahui<br/>Kepala Madrasah</td><td width="33%">&nbsp;</td><td colspan="3" align="center">Bendahara</td> </tr>
    <tr><td colspan="7">&nbsp;</td></tr>
    <tr><td colspan="7">&nbsp;</td></tr>
    <tr><td colspan="7">&nbsp;</td></tr>
    <tr><td colspan="3" align="center">( <?= $attr->kepala ?> )</td><td></td><td align="center" colspan="3">( <?= $attr->bendahara ?> )</td></tr>
</table>