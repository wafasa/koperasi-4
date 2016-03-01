<?php 
    header_excel('Rekap Pembiayaan.xls');
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
          <th width="25%" class="left">Alamat</th>
          <th width="25%" class="left">Jaminan</th>
          <th width="10%" class="left">Jumlah</th>
          <th width="10%" class="right">Angsuran</th>
          <th width="10%" class="left">Durasi (Bln)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $value) { ?>
            <tr>
                <td align="center"><?= ++$key ?></td>
                <td align="center"><?= datefmysql($value->tgl_pinjam) ?></td>
                <td><?= $value->nomor_rekening ?></td>
                <td><?= $value->nama ?></td>
                <td><?= $value->alamat ?></td>
                <td><?= $value->jaminan ?></td>
                <td align="right"><?= formatcurrency($value->jml_pinjaman) ?></td>
                <td align="right"><?= formatcurrency($value->bsr_angsuran) ?></td>
                <td align="center"><?= $value->lama_pinjaman ?></td>
            </tr>    
        <?php } ?>
        </tbody>
    </table>
