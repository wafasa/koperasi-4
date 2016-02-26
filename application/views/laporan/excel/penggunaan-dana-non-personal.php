<?php
    header_excel('penggunaan-dana-non-personal.xls');
?>
<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" /> 
<table width="100%"><tr><td align="center" colspan="<?= count($data) ?>"><?= strtoupper($title) ?> <?= indo_tgl(date2mysql(get_safe('awal'))) ?> s.d <?= indo_tgl(date2mysql(get_safe('akhir'))) ?><br/>TINGKAT MADRASAH</td></tr></table>
<table width="100%">
    <tr><td colspan="2">Nama Madrasah</td><td colspan="4">: <?= $attr->nama ?></td></tr>
    <tr><td colspan="2">NSM</td><td colspan="4">: <?= $attr->nsm ?> </td></tr>
    <tr><td colspan="2">Kabupaten</td><td colspan="4">:  <?= $attr->kabupaten ?></td></tr>
    <tr><td colspan="2">Provinsi</td><td colspan="4">:  <?= $attr->provinsi ?></td></tr>
    <tr><td colspan="2">Total Jumlah Siswa</td><td colspan="4">: <?= $siswa->jumlah_siswa ?> </td></tr>
    <tr><td colspan="2">Jumlah Dana BOS Tahap I/II</td><td colspan="4">:  </td></tr>
    <tr><td colspan="2">Alamat</td><td colspan="4">: <?= $attr->alamat ?></td></tr>
</table>
<br/>
<table border="1" width="100%">
    <thead>
        <?php 
        $total = 0;
        foreach ($data as $key => $value) { ?>
            <!--<td style="height: 200px;"><div class="rot-neg-90"><?= $value->uraian ?></div></td>-->
            <th class="rotate"><div><span><?= $value->uraian ?></span></div></th>
        <?php 
        $total += $value->nominal;
        } ?>
        <th class="rotate"><div><span>TOTAL DANA</span></div></th>
        <tr>
        <?php foreach ($data as $key => $value) { ?>
            <th><?= formatcurrency($value->nominal) ?></th>
        <?php } ?>
            <th><?= formatcurrency($total) ?></th>
        </tr>
    </thead>
</table>

<br/>
<table align="right" width="100%">
    <!--<tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>-->
    <tr><td align="center">Ketua Komite Madrasah</td><td align="center">Kepala Madrasah</td><td align="center">Bendahara</td> </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td align="center">( <?= $attr->ketua_komite ?> )</td><td align="center">( <?= $attr->kepala ?> )</td><td align="center">( <?= $attr->bendahara ?> )</td></tr>
</table>