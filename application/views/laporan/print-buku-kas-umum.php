udahudahadasd<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        <?php foreach($data as $value); ?>
        <center><?= $title ?></center>
        <br/><br/>
        <table width="100%">
            <tr><td width="20%">Nama Madrasah</td><td width="1%">: </td><td width="79"><?= $attr->nama ?></td></tr>
            <tr><td>Desa/Kecamatan</td><td>:</td><td> <?= $attr->kelurahan.'/'.$attr->kecamatan ?></td></tr>
            <tr><td>Kabupaten</td><td>:</td><td> <?= $attr->kabupaten ?></td></tr>
            <tr><td>Provinsi</td><td>:</td><td> <?= $attr->provinsi ?></td></tr>
        </table>
        <br/>
        <table width="100%" class="tabel-laporan">
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
                <?php 
                $total = 0;
                foreach ($data as $key => $value) { 
                    if ($value->keluar === 'Tidak') {
                        $total += $value->nominal;
                    }
                    if ($value->keluar === 'Ya') {
                        $total -= $value->nominal;
                    }
                    ?>
                <tr>
                    <td align="center"><?= datefmysql($value->tanggal) ?></td>
                    <td><?= $value->kode ?></td>
                    <td><?= $value->no_bukti ?></td>
                    <td><?= $value->uraian ?></td>
                    <td align="right"><?= ($value->keluar === 'Tidak')?currency($value->nominal):'' ?></td>
                    <td align="right"><?= ($value->keluar === 'Ya')?currency($value->nominal):'' ?></td>
                    <td align="right"><?= currency($total) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <br/>
        <table align="right" width="100%">
            <!--<tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>-->
            <tr><td width="33%" align="center">Mengetahui<br/>Kepala Madrasah</td><td width="33%">&nbsp;</td><td width="33%" align="center">Bendahara</td> </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="center">( <u><?= $attr->kepala ?></u> )<br/><?= $attr->nip_kepala ?></td><td></td><td align="center">( <u><?= $attr->bendahara ?></u> )<br/><?= $attr->nip_bendahara ?></td></tr>
        </table>
    </div>
</body>