<script type="text/javascript">
    $(function() {
        get_list_shu(1);
        $('#reload').click(function() {
            get_list_shu(1);
        });
        
        $("#tahun").datepicker({
            format: "yyyy", // Notice the Extra space at the beginning
            viewMode: "years", 
            minViewMode: "years"
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
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
    
    function get_list_shu(p, id) {
        $('#datamodal').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/laporan/shus") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_shu(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#info_tahun').html($('#tahun').val());
                $('#pendapatan_bunga').html(money_format(data.pend_bunga));
                $('#pendapatan_simpanan').html(money_format(data.pend_simpanan));
                $('#pengeluaran_rutin').html(money_format(data.pengeluaran));
                var total_shu = parseFloat(data.pend_bunga)+parseFloat(data.pend_simpanan)-parseFloat(data.pengeluaran);
                $('#total_sisa_shu').html(money_format(total_shu));
                
                $('#persen_jasa_usaha').html(data.persen_jasa_usaha+' %');
                $('#persen_simpanan').html(data.persen_simpanan+' %');
                
                var result1 = (parseFloat(data.persen_jasa_usaha)/100)*total_shu;
                var result2 = (parseFloat(data.persen_simpanan)/100)*total_shu;
                $('#result1').html(money_format(result1));
                $('#result2').html(money_format(result2));
                
                $('#example-advanced tbody').empty();          
                
                
                $.each(data.data,function(i, v){
                    var str = '';
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    var shu_jasa_usaha_anggota = parseFloat(v.shu_jasa_usaha);
                    var shu_ju = (shu_jasa_usaha_anggota/data.pend_bunga)*result1;
                    
                    var shu_jasa_simpanan = parseFloat(v.simpanan_wajib);
                    var shu_js = (shu_jasa_simpanan/data.pend_simpanan)*result2;
                    
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
        get_list_shu(p);
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
                    <button id="cari_button" class="btn btn-info btn-mini"><i class="fa fa-search"></i> Pencarian</button>
                    <button id="reload" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload data</button>
                </div>
            </div>
            <div class="grid-body">
                <i class="fa fa-bug"></i> Rekap Selama Tahun <span id="info_tahun"></span>
                <table style="width: 50%;" class="table table-bordered">
                    <tr>
                        <th>Kategori</th>
                        <th class="right">Jumlah</th>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pendapatan Bunga</td>
                        <td width="40%" align="right" id="pendapatan_bunga"></td>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pendapatan Simpanan Wajib</td>
                        <td width="40%" align="right" id="pendapatan_simpanan"></td>
                    </tr>
                    <tr>
                        <td width="60%">&sum; Pengeluaran Rutin</td>
                        <td width="40%" align="right" id="pengeluaran_rutin"></td>
                    </tr>
                    <tr>
                        <td width="60%">Total SHU</td>
                        <td width="40%" align="right" id="total_sisa_shu"></td>
                    </tr>
                </table>
                <i class="fa fa-bug"></i> Pembagian SHU Masing-masing Pos
                <table style="width: 50%;" class="table table-bordered">
                    <tr>
                        <td width="60%">Persentase Pembagi Untuk Jasa Usaha</td>
                        <td width="10%" align="center" id="persen_jasa_usaha"></td>
                        <td width="30%" align="right" id="result1"></td>
                    </tr>
                    <tr>
                        <td width="60%">Persentase Pembagi Untuk Jasa Simpanan</td>
                        <td width="10%" align="center" id="persen_simpanan"></td>
                        <td width="30%" align="right" id="result2"></td>
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
              <h4 class="modal-title">Parameter Pencarian </h4>
            </div>
            <div class="modal-body">
                <form id="form_search" method="post" role="form" class="form-horizontal">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label col-lg-3">Tahun:</label>
                        <div class="col-lg-3">
                            <input type="text" name="tahun" id="tahun" class="form-control" value="<?= date("Y") ?>" /> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_shu(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</div>
      <!-- END PAGE -->
    