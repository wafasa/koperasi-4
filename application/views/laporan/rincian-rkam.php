<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4-landscape.css') ?>" media="all" />
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<body onload="cetak();">
    <div class="page">
        <center>RINCIAN RENCANA KEGIATAN DAN ANGGARAN MADRASAH (RKAM)<br/>TAHUN PELAJARAN <?= $thn_agg->tahun_anggaran ?></center>
    <table width="100%">
        
    </table><br/>
    <table width="100%">
        <tr><td width="20%">Nama Madrasah</td><td width="1%">: </td><td width="79"><?= $attr->nama ?></td></tr>
        <tr><td>Desa/Kecamatan</td><td>:</td><td> <?= $attr->kelurahan ?> / <?= $attr->kecamatan ?></td></tr>
        <tr><td>Kabupaten/Kota</td><td>:</td><td> <?= $attr->kabupaten ?></td></tr>
        <tr><td>Provinsi</td><td>:</td><td> <?= $attr->provinsi ?></td></tr>
        <tr><td>Triwulan</td><td>:</td><td><?= triwulan(date("m")) ?></td></tr>
        <tr><td>Sumber Dana</td><td>:</td><td>BOS</td></tr>
    </table>
    <br/>
    <table width="100%" class="tabel-laporan">
        <thead>
        <tr>
            <th width="10%" rowspan="2">No. Urut</th>
            <th width="10%" rowspan="2">No. Kode</th>
            <th width="40%" rowspan="2">Uraian</th>
            <th width="10%" rowspan="2">Jumlah (dalam Rp.)</th>
            <th colspan="30%">Semester</th>
        </tr>
        <tr>
            <th>I</th>
            <th>II</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $total = 0;
        foreach ($list_data['data'] as $key1 => $data) { ?>
        <tr valign="top">
            <td align="center"><?= ++$key1 ?></td>
            <td><?= $data->kode ?></td>
            <td><?= $data->nama_program ?></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="center"></td>
        </tr>
            <?php 
            foreach ($data->child1 as $key2 => $data2) { ?>
            <tr>
                <td></td>
                <td><?= $data2->kode ?></td>
                <td><div style="margin-left: 20px;"><?= $data2->nama_program ?></div></td>
                <td align="right"><?= currency($data2->total) ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
                foreach ($data2->child2 as $key3 => $data3) { ?>
                <tr>
                    <td></td>
                    <td><?= $data3->kode ?></td>
                    <td><div style="margin-left: 40px;"><?= $data3->nama_program ?></div></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> 
                <?php 
                    foreach ($data3->child3 as $key4 => $data4) { ?>
                    <tr>
                        <td></td>
                        <td><?= $data4->kode ?></td>
                        <td><div style="margin-left: 60px;"><?= $data4->nama_program ?></div></td>
                        <td></td>
                        <td align="center"><?= ($data4->semester1 === '1')?'&checkmark;':'' ?></td>
                        <td align="center"><?= ($data4->semester2 === '1')?'&checkmark;':'' ?></td>
                    </tr>
                    <?php 
                        foreach ($data4->child4 as $key5 => $data5) { ?>
                        <tr>
                            <td></td>
                            <td><?= $data5->kode ?></td>
                            <td><div style="margin-left: 80px;"><?= $data5->nama_program ?></div></td>
                            <td align="right"><?= currency($data5->nominal) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php }
                    }
                }
            }
        } ?>
        </tbody>
    </table>
    <br/>
    
    <table align="right" width="100%">
        <tr><td width="33%">Mengetahui<br/>Ketua Komite Madrasah</td><td width="33%">&nbsp;</td><td width="33%">Menyetujui<br/>Kepala Madrasah</td> </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>( <?= $attr->ketua_komite ?> )</td><td></td><td>( <?= $attr->kepala ?> )<br/>NIP. <?= $attr->nip_kepala ?></td></tr>
    </table>
    </div>
</body>