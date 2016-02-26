<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_penerimaan_pajak(1);
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Pencarian Transaksi Pajak');
            //tinyMCE.activeEditor.setContent('');
        });
        
        $('#tanggal').datepicker({
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        }).on('changeDate', function(){
            $(this).datepicker('hide');
            load_data_rencana($(this).val());
        });

        $('#reload_penerimaan_pajak').click(function() {
            reset_form();
            get_list_penerimaan_pajak(1);
        });
        
        $('#cetak').click(function() {
            var wWidth = $(window).width();
            var dWidth = wWidth * 1;
            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            var x = screen.width/2 - dWidth/2;
            var y = screen.height/2 - dHeight/2;
            window.open('<?= base_url('laporan/print_rekap_pajak/') ?>?'+$('#formsearch').serialize()+'&cetak=printer','Cetak Transaksi BANK','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
        });
        
        $('#excel_button').click(function() {
            location.href='<?= base_url('laporan/print_rekap_pajak/') ?>?'+$('#formsearch').serialize()+'&cetak=excel';
        });
    });
    
    function get_list_penerimaan_pajak(p, id) {
        $('#datamodal').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/penerimaan_pajaks") ?>/page/'+p+'/id/'+id,
            data: $('#formsearch').serialize(),
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_penerimaan_pajak(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#example-advanced tbody').empty();          
                
                var saldo = 0;
                $.each(data.data,function(i, v){
                    var str = '';
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    var ppn = ''; var pph21 = ''; var pph22 = ''; var pph23 = ''; var pengeluaran = '';
                    if (v.jenis_transaksi === 'Penerimaan') {
                        if (v.jenis_pajak === 'PPN') {
                            ppn = v.hasil_pajak;
                        }
                        if (v.jenis_pajak === 'PPh21') {
                            pph21 = v.hasil_pajak;
                        }
                        if (v.jenis_pajak === 'PPh22') {
                            pph22 = v.hasil_pajak;
                        }
                        if (v.jenis_pajak === 'PPh23') {
                            pph23 = v.hasil_pajak;
                        }
                        saldo += parseFloat(v.hasil_pajak);
                    } else {
                        pengeluaran = v.hasil_pajak;
                        saldo -= parseFloat(v.hasil_pajak);
                    }
                    
                    str+= '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td align="center">'+datefmysql(v.tanggal)+'</td>'+
                            '<td>'+v.kode_akun_pajak+'</td>'+
                            '<td>'+v.no_bukti+'</td>'+
                            '<td>'+v.uraian+'</td>'+
                            '<td align="right">'+numberToCurrency(ppn)+'</td>'+
                            '<td align="right">'+numberToCurrency(pph21)+'</td>'+
                            '<td align="right">'+numberToCurrency(pph22)+'</td>'+
                            '<td align="right">'+numberToCurrency(pph23)+'</td>'+
                            '<td align="right">'+numberToCurrency(pengeluaran)+'</td>'+
                            '<td align="right">'+numberToCurrency(saldo)+'</td>'+
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
    
    function print_pajak(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('transaksi/print_pajak/') ?>?id='+id,'Cetak Transaksi Pajak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#tanggal').val('<?= date("d/m/Y") ?>');
    }
        
    function paging(p) {
        get_list_penerimaan_pajak(p);
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
                    <!--<button id="add_penerimaan_pajak" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>-->
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="excel_button" class="btn btn-mini"><i class="fa fa-search"></i> Export Excel</button>
                    <button id="cetak" type="button" class="btn btn-mini"><i class="fa fa-print"></i> Cetak</button>
                    <button id="reload_penerimaan_pajak" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                            <th width="3%" rowspan="2">No</th>
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
                    <label class="control-label">Tanggal:</label>
                    <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("Y-m") ?>" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Kode Akun Pajak:</label>
                    <input type="text" name="nokode"  class="form-control" id="nokode">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Bukti:</label>
                    <input type="text" name="nobukti"  class="form-control" id="nobukti">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Jenis Transaksi:</label>
                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-control">
                        <option value="">Pilih ...</option>
                        <option value="Penerimaan">Penerimaan</option>
                        <option value="Setoran">Setoran</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Uraian:</label>
                    <textarea name="uraian" class="form-control" id="uraian"></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Jenis Pajak:</label>
                    <select name="jenis_pajak" id="jenis_pajak" class="form-control" onchange="hitungPajak();">
                        <option value="">Pilih ...</option>
                        <option value="PPN">PPN</option>
                        <option value="PPh21">PPh21</option>
                        <option value="PPh22">PPh22</option>
                        <option value="PPh23">PPh23</option>
                    </select>
                </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_penerimaan_pajak(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>