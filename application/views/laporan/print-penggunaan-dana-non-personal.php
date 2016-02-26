<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        
        <center><?= strtoupper($title) ?> <?= indo_tgl(date2mysql(get_safe('awal'))) ?> s.d <?= indo_tgl(date2mysql(get_safe('akhir'))) ?><br/>TINGKAT MADRASAH</center>
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
                    <th><?= currency($value->nominal) ?></th>
                <?php } ?>
                    <th><?= currency($total) ?></th>
                </tr>
            </thead>
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