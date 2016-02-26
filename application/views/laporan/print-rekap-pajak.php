<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        <?php foreach($data as $value); ?>
        <center>BUKU PEMBANTU PAJAK<br/>Bulan: <?= tampil_bulan(get_safe('tanggal').'-01') ?></center>
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
                    <th width="7%" rowspan="2">Tanggal</th>
                    <th width="7%" class="left" rowspan="2">No. Kode</th>
                    <th width="7%" class="left" rowspan="2">No. Bukti</th>
                    <th width="30%" class="left" rowspan="2">Uraian</th>
                    <th width="28%" colspan="4">Penerimaan (D)</th>
                    <th width="7%" class="right" rowspan="2">Pengeluaran (K)</th>
                    <th width="7%" class="right" rowspan="2">Saldo</th>
                </tr>
                <tr>
                    <th width="7%" class="right">PPN</th>
                    <th width="7%" class="right">PPh21</th>
                    <th width="7%" class="right">PPh22</th>
                    <th width="7%" class="right">PPh23</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                </tr>
            </thead>
            <tbody>
<!--                <tr>
                    <td align="center"></td>
                    <td></td>
                    <td></td>
                    <td>Saldo Bulan Sebelumnya</td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td align="right"><?= currency($last_saldo->sisa) ?></td>
                </tr>-->
                <?php 
                $saldo = 0;
                foreach ($data as $key => $value) { 
                    $ppn = ''; $pph21 = ''; $pph22 = ''; $pph23 = ''; $pengeluaran= '';
                    if ($value->jenis_transaksi === 'Penerimaan') {
                        if ($value->jenis_pajak === 'PPN') {
                            $ppn = $value->hasil_pajak;
                        }
                        if ($value->jenis_pajak === 'PPh21') {
                            $pph21 = $value->hasil_pajak;
                        }
                        if ($value->jenis_pajak === 'PPh22') {
                            $pph22 = $value->hasil_pajak;
                        }
                        if ($value->jenis_pajak === 'PPh23') {
                            $pph23 = $value->hasil_pajak;
                        }
                        $saldo += $value->hasil_pajak;
                    } else {
                        $pengeluaran = $value->hasil_pajak;
                        $saldo -= $value->hasil_pajak;
                    }
                    ?>
                <tr>
                    <td align="center"><?= datefmysql($value->tanggal) ?></td>
                    <td><?= $value->kode_akun_pajak ?></td>
                    <td><?= $value->no_bukti ?></td>
                    <td><?= $value->uraian ?></td>
                    <td align="right"><?= ($ppn !== '')?currency($ppn):'' ?></td>
                    <td align="right"><?= ($pph21 !== '')?currency($pph21):'' ?></td>
                    <td align="right"><?= ($pph22 !== '')?currency($pph22):'' ?></td>
                    <td align="right"><?= ($pph23 !== '')?currency($pph23):'' ?></td>
                    <td align="right"><?= ($pengeluaran !== '')?currency($pengeluaran):'' ?></td>
                    <td align="right"><?= currency($saldo) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <br/>
        <table align="right" width="100%">
            <!--<tr><td width="100%"  colspan="3" align="right"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>-->
            <tr><td width="33%" align="center">Mengetahui<br/>Kepala Madrasah</td><td width="33%">&nbsp;</td><td width="33%" align="center">Dibuat Oleh,<br/>Bendahara</td> </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td align="center">( <u><?= $attr->kepala ?></u> )<br/><?= $attr->nip_kepala ?></td><td></td><td align="center">( <u><?= $attr->bendahara ?></u> )<br/><?= $attr->nip_bendahara ?></td></tr>
        </table>
    </div>
</body>