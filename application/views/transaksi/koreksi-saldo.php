<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_koreksi_saldo(1);
        $('#add_koreksi_saldo').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
            load_sisa_saldo();
        });
        
        $('#tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_koreksi_saldo').click(function() {
            reset_form();
            get_list_koreksi_saldo(1);
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/koreksi_saldo_auto') ?>",
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
    
    function load_sisa_saldo() {
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/sisa_saldo') ?>',
            success: function(data) {
                $('#sisa_saldo').val(data);  
            }
        });
    }
    
    function hitung_penyesuaian() {
        var sisa_komputer = parseFloat($('#sisa_saldo').val());
        var sisa_kenyataan= parseFloat($('#sisa_saldo_asli').val());
        var penyesuaian = sisa_kenyataan - sisa_komputer;
        $('#penyesuaian').val(penyesuaian);
    }
    
    function get_list_koreksi_saldo(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/koreksi_saldos") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_koreksi_saldo(p-1);
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
                            '<td align="center">'+datetimefmysql(v.waktu)+'</td>'+
                            '<td>'+v.transaksi+'</td>'+
                            '<td>'+v.keterangan+'</td>'+
                            '<td align="right">'+money_format(v.masuk)+'</td>'+
                            '<td align="right">'+money_format(v.keluar)+'</td>'+
                            '<td align="center" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_koreksi_saldo(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
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
        $('#tanggal').val('<?= date("d/m/Y") ?>');
    }

    function edit_koreksi_saldo(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/koreksi_saldos') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data[0].id);
                $('#tanggal').val(datefmysql(data.data[0].tanggal));
                $('#nokode').val(data.data[0].kode_akun_pajak);
                $('#nobukti').val(data.data[0].no_bukti);
                $('#nominal').val(numberToCurrency(data.data[0].nominal));
                $('#perhitungan').val(money_format(data.data[0].hasil_pajak));
                $('#jenis_transaksi').val(data.data[0].jenis_transaksi);
                $('#jenis_pajak').val(data.data[0].jenis_pajak);
                $('#uraian').val(data.data[0].uraian);
            }
        });
    }
        
    function paging(p) {
        get_list_koreksi_saldo(p);
    }

    function konfirmasi_save() {
        //$('#isi_koreksi_saldo').val(tinyMCE.get('isi').getContent());
        bootbox.dialog({
            message: "Anda yakin akan menyimpan data ini?",
            title: "Konfirmasi Simpan",
            buttons: {
              batal: {
                label: '<i class="fa fa-times-circle"></i> Tidak',
                className: "btn-default",
                callback: function() {

                }
              },
              ya: {
                label: '<i class="fa fa-save"></i>  Ya',
                className: "btn-primary",
                callback: function() {
                    save_koreksi_saldo();
                }
              }
            }
          });
      }

    function save_koreksi_saldo() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/koreksi_saldo') ?>',
            dataType: 'json',
            data: $('#formadd').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                hide_ajax_indicator();

                $('#datamodal').modal('hide');
                message_add_success();
                get_list_koreksi_saldo(1);
                
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_koreksi_saldo(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_koreksi_saldo(id, page) {
        bootbox.dialog({
            message: "Anda yakin akan menghapus data ini?",
            title: "Konfirmasi Hapus",
            buttons: {
              batal: {
                label: '<i class="fa fa-times-circle"></i> Tidak',
                className: "btn-default",
                callback: function() {

                }
              },
              ya: {
                label: '<i class="fa fa-trash"></i>  Ya',
                className: "btn-primary",
                callback: function() {
                    $.ajax({
                        type: 'DELETE',
                        url: '<?= base_url('api/transaksi/koreksi_saldo') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_koreksi_saldo(page);
                        }
                    });
                }
              }
            }
        });
    }

    function paging(page, tab, search) {
        get_list_koreksi_saldo(page, search);
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
                    <button id="add_koreksi_saldo" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>
                    <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <button id="reload_koreksi_saldo" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%" class="left">Nama Transaksi</th>
                            <th width="40%" class="left">Keterangan</th>
                            <th width="10%" class="right">Masuk</th>
                            <th width="10%" class="right">keluar</th>
                            <th width="5%"></th>
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
                <form id="formadd" method="post" role="form">
                <input type="hidden" name="id" id="id" />
                <div class="form-group">
                    <label class="control-label">Tanggal:</label>
                    <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" />
                </div>
                <div class="form-group">
                    <label class="control-label">Sisa Saldo Koperasi:</label>
                    <input type="text"  class="form-control" id="sisa_saldo" readonly="">
                </div>
                <div class="form-group">
                    <label class="control-label">Sisa Saldo Sebenarnya:</label>
                    <input type="text"  class="form-control" id="sisa_saldo_asli" onblur="hitung_penyesuaian();">
                </div>
                <div class="form-group">
                    <label class="control-label">Penyesuaian:</label>
                    <input type="text" name="penyesuaian"  class="form-control" id="penyesuaian" readonly="">
                </div>
                <div class="form-group">
                    <label class="control-label">Keterangan:</label>
                    <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="konfirmasi_save();"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>