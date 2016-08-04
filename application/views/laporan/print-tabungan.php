<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-half.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
       setTimeout(function(){ window.close();},300);
       window.print();    
    }
</script>
<?php
    $val = $data[0];
?>
<body onload="cetak();">
    <div class="page">
    <table class="table" width="100%">
    <tr><td width="20%">Nama Lengkap:</td><td><?= $val->nama ?></td></tr>
    <tr><td>No. Rekening:</td><td><?= $val->no_rekening ?></td></tr>
    </table>

    <table class="tabel-laporan" width='100%'>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="15%">Tanggal</th>
                <th width="15%" class="right">Debet</th>
                <th width="15%" class="right">Kredit</th>
                <th width="15%" class="right">Saldo</th>
                <th width="15%" class="right">Op ID</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $value) { ?>
            <tr>
                <td align='center'><?= ++$key ?></td>
                <td align='center'><?= datefmysql($value->tanggal) ?></td>
                <td align='right'><?= formatcurrency($value->masuk) ?></td>
                <td align='right'><?= formatcurrency($value->keluar) ?></td>
                <td align='right'><?= formatcurrency($value->sisa_saldo) ?></td>
                <td align='right'><?= $value->kode ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</body>