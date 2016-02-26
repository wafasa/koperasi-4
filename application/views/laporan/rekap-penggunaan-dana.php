<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_pencairan(1);
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Pencarian Penggunaan Dana');
            //tinyMCE.activeEditor.setContent('');
        });
        
        $('#awal, #akhir').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#cetak').click(function() {
            var wWidth = $(window).width();
            var dWidth = wWidth * 1;
            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            var x = screen.width/2 - dWidth/2;
            var y = screen.height/2 - dHeight/2;
            window.open('<?= base_url('laporan/print_penggunaan_dana/') ?>?'+$('#formsearch').serialize()+'&cetak=printer','Cetak Transaksi BANK','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
        });
        
        $('#excel_button').click(function() {
            location.href='<?= base_url('laporan/print_penggunaan_dana/') ?>?'+$('#formsearch').serialize()+'&cetak=excel';
        });

        $('#reload_pencairan').click(function() {
            reset_form();
            get_list_pencairan(1);
        });
        
        $('#nourut').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/rka_trans_auto') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page, // page number
                        level: 4
                    };
                },
                results: function (data, page) {
                    var more = (page * 20) < data.total; // whether or not there are more results available
         
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.data, more: more};
                }
            },
            formatResult: function(data){
                var markup = data.kode+'<br/>'+data.nama_program;
                return markup;
            }, 
            formatSelection: function(data){
                $('#s2id_nokode a .select2-chosen').html('');
                $('#nokode').val('');
                $('#uraian').val(data.nama_program);
                return data.kode;
            }
        });
        
        $('#nokode').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/rka_trans_auto') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page, // page number
                        parent: $('#nourut').val()
                    };
                },
                results: function (data, page) {
                    var more = (page * 20) < data.total; // whether or not there are more results available
         
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.data, more: more};
                }
            },
            formatResult: function(data){
                var markup = data.kode+'<br/>'+data.nama_program;
                return markup;
            }, 
            formatSelection: function(data){
                $('#uraian').val(data.nama_program);
                return data.kode;
            }
        });
    });
    
    function get_list_pencairan(p, id) {
        $('#datamodal').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/pencairans") ?>/page/'+p+'/id/'+id,
            data: $('#formsearch').serialize(),
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                //$("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_pencairan(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#example-advanced tbody').empty();          
                

                $.each(data.data,function(i, v){
                    var str = '';
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    str+= '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+v.uraian+'</td>'+
                            '<td>'+v.satuan+'</td>'+
                            '<td align="center">'+v.volume+'</td>'+
                            '<td align="right">'+numberToCurrency(v.nominal)+'</td>'+
                            '<td align="center">'+datefmysql(v.tanggal_kegiatan)+'</td>'+
                            
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
    
    function print_pencairan(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('transaksi/print_pencairan/') ?>?id='+id,'Cetak Transaksi Pencairan','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('a .select2-chosen').html('');
        $('#awal').val('<?= date("01/m/Y") ?>');
        $('#akhir').val('<?= date("d/m/Y") ?>');
    }

    function edit_pencairan(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit Transaksi Pencairan Bank');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/pencairans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data[0].id);
                $('#tanggal').val(datefmysql(data.data[0].tanggal));
                $('#tanggal_kegiatan').val(datefmysql(data.data[0].tanggal_kegiatan));
                $('#nourut').val(data.data[0].id_parent);
                $('#s2id_nourut a .select2-chosen').html(data.data[0].parent_program);
                $('#nokode').val(data.data[0].id_rka);
                $('#nobukti').val(data.data[0].no_bukti);
                $('#s2id_nokode a .select2-chosen').html(data.data[0].kode+' '+data.data[0].nama_program);
                $('#uraian').val(data.data[0].uraian);
                $('#satuan').val(data.data[0].satuan);
                $('#volume').val(data.data[0].volume);
                $('#nominal').val(numberToCurrency(data.data[0].nominal));
                $('#penerima').val(data.data[0].penerima);
            }
        });
    }
        
    function paging(p) {
        get_list_pencairan(p);
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
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="excel_button" class="btn btn-mini"><i class="fa fa-search"></i> Export Excel</button>
                    <button id="cetak" type="button" class="btn btn-mini"><i class="fa fa-print"></i> Cetak</button>
                    <button id="reload_pencairan" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                            <th width="5%" rowspan="2">No</th>
                            <!--<th width="3%" class="left">Urut</th>-->
                            <th width="50%" class="left" rowspan="2">Jenis Penggunaan / Pembelanjaan <br/>(<i>EXPENDITURE</i>)</th>
                            <th width="20%" colspan="2">KUANTITAS</th>
                            <th width="10%" class="right" rowspan="2">Jumlah Dana (Rp)</th>
                            <th width="15%" rowspan="2">Tanggal Pelaksanaan Kegiatan</th>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <th>Volume</th>
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
            <div class="modal-dialog" style="width: 700px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="formsearch" method="post" role="form">
                <input type="hidden" name="id" id="id" />
                <div class="form-group">
                    <label class="control-label">Tanggal Kegiatan:</label>
                    <input type="text" name="awal" class="form-control" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("01/m/Y") ?>" />
                    <input type="text" name="akhir" class="form-control" style="width: 145px;" id="akhir" value="<?= date("d/m/Y") ?>" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Urut:</label>
                    <input type="text" name="nourut"  class="js-data-example-ajax" id="nourut">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Kode RKA:</label>
                    <input type="text" name="nokode"  class="js-data-example-ajax" id="nokode" maxlength="10">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Bukti:</label>
                    <input type="text" name="nobukti"  class="form-control" id="nobukti" maxlength="10">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Uraian <i>Memorial</i>:</label>
                    <textarea name="uraian" class="form-control" id="uraian"></textarea>
                </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_pencairan(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>