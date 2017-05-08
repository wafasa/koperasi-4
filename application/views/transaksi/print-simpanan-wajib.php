<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-half.css') ?>"/>
<script type="text/javascript">
    function cetak() {
       setTimeout(function(){ window.close();},300);
       window.print();    
    }
</script>
<title><?= $title ?></title>
<body onload="cetak();">
    <?php foreach ($data as $value); ?>
    <div class="page">
        <table width="100%" style="color: #000;">
        <tr><td><b><?= $inst->nama ?></b></td> <td align="right"></td></tr>
        <tr><td><b><?= $inst->alamat ?> <?= $inst->kabupaten ?></b></td> <td align="right"></td></tr>
        <tr><td><b>Telp. <?= $inst->telp ?>,  Fax. <?= $inst->fax ?></b></td><td></td> </tr>
    </table>
    <br/>
    <center><b><u style="font-weight: bold;"><?= $subtitle ?></u></b></center>
    <br/>
    <div style="width:100%;display:inline-block;">
        <div style="width:50%;float:left;">
            <table width="100%" style="color: #000;border-spacing:0;">
                <tr>
                    <td width="34%"></td>
                    <td width="2%"></td>
                    <td width="64%"></td>
                </tr>
                <tr>
                    <td width="34%">Nama</td>
                    <td width="2%">:</td>
                    <td width="64%"><?= $value->nama ?></td>
                </tr>
                <tr>
                    <td width="34%">No. Rekening</td>
                    <td width="2%"></td>
                    <td width="64%" style="white-space: nowrap;"><?= $value->no_rekening ?></td>
                </tr>
            </table>
        </div>
        <div style="width:50%;float:right">
            <table width="100%" style="color: #000;">
                <tr>
                    <td width="34%">No. Transaksi</td>
                    <td width="2%">:</td>
                    <td width="64%"><b><?= $value->tanggal ?>/<?= $value->id_dt ?></b></td>
                </tr>
                <tr>
                    <td width="34%">Tanggal</td>
                    <td width="2%">:</td>
                    <td width="64%"><?= indo_tgl($value->tanggal) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <br/><br/>
    <table width="100%" style="color: #000;">
        <tr>
            <td>Jumlah Setor</td>
            <td><h1>Rp. <?= currency($value->masuk) ?>, -</h1> </td>
        </tr>
        <tr valign="top">
            <td width="20%">Terbilang</td>
            <td><i><u># <?= strtoupper(terbilang($value->masuk))?> RUPIAH #</u></i></td>
        </tr>
    </table>
    <br/>
  


    <p style="float:right;">

    </p>


    <table width="100%">

        <tr>
            <td width="30%" align="left">&nbsp;</td>
            <td width="30%"></td>
            <td width="30%" align="center"><?= $inst->kabupaten ?>, <?= indo_tgl($value->tanggal) ?></td>
        </tr>
        <tr>
            <td width="30%" align="center">Mengetahui</td>
            <td width="30%"  align="center">Kasir</td>
            <td width="30%" align="center">Anggota</td>
        </tr>
        <tr>
            <td width="30%" align="left">&nbsp;</td>
            <td width="30%"></td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td width="30%" align="left">&nbsp;</td>
            <td width="30%"  align="right"></td>
            <td width="30%" align="center"></td>
        </tr>
        
        <tr>
            <td width="30%" align="center">( ...................... )</td>
            <td width="30%"  align="center">( Kasir : <?= $this->session->userdata('nama') ?>  )</td>
            <td align="center">( <?= $value->nama ?>  )</td>
        </tr>
    </table>
    
    </div>
    

</body>