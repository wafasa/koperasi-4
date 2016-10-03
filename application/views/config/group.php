<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        get_list_group(1);
        get_list_account(1);
        $('#add_group').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah Group');
            $('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
        });
        
        $('#tanggal, #tanggal_disetujui').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_group').click(function() {
            reset_form();
            get_list_group(1);
        });
        
        $('#add_account').click(function() {
            $('#datamodal_account').modal('show');
            $('#datamodal_account h4.modal-title').html('Tambah Account');
            reset_form();
        });

        $('#checkall').click(function() {
            if ($(this).is(':checked') === true) {
                $('.checkbox').attr('checked','checked');
            } else {
                $('.checkbox').removeAttr('checked');
            }
        });
        
        $('#id_group').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/group_auto') ?>",
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
                var markup = data.nama;
                return markup;
            }, 
            formatSelection: function(data){
                return data.nama;
            }
        });
    });
    
    function get_list_group(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/config/groups") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_group(p-1);
                    return false;
                };

                $('#pagination_no').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary_no').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#table_group tbody').empty();          
                

                $.each(data.data,function(i, v){
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    str = '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right" class=aksi>'+
                                '<button type="button" title="Klik untuk edit privileges" class="btn btn-default btn-mini" onclick="edit_privileges(\''+v.id+'\')"><i class="fa fa-user-secret"></i></button> '+
                                '<button type="button" title="Klik untuk edit" class="btn btn-default btn-mini" onclick="edit_group(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" title="Klik untuk hapus" class="btn btn-default btn-mini" onclick="delete_group(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
                        '</tr>';
                    $('#table_group tbody').append(str);
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
    
    function get_list_account(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/config/accounts") ?>/page/'+p+'/id/'+id,
            data: '',
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_account(p-1);
                    return false;
                };

                $('#pagination_no2').html(pagination(data.jumlah, data.limit, data.page, 2));
                $('#page_summary_no2').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));

                $('#table_account tbody').empty();          
                

                $.each(data.data,function(i, v){
                    var highlight = 'odd';
                    if ((i % 2) === 1) {
                        highlight = 'even';
                    };
                    str = '<tr data-tt-id='+i+' class="'+highlight+'">'+
                            '<td align="center">'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+v.username+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td>'+v.nama_group+'</td>'+
                            '<td align="right" class=aksi>'+
                                '<button type="button" title="Klik untuk edit" class="btn btn-default btn-mini" onclick="edit_account(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" title="Klik untuk hapus" class="btn btn-default btn-mini" onclick="delete_account(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
                        '</tr>';
                    $('#table_account tbody').append(str);
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
        $('a .select2-chosen').html('&nbsp;');
    }

    function edit_group(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#wizard').bwizard('show','0');
        $('#datamodal h4.modal-title').html('Edit Group');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/groups') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                //$('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
                var data = data.data[0];
                $('#id').val(data.id);
                $('#nama').val(data.nama);
            }
        });
    }

    function edit_privileges(id_group) {
        $('#datamodal_privileges').modal('show');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/privileges') ?>/id/'+id_group,
            success: function(data) {
                $('#table_privileges tbody').empty();
                var nama_group = data.data[0];
                $('#label_group').html(nama_group.nama_group);
                $('#id_group_privileges').val(id_group);
                $.each(data.data, function (i, v) {
                    var checked = '';
                    if (v.check === 'TRUE') {
                        checked = 'checked';
                    }
                    var str = '<tr>'+
                        '<td align="center">'+(++i)+'</td>'+
                        '<td>'+((v.show_desktop === '1')?v.modul:'<i class="fa fa-eye-slash"></i> '+v.modul)+'</td>'+
                        '<td>'+v.menu+'</td>'+
                        '<td align="center"><input type="checkbox" name="privileges[]" value="'+v.id+'" '+checked+' class="checkbox" /></td>'+
                        '</tr>';
                    $('#table_privileges tbody').append(str);
                    $('.checkbox').click(function() {
                        if ($(this).is(':checked') === false) {
                            $('#checkall').removeAttr('checked');
                        }
                    });
                });
            }
        });
    }
        
    function paging(p, tab) {
        if (tab === '1') {
            get_list_group(p);
        } else {
            get_list_account(p);
        }
    }

    function konfirmasi_save(opsi) {
        //$('#isi_group').val(tinyMCE.get('isi').getContent());
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
                    if (opsi === 'group') {
                        save_group();    
                    }
                    if (opsi === 'akun') {
                        save_account();
                    }
                    if (opsi === 'privileges') {
                        save_privileges();
                    }
                }
              }
            }
          });
      }

    function save_group() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/group') ?>',
            dataType: 'json',
            data: $('#formadd').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                //reset_form();
                if (msg.status === true) {
                    $('#datamodal').modal('hide');
                    if (msg.act === 'add') {
                        message_add_success();
                        get_list_group(1);
                    } else {
                        message_edit_success();
                        get_list_group(page);
                    }
                }
            },
            complete: function() {
                hide_ajax_indicator();
            },
            error: function() {
                hide_ajax_indicator();
            }
        });
    }

    function delete_group(id, page) {
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
                        url: '<?= base_url('api/config/group') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_group(page);
                        }
                    });
                }
              }
            }
        });
    }
    
    function edit_account(id) {
        $('#oldpict').html('');
        $('#datamodal_account').modal('show');
        $('#datamodal_account h4.modal-title').html('Edit Account');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/accounts') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                //$('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
                var data = data.data[0];
                $('#id2').val(data.id);
                $('#nama2').val(data.nama);
                $('#username').val(data.username);
                $('#id_group').val(data.id_user_group);
                $('#s2id_id_group a .select2-chosen').html(data.nama_group);
            }
        });
    }

    function save_account() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/account') ?>',
            dataType: 'json',
            data: $('#formadd_account').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                //reset_form();
                if (msg.status === true) {
                    $('#datamodal_account').modal('hide');
                    if (msg.act === 'add') {
                        message_add_success();
                        get_list_account(1);
                    } else {
                        message_edit_success();
                        get_list_account(page);
                    }
                }
            },
            complete: function() {
                hide_ajax_indicator();
            },
            error: function() {
                hide_ajax_indicator();
            }
        });
    }

    function delete_account(id, page) {
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
                        url: '<?= base_url('api/config/account') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_account(page);
                        }
                    });
                }
              }
            }
        });
    }

    function save_privileges() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/privileges') ?>',
            data: $('#form_privileges').serialize(),
            success: function(data) {
                if (data === true) {
                    $('#datamodal_privileges').modal('hide');
                    message_edit_success();
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
                
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="active"><a href="#home" role="tab" data-toggle="tab">User Level</a></li>
                      <li><a href="#profile" role="tab" data-toggle="tab">User Account</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="toolbar-left"> 
                                <button id="add_group" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah Group</button>
                                <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                                <button id="reload_group" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                            </div><br/>
                            <table width="100%" class="table table-stripped table-hover tabel-advance" id="table_group">
                                <thead>
                                <tr>
                                    <th width="3%">No.</th>
                                    <th width="82%" class="left">Nama</th>
                                    <th width="15%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                            <div id="pagination_no" class="pagination"></div>
                            <div class="page_summary" id="page_summary_no"></div>
                        </div>
                        <div class="tab-pane" id="profile">
                            <div class="toolbar-left"> 
                                <button id="add_account" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah Account</button>
                                <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                                <button id="reload_account" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                            </div><br/>
                            <table width="100%" class="table table-stripped table-hover tabel-advance" id="table_account">
                                <thead>
                                    <tr>
                                        <th width="3%">No.</th>
                                        <th width="17%" class="left">ID / Username</th>
                                        <th width="55%" class="left">Nama</th>
                                        <th width="15%" class="left">User Group</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                            <div id="pagination_no2" class="pagination"></div>
                            <div class="page_summary" id="page_summary_no2"></div>
                        </div>
                    </div>
                    
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="datamodal" class="modal fade" style="overflow-y: auto">
            <div class="modal-dialog" style="width: 600px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="formadd" method="post" role="form">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label">Nama Group:</label>
                        <input type="text" name="nama" id="nama" class="form-control"  />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="konfirmasi_save('group');"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
        <div id="datamodal_account" class="modal fade" style="overflow-y: auto">
            <div class="modal-dialog" style="width: 600px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="formadd_account" method="post" role="form">
                    <input type="hidden" name="id" id="id2" />
                    <div class="form-group">
                        <label class="control-label">Nama Group:</label>
                        <input type="text" name="id_group" id="id_group" class="select2-input"  />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nama Lengkap:</label>
                        <input type="text" name="nama" id="nama2" class="form-control"  />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Username:</label>
                        <input type="text" name="username" id="username" class="form-control"  />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="konfirmasi_save('akun');"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="datamodal_privileges" class="modal fade" style="overflow-y: auto">
            <div class="modal-dialog" style="width: 600px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Hak Akses</h4>
            </div>
            <div class="modal-body">
            <div class="alert alert-info">
              <strong>Info!</strong> Nama Group Level: <span id="label_group"></span>
            </div>
                <form id="form_privileges" method="post" role="form">
                    <input type="hidden" name="id_group" id="id_group_privileges">
                    <table width="100%" class="table table-stripped table-hover tabel-advance" id="table_privileges">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="40%" class="left">Modul</th>
                            <th width="40%" class="left">Nama Menu</th>
                            <th width="10%"><input type="checkbox" id="checkall" /></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="konfirmasi_save('privileges');"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
      </div>
      <!-- END PAGE -->
    </div>