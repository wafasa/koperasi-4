<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        $('.semester').hide();
        get_list_rka(1);
        $('#add_rka').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah RKA');
            //tinyMCE.activeEditor.setContent('');
        });

        $('#reload_rka').click(function() {
            reset_form();
            get_list_rka(1);
        });
        
        $('#print_rka').click(function() {
            var wWidth = $(window).width();
            var dWidth = wWidth * 1;
            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            var x = screen.width/2 - dWidth/2;
            var y = screen.height/2 - dHeight/2;
            window.open('<?= base_url('laporan/rincian_rkam') ?>','Cetak RKA','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/rka_auto') ?>",
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
                if (data.panjang_kode === '5') {
                    $('.semester').show();
                }
                return data.kode+' - '+data.nama_program;
            }
        });
    });
    
    function get_list_rka(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/masterdata/rkas") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_rka(p-1);
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
                            '<td>'+v.kode+'</td>'+
                            '<td>'+v.nama_program+'</td>'+
                            '<td align="right"></td>'+
                            '<td align="center" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_rka(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_rka(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
                        '</tr>';
                        $.each(v.child1, function(i2, v2) {
                            str+= '<tr data-tt-id='+i+'-'+i2+' data-tt-parent-id='+i+' class="'+highlight+'">'+
                                '<td align="center"></td>'+
                                '<td>'+v2.kode+'</td>'+
                                '<td><div style="margin-left: 20px;">'+v2.nama_program+'</div></td>'+
                                '<td align="right">'+numberToCurrency(v2.total)+'</td>'+
                                '<td align="center" class=aksi>'+
                                    '<button type="button" class="btn btn-default btn-mini" onclick="edit_rka(\''+v2.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                    '<button type="button" class="btn btn-default btn-mini" onclick="delete_rka(\''+v2.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                                '</td>'+
                            '</tr>';
                            $.each(v2.child2, function(i3, v3) {
                                str+= '<tr data-tt-id='+i+'-'+i2+'-'+i3+' data-tt-parent-id='+i+'-'+i2+' class="'+highlight+'">'+
                                    '<td align="center"></td>'+
                                    '<td>'+v3.kode+'</td>'+
                                    '<td><div style="margin-left: 40px;">'+v3.nama_program+'</div></td>'+
                                    '<td></td>'+
                                    '<td align="center" class=aksi>'+
                                        '<button type="button" class="btn btn-default btn-mini" onclick="edit_rka(\''+v3.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                        '<button type="button" class="btn btn-default btn-mini" onclick="delete_rka(\''+v3.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                                    '</td>'+
                                '</tr>';
                                $.each(v3.child3, function(i4, v4) {
                                    str+= '<tr data-tt-id='+i+'-'+i2+'-'+i3+'-'+i4+' data-tt-parent-id='+i+'-'+i2+'-'+i3+' class="'+highlight+'">'+
                                        '<td align="center"></td>'+
                                        '<td>'+v4.kode+'</td>'+
                                        '<td><div style="margin-left: 60px;">'+v4.nama_program+'</div></td>'+
                                        '<td></td>'+
                                        '<td align="center" class=aksi>'+
                                            '<button type="button" class="btn btn-default btn-mini" onclick="edit_rka(\''+v4.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                            '<button type="button" class="btn btn-default btn-mini" onclick="delete_rka(\''+v4.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                                        '</td>'+
                                    '</tr>';
                                    $.each(v4.child4, function(i5, v5) {
                                        str+= '<tr data-tt-id='+i+'-'+i2+'-'+i3+'-'+i4+'-'+i5+' data-tt-parent-id='+i+'-'+i2+'-'+i3+'-'+i4+' class="'+highlight+'">'+
                                            '<td align="center"></td>'+
                                            '<td>'+v5.kode+'</td>'+
                                            '<td><div style="margin-left: 80px;">'+v5.nama_program+'</div></td>'+
                                            '<td align="right">'+numberToCurrency(v5.nominal)+'</td>'+
                                            '<td align="center" class=aksi>'+
                                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_rka(\''+v5.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_rka(\''+v5.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
                                            '</td>'+
                                        '</tr>';
                                    });
                                });
                            });
                        });
                    $('#example-advanced tbody').append(str);
                    no = v.id;
                });                
                $("#example-advanced").treetable({ expandable: true });
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
        $('#oldpict').html('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#s2id_parent_code a .select2-chosen').html('');
        $('#thn_anggaran').val('<?= $thn_anggaran->tahun_anggaran ?>');
        $('#id_tahun_anggaran').val('<?= $thn_anggaran->id ?>');
    }

    function edit_rka(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit RKA');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/masterdata/rka') ?>/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data.id);
                $('#judul').val(data.data.kode);
                $('#isi').val(data.data.nama_program);
                $('#parent_code').val(data.data.id_parent);
                $('#nominal').val(numberToCurrency(data.data.nominal));
                $('#s2id_parent_code a .select2-chosen').html(data.data.parent_code+' - '+data.data.parent_name);
                if (data.data.panjang_kode === '5') {
                    $('.semester').show();
                    $('#semester1').val(data.data.semester1);
                    $('#semester2').val(data.data.semester2);

                }
            }
        });
    }
        
    function paging(p) {
        get_list_rka(p);
    }

    function konfirmasi_save() {
        //$('#isi_rka').val(tinyMCE.get('isi').getContent());
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
                    save_rka();
                }
              }
            }
          });
      }

    function save_rka() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/masterdata/rka') ?>',
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
                    //$('#datamodal').modal('hide');
                    message_add_success();
                    get_list_rka(1);
                } else {
                    $('#datamodal').modal('hide');
                    message_edit_success();
                    get_list_rka(page);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_rka(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_rka(id, page) {
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
                        url: '<?= base_url('api/masterdata/rka') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_rka(page);
                        }
                    });
                }
              }
            }
        });
    }

    function paging(page, tab, search) {
        get_list_rka(page, search);
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
              <h4>Daftar List RKA</h4>
                <div class="tools"> 
                    <button id="add_rka" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>
                    <button id="print_rka" class="btn btn-mini"><i class="fa fa-print"></i> Print RKA</button>
                    <button id="reload_rka" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                            <tr>
                                <th width="7%">No</th>
                                <th width="8%" class="left">No.&nbsp;Kode</th>
                                <th width="65%" class="left">Uraian</th>
                                <th width="10%" class="right">Jumlah</th>
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
                    <label class="control-label">Tahun Anggaran:</label>
                    <input type="text" class="form-control" id="thn_anggaran" value="<?= $thn_anggaran->tahun_anggaran ?>" />
                    <input type="hidden" name="id_tahun_anggaran" id="id_tahun_anggaran" value="<?= $thn_anggaran->id ?>" />
                </div>
                <div class="form-group">
                    <label class="control-label">Kode Parent:</label>
                    <input type="text" name="parent" class="js-data-example-ajax" id="parent_code" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Kode RKA:</label>
                    <input type="text" name="judul"  class="form-control" id="judul">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Uraian:</label>
                    <textarea name="isi" id="isi" class="isi form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Nominal<br/><small>Diisikan hanya untuk rincian kegiatan</small>:</label>
                    <input type="text" name="nominal"  class="form-control" onkeyup="FormNum(this);" id="nominal">
                </div>
                <div class="form-group semester">
                    <label for="semester1" class="control-label">Semester 1 :</label>
                    <select name="semester1" id="semester1" class="form-control">
                        <option value="">Pilih</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div class="form-group semester">
                    <label for="semester1" class="control-label">Semester 2 :</label>
                    <select name="semester2" id="semester2" class="form-control">
                        <option value="">Pilih</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
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