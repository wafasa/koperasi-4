<?php 
    header_excel('Rekap Angsuran.xls');
?>
<table>
    <tr>
        <td colspan="8">REKAP PEMBAYARAN ANGSURAN TANGGAL <?= get_safe('awal') ?> S.D <?= get_safe('akhir') ?></td>
    </tr>
</table>
    <table border="1">
        <thead>
        <tr>
          <th width="3%">No</th>
          <th width="7%">Tanggal</th>
          <th width="7%">No. Rek.</th>
          <th width="25%" class="left">Nama</th>
          <th width="10%" class="right">Angsuran</th>
          <th width="10%" class="right">Angs. Pokok</th>
          <th width="10%" class="right">Bunga</th>
          <th width="10%" class="right">Sisa Pinjaman</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $value) { ?>
            <tr>
                <td align="center"><?= ++$key ?></td>
                <td align="center"><?= datefmysql($value->tgl_pinjam) ?></td>
                <td><?= $value->nomor_rekening ?></td>
                <td><?= $value->nama ?></td>
                <td align="right"><?= formatcurrency($value->bsr_angsuran) ?></td>
                <td align="right"><?= formatcurrency($value->angsuran_pokok) ?></td>
                <td align="right"><?= formatcurrency($value->jasa_angsuran) ?></td>
                <td align="right"><?= formatcurrency($value->sisa_angsuran) ?></td>
            </tr>    
        <?php } ?>
        </tbody>
    </table>
