<script type="text/javascript">
    $(function() {
        get_list_rekening(1);
        $('#page_now_rekening').val(1);
        $('#bt_tambah_rekening').click(function(){
            $('.edit_hide').show();
            reset_data_rekening();
            $('#modal_rekening').modal('show');
            $('#modal_title_rekening').html('Tambah Rekening');
        });

        $('#bt_reset_rekening').click(function(){
            reset_data_rekening();
            get_list_rekening(1);
        });

        $('.form-control').keyup(function(){
            if($(this).val() !== ''){
                dc_validation_remove(this);
            }
        });

        $('#rekening_auto').select2({
            ajax: {
                url: "<?= base_url('api/akuntansi/rekening_auto') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) { // page is the one-based page number tracked by Select2
                    return {
                        q: term, //search term
                        page: page // page number
                    };
                },
                results: function (data, page) {
                    var more = (page * 20) < data.total; // whether or not there are more results available
         
                    // notice we return the value of more so Select2 knows if more results can be loaded
                    return {results: data.data, more: more};
                }
            },
            formatResult: function(data){
                var markup = data.kode+' '+data.nama;
                return markup;
            }, 
            formatSelection: function(data){
                return data.nama;
            }
        });   
      
    });

    function reset_data_rekening(){
       $('#id_rekening, #id_parent, .form-control, #pencarian_rekening').val('');
       $('#select_rek').html('Pilih Rekening');
       //$('.select2-chosen').html('');
       dc_validation_remove('.form-control');
    }

    function get_rekening(id){
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/akuntansi/rekening") ?>/id/'+id,
            cache: false,
            dataType : 'json',
            success: function(data) {                                
                $('#pagination2').html('&nbsp;<br/>&nbsp;<br/>');
                $('#rekening_summary').html(page_summary(1, 1, data.limit, data.page));

                $('#example-advanced tbody').empty();          
                extract_data(data);
                $('#example-advanced').treetable('expandAll');
            },
            error: function(e){
                access_failed(e.status);
            }
        });
    }

    function get_list_rekening(p){
        $('#page_now_rekening').val(p);
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/akuntansi/rekening_list") ?>/page/'+p,
            cache: false,
            data: 'pencarian='+$('#pencarian_rekening').val(),
            dataType : 'json',
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_rekening(p-1);
                    return false;
                };
                $("#example-advanced").treetable('destroy');
                $('#example-advanced tbody').empty();   
                extract_data(data);
                         
            },
            error: function(e){
                access_failed(e.status);
            }
        });
    }

    function extract_data(data){
        var str = '';
        var branch = ''; var parent = ''; var root = '';
        $.each(data.data,function(i, v){
            
            root = ((i+1) + ((data.page - 1) * data.limit))
            branch = 'data-tt-id="'+root+'"';
            str = draw_table(v, branch, parent, data.page, 0, root);
            $('#example-advanced tbody').append(str);
            
            if (v.child !== null) {
                $.each(v.child, function(i2 , v2){
                    branch = 'data-tt-id="'+root+'-'+i2+'"';
                    parent = 'data-tt-parent-id="'+root+'"';
                    str = draw_table(v2, branch, parent, data.page, 20, root+'-'+i2);
                    $('#example-advanced tbody').append(str);

                    if (v2.child !== null) {
                        $.each(v2.child, function(i3 , v3){
                            
                            branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'"';
                            parent = 'data-tt-parent-id="'+root+'-'+i2+'"';
                            str = draw_table(v3, branch, parent, data.page, 40, root+'-'+i2+'-'+i3);
                            $('#example-advanced tbody').append(str);

                            if (v3.child !== null) {
                                $.each(v3.child, function(i4 , v4){
                                    i4++;
                                    branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'"';
                                    parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'"';
                                    str = draw_table(v4, branch, parent, data.page, 60, root+'-'+i2+'-'+i3+'-'+i4);
                                    $('#example-advanced tbody').append(str);
                                    if (v4.child !== null) {
                                        $.each(v4.child, function(i5 , v5){
                                            i5++;
                                            branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'"';
                                            parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'-'+i4+'"';
                                            str = draw_table(v5, branch, parent, data.page, 80, root+'-'+i2+'-'+i3+'-'+i4+'-'+i5);
                                            $('#example-advanced tbody').append(str);
                                            if (v5.child !== null) {
                                                $.each(v5.child, function(i6 , v6){
                                                    i6++;
                                                    branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'"';
                                                    parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'"';
                                                    str = draw_table(v6, branch, parent, data.page, 100, root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6);
                                                    $('#example-advanced tbody').append(str);
                                                    if (v6.child !== null) {
                                                        $.each(v6.child, function(i7 , v7){
                                                            i7++;
                                                            branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'"';
                                                            parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'"';
                                                            str = draw_table(v7, branch, parent, data.page, 120, root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7);
                                                            $('#example-advanced tbody').append(str);
                                                            if (v7.child !== null) {
                                                                $.each(v7.child, function(i8, v8) {
                                                                    i8++;
                                                                    branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'-'+i8+'"';
                                                                    parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'"';
                                                                    str = draw_table(v8, branch, parent, data.page, 140, root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'-'+i8);
                                                                    $('#example-advanced tbody').append(str);
                                                                    if (v8.child !== null) {
                                                                        $.each(v8.child, function(i9, v9) {
                                                                            i9++;
                                                                            branch = 'data-tt-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'-'+i8+'-'+i9+'"';
                                                                            parent = 'data-tt-parent-id="'+root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'-'+i8+'"';
                                                                            str = draw_table(v9, branch, parent, data.page, 160, root+'-'+i2+'-'+i3+'-'+i4+'-'+i5+'-'+i6+'-'+i7+'-'+i8+'-'+i9);
                                                                            $('#example-advanced tbody').append(str);
                                                                        });
                                                                    }
                                                                    
                                                                });
                                                            }
                                                        });
                                                    };
                                                });
                                            };
                                        });
                                    };
                                });
                            };
                        });
                    };
                });
            };
        branch = ''; parent = '';
        });
        
        $("#example-advanced").treetable({expandable:true}); 
    }

    function draw_table(v, branch, parent, page, indent, nodeId){

        var str = '<tr '+branch+' '+parent+'>'+
                '<td>'+ v.kode +'</td>'+
                '<td><span style="margin-left: '+indent+'px;">'+v.nama+'</span></td>'+
                '<td align="right">'+
                    '<button type="button" class="btn btn-default btn-mini" onclick="edit_rekening('+v.id+', '+page+')"><i class="fa fa-pencil"></i> </button> '+
                    '<button type="button" class="btn btn-default btn-mini" onclick="delete_rekening('+v.id+', '+page+',\''+nodeId+'\')"><i class="fa fa-trash-o"></i> </button>'+
                '</td>'+
            '</tr>';
        return str;
    }


    function save_rekening(){
        var stop = false;
        
        if ($('#kode').val() === '') {
            dc_validation('#kode', 'Kode rekening harus diisi!');
            stop = true;   
        };
        if ($('#rekening').val() === '') {
            dc_validation('#rekening', 'Nama rekening harus diisi!');
            stop = true;   
        };

        if (stop) {
            return false;
        };

        var update = '';
        if($('#id_rekening').val() !== ''){
            update = 'id/'+ $('#id_rekening').val();
        }


        show_ajax_indicator();
        $.ajax({
            type : 'POST',
            url: '<?= base_url("api/akuntansi/rekening") ?>/'+update,
            data: $('#formkec').serialize(),
            cache: false,
            dataType : 'json',
            success: function(data) {
                if($('#id_rekening').val() !== ''){
                    //reset_data_rekening();
                    $('#rekening').val('');
                    $('#modal_rekening').modal('hide');
                    message_edit_success();
                    get_list_rekening(1);
                }else{
                    $('#rekening').val('');
                    message_add_success();
                    get_list_rekening(1);
                }

                //reset_data_rekening()
                hide_ajax_indicator();
            },
            error : function(data){
                 if($('#id_rekening').val() !== ''){
                    message_edit_failed();
                }else{
                    message_add_failed();
                }

                hide_ajax_indicator();
            }
        });
        
    }

    function edit_rekening(id, p){
        dc_validation_remove('.form-control');
        $('#page_now_rekening').val(p);
        $('#modal_title_rekening').html('Edit rekening');
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/akuntansi/rekening") ?>/id/'+id,
            cache: false,
            dataType : 'json',
            success: function(data) {
                $('#id_rekening').val(data.data.id);
                $('#rekening_auto').val(data.data.id_parent);
                $('#kode').val(data.data.kode);
                $('#rekening').val(data.data.nama);
                $('.edit_hide').hide();
                $('#modal_rekening').modal('show');
            },
            error: function(e){
                access_failed(e.status);
            }
        });
    }

    function delete_rekening(id, p, branch){
        
        bootbox.dialog({
          message: "Anda yakin akan menghapus data ini?",
          title: "Hapus Data",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {
                
              }
            },
            hapus: {
              label: '<i class="fa fa-trash-o"></i>  Hapus',
              className: "btn-primary",
              callback: function() {
                $.ajax({
                    type : 'DELETE',
                    url: '<?= base_url("api/akuntansi/rekening") ?>/id/'+id,
                    cache: false,
                    dataType : 'json',
                    success: function(data) {
                        $("#example-advanced").treetable("removeNode",branch);
                        message_delete_success();
                    },
                    error: function(e){
                         message_delete_failed();
                    }
                });
              }
            }
          }
        });
    }

    function paging(p){
        get_list_rekening(p);
    }
