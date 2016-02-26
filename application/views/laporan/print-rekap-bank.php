<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        <?php foreach($data as $values); ?>
        <center>BUKU PEMBANTU BANK<br/>Bulan: <?= tampil_bulan(get_safe('tanggal').'-01') ?></center>
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
                <tr>
                    <td align="center"></td>
                    <td></td>
                    <td></td>
                    <td>Saldo Bulan Sebelumnya</td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td align="right"><?= currency($last_saldo->sisa) ?></td>
                </tr>
                <?php 
                $total = $last_saldo->sisa;
                if (is_array($data)) {
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
                        <td align="right"><?= ($value->jenis === 'Penerimaan')?currency($value->nominal):'' ?></td>
                        <td align="right"><?= ($value->jenis === 'Penarikan')?currency($value->nominal):'' ?></td>
                        <td align="right"><?= currency($total) ?></td>
                    </tr>
                    <?php } 
                } ?>
            </tbody>
        </table>

        <br/>
        <table align="right" width="100%">
            <tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= isset($values->tanggal)?indo_tgl($values->tanggal):indo_tgl(date("Y-m-d")) ?></td> </tr>
            <tr><td width="33%" align="center">Mengetahui<br/>Kepala Madrasah</td><td width="33%">&nbsp;</td><td width="33%" align="center">Bendahara</td> </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="center">( <u><?= $attr->kepala ?></u> )<br/><?= $attr->nip_kepala ?></td><td></td><td align="center">( <u><?= $attr->bendahara ?></u> )<br/><?= $attr->nip_bendahara ?></td></tr>
        </table>
    </div>
</body>