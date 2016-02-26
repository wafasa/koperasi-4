<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        
        <center><?= strtoupper($title) ?><br/>Periode: <?= indo_tgl(date2mysql(get_safe('awal'))) ?> s.d <?= indo_tgl(date2mysql(get_safe('akhir'))) ?></center>
        <br/><br/>
        <table width="100%">
            <tr><td width="25%">Nama Madrasah</td><td width="1%">: </td><td width="74"><?= $attr->nama ?></td></tr>
            <tr><td>NSM</td><td>:</td><td><?= $attr->nsm ?></td></tr>
            <tr><td>Kabupaten</td><td>:</td><td> <?= $attr->kabupaten ?></td></tr>
            <tr><td>Provinsi</td><td>:</td><td> <?= $attr->provinsi ?></td></tr>
            <tr><td>Total Jumlah Siswa</td><td>:</td><td><?= $siswa->jumlah_siswa ?></td></tr>
            <tr><td>Jumlah Dana BOS Tahap I/II</td><td>:</td><td> </td></tr>
            <tr><td>Alamat</td><td>:</td><td><?= $attr->alamat ?></td></tr>
        </table>
        <br/>
        <table class="tabel-laporan" width="100%">
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
                    <td align="right"><?= currency($value->nominal) ?></td>
                    <td align="center"><?= datefmysql($value->tanggal_kegiatan) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <br/>
        <table align="right" width="100%">
            <!--<tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>-->
            <tr><td width="33%" align="center">Ketua Komite Madrasah</td><td width="33%" align="center">Kepala Madrasah</td><td width="33%" align="center">Bendahara</td> </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="center">( <?= $attr->ketua_komite ?> )</td><td align="center">( <u><?= $attr->kepala ?></u> )<br/><?= $attr->nip_kepala ?></td><td align="center">( <u><?= $attr->bendahara ?></u> )<br/><?= $attr->nip_bendahara ?></td></tr>
        </table>
    </div>
</body>