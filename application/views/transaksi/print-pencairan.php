<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        <?php foreach($data as $value); ?>
<!--        <center>PENCAIRAN DANA BOS <br/>TAHUN PELAJARAN <?= $thn_agg->tahun_anggaran ?></center>
        <br/><br/>-->
        <table width="100%">
            <tr>
                <td width="55%">&nbsp;</td>
                <td width="45%">
                    <table width="100%">
                        <tr><td>T.A</td><td width="1%">: </td><td width="79"><?= $thn_agg->tahun_anggaran ?></td></tr>
                        <tr><td>No. Bukti</td><td>:</td><td><?= $value->no_bukti ?></td></tr>
                        <tr><td>No. Kode Akun</td><td>:</td><td> <?= $value->kode ?></td></tr>
<!--                        <tr><td>Uraian</td><td>:</td><td><?= $value->uraian ?></td></tr>
                        <tr><td>Jumlah</td><td>:</td><td><?= currency($value->nominal) ?></td></tr>
                        <tr><td>Penerima</td><td>:</td><td><?= $value->penerima ?></td></tr>-->
                    </table>
                </td>
            </tr>
        </table>
        <center><h2><u>BUKTI PEMBAYARAN</u></h2></center>
        <br/>
        <table width="100%">
            <tr><td>Sudah terima dari</td><td>:</td><td>Bendahara <?= $attr->nama ?></td></tr>
            <tr><td>Jumlah Uang</td><td>:</td><td><?= currency($value->nominal) ?></td></tr>
            <tr><td>Terbilang</td><td>:</td><td><?= terbilang($value->nominal) ?> RUPIAH</td></tr>
            <tr><td>Untuk Pembayaran</td><td>:</td><td><?= $value->uraian ?></td></tr>
        </table><br/><br/>
        <table align="right" width="100%">
            <tr><td width="55%" align="right"></td><td width="45%"><?= $attr->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td> </tr>
            <tr><td width="55%" align="center"></td><td width="45%">Penerima</td> </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td></td><td>( <?= $value->penerima ?> )</td></tr>
        </table>
        <br/><br/>
        <br/><br/>
        <br/><br/>
        <br/><br/>
        <br/><br/>
        <hr/>
        <table align="right" width="100%">
            <tr><td width="55%"><b>Bendahara</b></td><td width="45%"></td> </tr>
            <tr><td width="55%"><b><?= $attr->nama ?></b></td><td width="45%"></td> </tr>
            <tr><td></td><td>&nbsp;</td></tr>
            <tr><td></td><td>&nbsp;</td></tr>
            <tr><td></td><td>&nbsp;</td></tr>
            <tr><td>( <u><?= $attr->bendahara ?></u> )</td><td></td></tr>
            <tr><td>NIP. <?= $attr->nip_bendahara ?></td><td></td></tr>
        </table>
    </div>
</body>