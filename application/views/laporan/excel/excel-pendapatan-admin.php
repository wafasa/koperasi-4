<?php 
    header_excel('Rekap Pendapatan Administrasi.xls');
?>
<table>
    <tr>
        <td colspan="9">REKAP PEMBIAYAAN TANGGAL <?= get_safe('awal') ?> S.D <?= get_safe('akhir') ?></td>
    </tr>
</table>
    <table border="1">
        <thead>
        <tr>
            <th width="3%">No</th>
            <th width="7%">Tanggal</th>
            <th width="7%">No. Rek.</th>
            <th width="25%" class="left">Nama</th>
            <th width="10%" class="right">Administrasi</th>
            <th width="10%" class="right">Keanggotaan</th>
            <th width="10%" class="right">Survey</th>
            <th width="10%" class="right">Stofmap</th>
            <th width="10%" class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $total_adm = 0; $total_ca = 0; $survey = 0; $stofmap = 0;
        foreach ($data as $key => $value) { ?>
            <tr>
                <td align="center"><?= ++$key ?></td>
                <td align="center"><?= datefmysql($value->tgl_input) ?></td>
                <td><?= $value->nomor_rekening ?></td>
                <td><?= $value->nama ?></td>
                <td align="right"><?= formatcurrency($value->biaya_adm) ?></td>
                <td align="right"><?= formatcurrency($value->biaya_ca) ?></td>
                <td align="right"><?= formatcurrency($value->survey) ?></td>
                <td align="right"><?= formatcurrency($value->stofmap) ?></td>
                <td align="right"><?= formatcurrency($value->biaya_adm+$value->biaya_ca+$value->survey+$value->stofmap) ?></td>
            </tr>    
        <?php 
        $total_adm += $value->biaya_adm;
        $total_ca += $value->biaya_ca;
        $survey += $value->survey;
        $stofmap += $value->stofmap;
        } ?>
            <tr>
                <td align="center" colspan="4">TOTAL</td>
                <td align="right"><?= formatcurrency($total_adm) ?></td>
                <td align="right"><?= formatcurrency($total_ca) ?></td>
                <td align="right"><?= formatcurrency($survey) ?></td>
                <td align="right"><?= formatcurrency($stofmap) ?></td>
                <td align="right"><?= formatcurrency($total_adm+$total_ca+$survey+$stofmap) ?></td>
            </tr>
        </tbody>
    </table>
