<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        get_list_anggota(1);
        $('#add_anggota').click(function() {
            reset_form();
            $('#datamodal_add').modal('show');
            $('#datamodal_add h4.modal-title').html('Tambah <?= $title ?>');
        });
        
        $('#tanggal, #awal, #akhir').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#cari_button').click(function() {
            $('#datamodal_search').modal('show');
        });
        
        $('#jumlah, #jumlah_simpanan_wajib').focus(function() {
            var nilai = ($(this).val() === '')?0:$(this).val();
            $(this).val(currencyToNumber(nilai));
        });
        
        $('#tanggal').blur(function() {
            var nilai_this = $('#tanggal').val();
            var nilai_hide = $('#tanggal_hide').val();
            if (nilai_this === '') {
                $('#tanggal').val(nilai_hide);
            }
        }); 

        $('#reload_anggota').click(function() {
            reset_form();
            get_list_anggota(1);
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/anggota_auto') ?>",
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
    
    function get_list_anggota(p, id) {
        $('#datamodal_search').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/masterdata/anggotas") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_anggota(p-1);
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
                            '<td align="center">'+datefmysql(v.tgl_masuk)+'</td>'+
                            '<td>'+v.no_rekening+'</td>'+
                            '<td>'+v.no_ktp+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td>'+v.alamat+'</td>'+
                            //'<td align="right">'+money_format(v.simpanan_wajib)+'</td>'+
                            '<td align="right" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_anggota(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_anggota(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
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

    function edit_anggota(id) {
        
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/masterdata/anggotas') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#datamodal_add').modal('show');
                $('#datamodal_add h4.modal-title').html('Edit <?= $title ?>');
                var data = data.data[0];
                $('#id').val(data.id);
                $('#tanggal, #tanggal_hide').val(datefmysql(data.tgl_masuk));
                $('#norek').val(data.no_rekening);
                $('#noktp').val(data.no_ktp);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#jumlah').val(money_format(data.simpanan_pokok));
                $('#jumlah_simpanan_wajib').val(money_format(data.simpanan_wajib));
            }
        });
    }
    
    function konfirmasi_save() {
        //$('#isi_debitur').val(tinyMCE.get('isi').getContent());
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
                label: '<i class="fa fa-check-circle"></i>  Ya',
                className: "btn-primary",
                callback: function() {
                    save_anggota();
                }
              }
            }
          });
      }

    function save_anggota() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/masterdata/anggota') ?>',
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
                    $('#datamodal_add').modal('hide');
                    message_add_success();
                    get_list_anggota(1);
                } else {
                    $('#datamodal_add').modal('hide');
                    message_edit_success();
                    get_list_anggota(page);
                }
            },
            error: function() {
                //$('#datamodal').modal('hide');
                hide_ajax_indicator();
            }
        });
    }
    
    function delete_anggota(id, page) {
        bootbox.dialog({
            message: "<b>Semua transaksi yang terkait dengan nasabah ini akan ikut terhapus</b><br/>Anda yakin akan menghapus data ini?",
            title: "Konfirmasi Hapus",
            buttons: {
              batal: {
                label: '<i class="fa fa-times-circle"></i> Tidak',
                className: "btn-default",
                callback: function() {

                }
              },
              ya: {
                label: '<i class="fa fa-check-circle"></i>  Ya',
                className: "btn-primary",
                callback: function() {
                    $.ajax({
                        type: 'DELETE',
                        url: '<?= base_url('api/transaksi/anggota') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_anggota(page);
                        }
                    });
                }
              }
            }
        });
    }
        
    function paging(p) {
        get_list_anggota(p);
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
                    <button id="add_anggota" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="reload_anggota" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload Data</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="7%">Tgl Masuk</th>
                            <th width="10%" class="left">No. Rek</th>
                            <th width="10%" class="left">No. KTP</th>
                            <th width="20%" class="left">Nama</th>
                            <th width="30%" class="left">Alamat</th>
                            <!--<th width="10%" class="right">Simpanan Wajib</th>-->
                            <th width="10%"></th>
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
        
        <div id="datamodal_add" class="modal fade" style="overflow-y: auto">
            <div class="modal-dialog" style="width: 800px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="formadd" method="post" role="form" class="form-horizontal">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">Tanggal Daftar:</label>
                        <div class="col-lg-8">
                            <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" />
                        </div>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">Nomor Rekening:</label>
                        <div class="col-lg-8">
                            <input type="text" name="norek"  class="form-control" id="norek" placeholder="Otomatis" readonly="">
                        </div>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">Nama nasabah:</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama"  class="form-control" style="text-transform: uppercase;" onkeyup="this.value=this.value.toUpperCase();" id="nama">
                        </div>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">No. KTP:</label>
                        <div class="col-lg-8">
                            <input type="text" name="noktp"  class="form-control" id="noktp">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Alamat:</label>
                        <div class="col-lg-8">
                            <textarea name="alamat" id="alamat" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Pekerjaan:</label>
                        <div class="col-lg-8">
                            <input type="text" name="pekerjaan"  class="form-control" id="pekerjaan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Agama:</label>
                        <div class="col-lg-8">
                            <select name="agama" id="agama" class="form-control">
                                <option value="">Pilih ...</option>
                                <?php foreach ($agama as $data) { ?>
                                <option value="<?= $data ?>"><?= $data ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">Jumlah Simpanan Pokok:</label>
                        <div class="col-lg-8">
                            <input type="text" name="jumlah" onblur="FormNum(this);" class="form-control" id="jumlah" />
                        </div>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label col-lg-3">Jumlah Simpanan Wajib:</label>
                        <div class="col-lg-8">
                            <input type="text" name="jumlah_simpanan_wajib" onblur="FormNum(this);" class="form-control" id="jumlah_simpanan_wajib" />
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
        
        <div id="datamodal_search" class="modal fade">
            <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Pencarian Data</h4>
            </div>
            <div class="modal-body">
                <form id="form_search" method="post" role="form" class="form-horizontal">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label col-lg-3">Nama:</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama"  class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">No. Rekening:</label>
                        <div class="col-lg-8">
                            <input type="text" name="norek"  class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_anggota(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
      </div>
      <!-- END PAGE -->
    </div>