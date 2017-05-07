<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_penerimaan_pengeluaran(1);
        $('#add_penerimaan_pengeluaran').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
        });

        $('#reload_penerimaan_pengeluaran').click(function() {
            reset_form();
            get_list_penerimaan_pengeluaran(1);
        });
        
        $('#nama_transaksi').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/transaksi_lain_auto') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page, // page number
                        jenis: $('#jenis').val()
                    };
                },
                results: function (data, page) {
                    var more = (page * 20) < data.total; // whether or not there are more results available
         
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.data, more: more};
                }
            },
            formatResult: function(data){
                var markup = data.nama;
                return markup;
            }, 
            formatSelection: function(data){
                return data.nama;
            }
        });
        
        $('#cari_button').click(function() {
            
        });
    });
    
    function get_list_penerimaan_pengeluaran(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/penerimaan_pengeluarans") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_penerimaan_pengeluaran(p-1);
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
                            '<td align="center">'+datefmysql(v.tanggal)+'</td>'+
                            '<td>'+v.jenis+'</td>'+
                            '<td>'+v.keterangan+'</td>'+
                            '<td align="right">'+money_format(v.nominal)+'</td>'+
                            '<td align="right" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_penerimaan_pengeluaran(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_penerimaan_pengeluaran(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
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

    function edit_penerimaan_pengeluaran(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/penerimaan_pengeluarans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                var data = data.data[0];
                $('#id').val(data.id);
                $('#tanggal').val(datefmysql(data.tanggal));
                $('#jenis').val(data.jenis);
                $('#nama_transaksi').val(data.id_jenis);
                $('#s2id_nama_transaksi a .select2-chosen').html(data.nama_transaksi);
                $('#nominal').val(numberToCurrency(data.nominal));
                $('#keterangan').val(data.keterangan);
            }
        });
    }
        
    function paging(p) {
        get_list_penerimaan_pengeluaran(p);
    }

    function konfirmasi_save() {
        //$('#isi_penerimaan_pengeluaran').val(tinyMCE.get('isi').getContent());
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
                    save_penerimaan_pengeluaran();
                }
              }
            }
          });
      }

    function save_penerimaan_pengeluaran() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/penerimaan_pengeluaran') ?>',
            dataType: 'json',
            data: $('#formadd').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                hide_ajax_indicator();
                $('#judul, #isi, #nominal').val('');
                //reset_form();
                if (msg.act === 'add') {
                    $('#datamodal').modal('hide');
                    message_add_success();
                    get_list_penerimaan_pengeluaran(1);
                } else {
                    $('#datamodal').modal('hide');
                    message_edit_success();
                    get_list_penerimaan_pengeluaran(page);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_penerimaan_pengeluaran(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_penerimaan_pengeluaran(id, page) {
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
                        url: '<?= base_url('api/transaksi/penerimaan_pengeluaran') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_penerimaan_pengeluaran(page);
                        }
                    });
                }
              }
            }
        });
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
                    <button id="add_penerimaan_pengeluaran" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="reload_penerimaan_pengeluaran" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                          <th width="3%">No</th>
                          <th width="7%">Tanggal</th>
                          <th width="20%" class="left">Jenis</th>
                          <th width="50%" class="left">Keterangan</th>
                          <th width="13%" class="right">Nominal</th>
                          <th width="7%"></th>
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
                <form id="formadd" method="post" role="form" class="form-horizontal">
                <input type="hidden" name="id" id="id" />
                <div class="form-group">
                    <label class="control-label col-lg-3">Tanggal:</label>
                    <div class="col-lg-8">
                        <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" readonly="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Jenis:</label>
                    <div class="col-lg-8">
                        <select name="jenis" id="jenis" class="form-control">
                            <option value="">Pilih ...</option>
                            <option value="Pemasukkan">Pemasukkan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Nama Transaksi:</label>
                    <div class="col-lg-8">
                        <input type="text" name="nama_transaksi" id="nama_transaksi" class="select2-input" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-lg-3">Nominal Rp.:</label>
                    <div class="col-lg-8">
                        <input type="text" name="nominal"  class="form-control" id="nominal" onblur="FormNum(this)" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Keterangan:</label>
                    <div class="col-lg-8">
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="5"></textarea>
                    </div>
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