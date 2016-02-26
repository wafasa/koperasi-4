<?php 
    header_excel('penggunaan-dana-bos.xls');
?>
<table width="100%"><tr><td align="center" colspan="6"><?= strtoupper($title) ?><br/>Periode: <?= indo_tgl(date2mysql(get_safe('awal'))) ?> s.d <?= indo_tgl(date2mysql(get_safe('akhir'))) ?></td></tr></table>
<table width="100%">
    <tr><td colspan="2">Nama Madrasah</td><td colspan="4">: <?= $attr->nama ?></td></tr>
    <tr><td colspan="2">NSM</td><td colspan="4">: <?= $attr->nsm ?> </td></tr>
    <tr><td colspan="2">Kabupaten</td><td colspan="4">:  <?= $attr->kabupaten ?></td></tr>
    <tr><td colspan="2">Provinsi</td><td colspan="4">:  <?= $attr->provinsi ?></td></tr>
    <tr><td colspan="2">Total Jumlah Siswa</td><td colspan="4">: <?= $siswa->jumlah_siswa ?> </td></tr>
    <tr><td colspan="2">Jumlah Dana BOS Tahap I/II</td><td colspan="4">:  </td></tr>
    <tr><td colspan="2">Alamat</td><td colspan="4">: <?= $attr->alamat ?></td></tr>
</table>
        <table border="1" width="100%">
            <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <!--<th width="3%" class="left">Urut</th>-->
                <th width="50%" class="left" rowspan="2">Jenis Penggunaan / Pembelanjaan <br/>(<i>EXPENDITURE</i>)</th>
                <th width="20%" colspan="2">KUANTITAS</th>
                <th width="10%" class="right" rowspan="2">Jumlah Dana (Rp)</th>
                <th width="15%" rowspan="2">Tanggal Pelaksanaan Kegiatan</th>
            </tr>
            <tr>
                <th>Satuan</th>
                <th>Volume</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $key => $value) { ?>
                <tr>
                    <td align="center"><?= ++$key ?></td>
                    <td><?= $value->uraian ?></td>
                    <td><?= $value->satuan ?></td>
                    <td align="center"><?= $value->volume ?></td>
                    <td align="right"><?= formatcurrency($value->nominal) ?></td>
                    <td align="center"><?= datefmysql($value->tanggal_kegiatan) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <br/>
        <table align="right" width="100%">
            <!--<tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>-->
            <tr><td colspan="2" align="center">Ketua Komite Madrasah</td><td colspan="2" align="center">Kepala Madrasah</td><td colspan="2" align="center">Bendahara</td> </tr>
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr><td colspan="6">&nbsp;</td></tr>
            <tr><td colspan="2" align="center">( <?= $attr->ketua_komite ?> )</td><td colspan="2" align="center">( <?= $attr->kepala ?> )</td><td align="center" colspan="2">( <?= $attr->bendahara ?> )</td></tr>
        </table>