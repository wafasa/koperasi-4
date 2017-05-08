<?php
    header_excel('rekap-simpanan-pokok_'.date2mysql(get_safe('awal')).'_sd_'.date2mysql(get_safe('akhir')).'.xls');
?>
<table width="100%">
    <tr>
        <td colspan="9" align="center">REKAP SIMPANAN BEBAS</td>
    </tr>
    <tr>
        <td colspan="9" align="center">TANGGAL <?= get_safe('awal') ?> S.D <?= get_safe('akhir') ?></td>
    </tr>
</table>
<table border="1" width="100%">
    <thead>
    <tr>
        <th width="3%">No</th>
        <th width="7%">Tanggal</th>
        <th width="10%" class="left">No. Anggota</th>
        <th width="25%" class="left">Nama</th>
        <th width="10%" class="right">Awal</th>
        <th width="10%" class="right">Masuk</th>
        <th width="10%" class="right">Keluar</th>
        <th width="10%" class="right">Sisa</th>
        <th width="15%" class="left">Petugas</th>


    </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $value) { ?>
        <tr>
            <td><?= ++$key ?></td>
            <td><?= datefmysql($value->tanggal) ?>&nbsp;</td>
            <td><?= $value->no_rekening ?></td>
            <td><?= $value->nama ?></td>
            <td><?= formatcurrency($value->awal,"USD") ?></td>
            <td><?= formatcurrency($value->masuk,"USD") ?></td>
            <td><?= formatcurrency($value->keluar,"USD") ?></td>
            <td><?= formatcurrency($value->saldo,"USD") ?></td>
            <td><?= $value->username ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>