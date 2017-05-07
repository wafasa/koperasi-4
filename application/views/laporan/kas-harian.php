<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_kas_harian(1);
        $('#search').click(function() {
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Pencarian');
        });
        
        $('#awal, #akhir').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_kas_harian').click(function() {
            reset_form();
            get_list_kas_harian(1);
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/kas_harian_auto') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page, // page number
                        jenissppb: $('#jenisbarang2').val()
                    };
                },
                results: function (data, page) {
                    var more = (page * 20) < data.total; // whether or not there are more results available
         
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.data, more: more};
                }
            },
            formatResult: function(data){
                var markup = data.kode+' - '+data.nama_program;
                return markup;
            }, 
            formatSelection: function(data){
                return data.kode+' - '+data.nama_program;
            }
        });
    });
    
    function get_list_kas_harian(p, id) {
        $('#form-pencarian').modal('hide');
        var id_transaksi = '';
        if (id !== undefined) {
            id_transaksi = id;
        }
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/laporan/kas_harians") ?>/page/'+p+'/id/'+id_transaksi,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_kas_harian(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#example-advanced tbody').empty();          
                
                var str_awal = '<tr style="background: #f4f4f4;">'+
                        '<td align="center"></td>'+
                        '<td><b>'+data.today+'</b></td>'+
                        '<td colspan="2"></td>'+
                        '<td><b>Sisa Saldo Tanggal '+data.kemaren+'</b></td>'+
                        '<td align="right"></td>'+
                        '<td align="right"></td>'+
                        '<td align="right"></td>'+
                        '<td align="right"><b>'+money_format(data.awal)+'</b></td>'+
                    '</tr>';
                $('#example-advanced tbody').append(str_awal);
                $.each(data.data,function(i, v){
                    var str = '';
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    str+= '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td><small>'+datetimefmysql(v.waktu)+'</small></td>'+
                            '<td>'+v.transaksi+'</td>'+
                            '<td>'+v.id_transaksi+'</td>'+
                            '<td>'+v.keterangan+'</td>'+
                            '<td align="right">'+money_format(v.awal)+'</td>'+
                            '<td align="right">'+money_format(v.masuk)+'</td>'+
                            '<td align="right">'+money_format(v.keluar)+'</td>'+
                            '<td align="right">'+money_format(v.sisa_saldo)+'</td>'+
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

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#awal, #akhir').val('<?= date("d/m/Y") ?>');
    }

    function paging(p) {
        get_list_kas_harian(p);
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
              <h4>Daftar List <?= $title ?></h4>
                <div class="tools"> 
                    <button id="search" class="btn btn-info btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <button id="reload_kas_harian" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload Data</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="10%">Tanggal</th>
                            <th width="15%" class="left">Transaksi</th>
                            <th width="5%">Kode</th>
                            <th width="40%" class="left">Keterangan</th>
                            <th width="10%" class="right">Awal</th>
                            <th width="10%" class="right">Masuk</th>
                            <th width="10%" class="right">keluar</th>
                            <th width="10%" class="right">Sisa</th>
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
        </div>
        <div id="datamodal" class="modal fade">
            <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_search" method="post" role="form" class="form-horizontal">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label col-lg-3">Tanggal:</label>
                        <div class="col-lg-3">
                            <input type="text" name="awal" class="form-control" id="awal" value="<?= date("d/m/Y") ?>" /> 
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="akhir" class="form-control" id="akhir" value="<?= date("d/m/Y") ?>" /> 
                        </div>
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
    </div>