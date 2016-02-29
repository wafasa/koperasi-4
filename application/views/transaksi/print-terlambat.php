<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    DATA TERLAMBAT ANGSURAN
        <table class="tabel-laporan">
            <thead>
            <tr>
              <th width="3%">No</th>
              <th width="7%">Tempo</th>
              <th width="7%">No. Rek.</th>
              <th width="25%" class="left">Nama</th>
              <th width="45%" class="left">Alamat</th>
              <th width="10%" class="right">Angsuran</th>
              <th width="5%" class="right">Angs. Ke</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $key => $value) { ?>
                <tr>
                    <td><?= ++$key ?></td>
                    <td><?= datefmysql($value->jatuh_tempo) ?></td>
                    <td><?= $value->nomor_rekening ?></td>
                    <td><?= $value->nama ?></td>
                    <td><?= $value->alamat ?></td>
                    <td><?= $value->jml_angsuran ?></td>
                    <td><?= $value->angsuran_ke ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    
</body>