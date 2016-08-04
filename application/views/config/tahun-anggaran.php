<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        get_list_tahun_anggaran(1);
        $('#add_tahun_anggaran').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah Tahun Anggaran');
            //tinyMCE.activeEditor.setContent('');
        });

        $('#reload_tahun_anggaran').click(function() {
            reset_form();
            get_list_tahun_anggaran(1);
        });
    });
    
    function get_list_tahun_anggaran(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/config/tahun_anggarans") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_tahun_anggaran(p-1);
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
                    aktif = '-';
                    if (v.aktifasi === 'Ya') {
                        aktif = '&checkmark;';
                    }
                    str = '<tr class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+v.tahun_anggaran+'</td>'+
                            '<td>'+v.semester+'</td>'+
                            '<td>'+v.jumlah_siswa+'</td>'+
                            '<td align="center">'+aktif+'</td>'+
                            '<td align="center" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" title="Klik untuk aktifasi" onclick="aktivasi(\''+v.id+'\')"><i class="fa fa-check-circle"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" title="Klik untuk edit" onclick="edit_tahun_anggaran(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" title="Klik untuk delete" onclick="delete_tahun_anggaran(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
                        '</tr>';
                    $('#example-advanced tbody').append(str);
                    no = v.id;
                });                
            },
            complete: function() {
                hide_ajax_indicator();
            },
            error: function(e){
                hide_ajax_indicator();
            }
        });
    }
    
    function aktivasi(id) {
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/aktivasi_tahun_anggaran') ?>/id/'+id,
            dataType: 'json',
            success: function(data) {
                get_list_tahun_anggaran(1);
            }
        });
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
    }

    function edit_tahun_anggaran(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit Tahun Anggaran');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/tahun_anggarans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data[0].id);
                $('#tahun').val(data.data[0].tahun_anggaran);
                $('#semester').val(data.data[0].semester);
                $('#aktivasi').val(data.data[0].aktifasi);
                $('#jml_siswa').val(data.data[0].jumlah_siswa);
            }
        });
    }
        
    function paging(p) {
        get_list_tahun_anggaran(p);
    }

    function konfirmasi_save() {
        //$('#isi_tahun_anggaran').val(tinyMCE.get('isi').getContent());
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
                    save_tahun_anggaran();
                }
              }
            }
          });
      }

    function save_tahun_anggaran() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/tahun_anggaran') ?>',
            dataType: 'json',
            data: $('#formadd').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                hide_ajax_indicator();
                
                reset_form();
                if (msg.act === 'add') {
                    //$('#datamodal').modal('hide');
                    message_add_success();
                    get_list_tahun_anggaran(1);
                } else {
                    $('#datamodal').modal('hide');
                    message_edit_success();
                    get_list_tahun_anggaran(page);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_tahun_anggaran(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_tahun_anggaran(id, page) {
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
                        url: '<?= base_url('api/config/tahun_anggaran') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_tahun_anggaran(page);
                        }
                    });
                }
              }
            }
        });
    }

    function paging(page) {
        get_list_tahun_anggaran(page);
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
                    <button id="add_tahun_anggaran" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>
                    <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <button id="reload_tahun_anggaran" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                          <th width="7%">No</th>
                          <th width="20%" class="left">Tahun Anggaran</th>
                          <th width="30%" class="left">Semester</th>
                          <th width="15%" class="left">Jumlah Siswa</th>
                          <th width="10%" class="right">Aktifasi</th>
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
                    <label for="recipient-name" class="control-label">Tahun Anggaran:</label>
                    <input type="text" name="tahun"  class="form-control" id="tahun">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Semester:</label>
                    <select name="semester" id="semester" class="form-control">
                        <option value="">Pilih</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Jumlah Siswa:</label>
                    <input type="text" name="jml_siswa"  class="form-control" id="jml_siswa">
                </div>
                <div class="form-group">
                    <label for="semester1" class="control-label">Aktivasi :</label>
                    <select name="aktivasi" id="aktivasi" class="form-control">
                        <option value="Tidak">Tidak</option>
                        <option value="Ya">Ya</option>
                    </select>
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