</script>
<style type="text/css">
    #example-advanced tr td {
        border-left: none;
        border-right: none;
    }
</style>
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
                    <button class="btn btn-mini btn-primary" id="bt_tambah_rekening"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    <button class="btn btn-mini" id="bt_reset_rekening"><i class="fa fa-refresh"></i> Reload Data</button>

                </div> 
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover" id="example-advanced">
                        <thead>
                            <tr>
                                <th width="30%">No.</th>
                                <th width="60%" class="left">Nama</th>
                                <th align="center" width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="modal_rekening" class="modal fade">
            <div class="modal-dialog" style="width: 50%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal_title_rekening">Tambah Rekening</h4>
              </div>
              <div class="modal-body">
                <?= form_open('','id=formkec role=form class=form-horizontal') ?>
                <input name="id" type="hidden" id="id_rekening"/>

                <div class="form-group edit_hide">
                    <label for="parent" class="col-lg-3 control-label">Rekening Parent</label>
                    <div class="col-lg-8">
                        <input type="text" name="id_parent" id="rekening_auto" class="select2-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="kode" class="col-lg-3 control-label">Kode</label>
                    <div class="col-lg-8">
                      <input type="text" name="kode" class="form-control" id="kode" placeholder="Kode Rekening" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="kode" class="col-lg-3 control-label">Nama</label>
                    <div class="col-lg-8">
                      <input type="text" name="rekening" class="form-control" id="rekening" placeholder="Nama Rekening" />
                    </div>
                </div>

                <?= form_close() ?>
              </div>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
                <button type="button" class="btn btn-primary" onclick="save_rekening()"><i class="fa fa-save"></i> Simpan</button>

              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
</div>