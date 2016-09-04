<script type="text/javascript">
    $(function() {
        get_list_tabungan(1);
        $('#reload').click(function() {
            get_list_tabungan(1);
        });
    });
    
    function hitung_shu(i) {
        var sisa_shu = parseFloat(currencyToNumber($('#total_sisa_shu').html()));
        if (i === 1) {
            var persen_usaha = $('#persen'+i).val()/100;
            $('#result'+i).html(money_format(sisa_shu*persen_usaha));
        } else {
            var persen_simpanan = $('#persen'+i).val()/100;
            $('#result'+i).html(money_format(sisa_shu*persen_simpanan));
        }
        
    }
    
    function get_list_tabungan(p, id) {
        $('#datamodal_search').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/tabungans") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_tabungan(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#example-advanced tbody').empty();          
                
                var pendapatan_bunga = parseFloat(currencyToNumber($('#pendapatan_bunga').html()));
                var persentase_pend_bunga = currencyToNumber($('#result1').html());
                
                var pendapatan_simpanan = parseFloat(currencyToNumber($('#pendapatan_simpanan').html()));
                var persentase_pend_simpanan = currencyToNumber($('#result2').html());
                
                $.each(data.data,function(i, v){
                    var str = '';
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    var shu_jasa_usaha_anggota = parseFloat(v.shu_jasa_usaha);
                    var shu_ju = (shu_jasa_usaha_anggota/pendapatan_bunga)*persentase_pend_bunga;
                    
                    var shu_jasa_simpanan = parseFloat(v.simpanan_wajib);
                    var shu_js = (shu_jasa_simpanan/pendapatan_simpanan)*persentase_pend_simpanan;
                    
                    str+= '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+v.no_rekening+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td>'+v.alamat+'</td>'+
                            '<td align="right">'+money_format(shu_ju)+'</td>'+
                            '<td align="right">'+money_format(shu_js)+'</td>'+
                            '<td align="right">'+money_format(shu_ju+shu_js)+'</td>'+
                        '</tr>';
                    $('#example-advanced tbody').append(str);
                });
            },
            complete: function() {
                hide_ajax_indicator();
                //$("#example-advanced").treetable({ expandable: true });
            },
            error: function(e){
                hide_ajax_indicator();
            }
        });
    }
    
    function paging(p) {
        get_list_tabungan(p);
    }
</script>

<div class="content">
      <ul class="breadcrumb">
        <li>
          <p>YOU ARE HERE</p>
        </li>
        <li><a href="#" class="active"><?= $title ?></a></li>
      </ul>
      <div class="row">
        <div class="col-md-12">
          <div class="grid simple ">
            <div class="grid-title">
              <h4>Laporan <?= $title ?></h4>
                <div class="tools"> 
                    <!--<button id="search" class="btn btn-info btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <button id="reload" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
                <i class="fa fa-bug"></i> Rekap Selama Tahun <?= date("Y") ?>
                <?php
                    $total_shu = $pend_bunga+$pend_simpanan-$pengeluaran;
                ?>
                <table style="width: 50%;" class="table table-bordered">
                    <tr>
                        <th>Kategori</th>
                        <th class="right">Jumlah</th>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pendapatan Bunga</td>
                        <td width="40%" align="right" id="pendapatan_bunga"><?= formatcurrency($pend_bunga) ?></td>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pendapatan Simpanan Wajib</td>
                        <td width="40%" align="right" id="pendapatan_simpanan"><?= formatcurrency($pend_simpanan) ?></td>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pengeluaran Rutin</td>
                        <td width="40%" align="right"><?= formatcurrency($pengeluaran) ?></td>
                    </tr>
                    <tr>
                        <td width="60%">Total SHU</td>
                        <td width="40%" align="right" id="total_sisa_shu"><?= formatcurrency($total_shu) ?></td>
                    </tr>
                </table>
                <i class="fa fa-bug"></i> Pembagian SHU Masing-masing Pos
                <table style="width: 50%;" class="table table-bordered">
                    <tr>
                        <td width="60%">Persentase Pembagi Untuk Jasa Usaha</td>
                        <td width="10%" align="center"><?= $persen_jasa->persen_jasa_usaha ?> %</td>
                        <td width="30%" align="right" id="result1"><?= formatcurrency($total_shu*($persen_jasa->persen_jasa_usaha/100)) ?></td>
                    </tr>
                    <tr>
                        <td width="60%">Persentase Pembagi Untuk Jasa Simpanan</td>
                        <td width="10%" align="center"><?= $persen_jasa->persen_simpanan ?> %</td>
                        <td width="30%" align="right" id="result2"><?= formatcurrency($total_shu*($persen_jasa->persen_simpanan/100)) ?></td>
                    </tr>
                </table>
                <i class="fa fa-bug"></i> Rekapitulasi Pembagian SHU untuk masing-masing anggota tahun <?= date("Y") ?>
                <table class="table table-stripped table-hover tabel-advance" id="example-advanced">
                    <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="10%" class="left">No. Rek</th>
                        <th width="20%" class="left">Nama</th>
                        <th width="30%" class="left">Alamat</th>
                        <th width="10%" class="right">SHU Jasa Usaha</th>
                        <th width="10%" class="right">SHU Jasa Simpanan</th>
                        <th width="10%" class="right">Jumlah SHU</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="pagination_no" class="pagination"></div>
                <div class="page_summary" id="page_summary_no"></div>
            </div>  
          </div>
        </div>
        </div>
        <div id="datamodal" class="modal fade">
            <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_search" method="post" role="form">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label">Tanggal:</label>
                        <input type="text" name="awal" class="form-control" style="width: 145px; margin-right: 10px;" id="awal" value="<?= date("d/m/Y") ?>" /> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_kas_harian(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</div>
      <!-- END PAGE -->
    