<script type="text/javascript">
    $(function() {
        tampilkan(1);

        $('#bt_cari').click(function() {
            $('#datamodal_no').modal('show');
            $('#datamodal_no .title h4').html('Tambah Jurnal Umum');
            $('#rows_debet, #rows_kredit').html('');
            $('#keterangan, #tanggal, #kode_transaksi, #hidden_kode_transaksi').val('');
            tambah_rek_debet();
            tambah_rek_kredit();
        });
        $('#bt_cari_jurnal').click(function() {
            $('#datamodal_jurnal').modal('show');
            $('#modal_title').html('Cari Jurnal');
        });
        $('#nilai').blur(function() {
            var nilai = $(this).val();
            $('#nilai2').val(numberToCurrency(nilai));
        });
        $('#reset').click(function() {
            $('#awal, #akhir').val('');
            $('#s2id_rekening_2_auto a .select2-chosen').html('');
            $('#rekening_2_auto').val('');
            tampilkan(1);
        });
        $('.form-control, .form-control, .select2-input').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove($(this));
            }
        });
        $('#awal, #akhir, #tanggal').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#rekening_2_auto').select2({
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

    function paging(p) {
        tampilkan(p);
    }

    function tampilkan(p, id_bukubesar) {
        $('#datamodal_no, #datamodal_jurnal').modal('hide');

        var id = '';
        if (id_bukubesar !== undefined) {
            id = id_bukubesar;
        }
        show_ajax_indicator();
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/akuntansi/jurnals') ?>/page/'+p+'/id/'+id,
            data: $('#form_jurnal').serialize()+'&jenis=Umum',
            dataType: 'json',
            success: function(data) {
                hide_ajax_indicator();
                if ((p > 1) & (data.data.length === 0)) {
                    tampilkan(p-1);
                    return false;
                };
                $('#pagination').html(pagination(data.jumlah, data.limit, data.page, 1));
                $('#page_summary').html(page_summary(data.jumlah, data.data.length, data.limit, data.page));
                $('#table_data_no tbody').html('');
                $.each(data.data, function(i, v) {
                    var str = '<tr>'+
                            '<td align="center">'+((i+1) + ((p-1)*(data.limit)))+'</td> '+
                            '<td>'+datefmysql(v.tanggal)+'</td>'+ 
    //                        '<td>'+v.transaksi+'</td>'+
                            '<td>';
                                $.each(v.detail, function(j, val) {
                                    var paddingleft = '';
                                    if (val.kredit !== '0') {
                                        paddingleft = 'style="padding-left: 30px;"';
                                    }
                                    str+='<div '+paddingleft+'>'+val.rekening+'</div>';
                                });
                                str+='<div style="padding-left: 30px; color: #999;"><i>'+v.ket_transaksi+'</i></div>';
                            str+='</td>'+
                            '<td>';
                                $.each(v.detail, function(j, val) {
                                    str+='<div>'+val.kode+'</div>';
                                });
                            str+='</td>'+
                            '<td align="right">';
                                $.each(v.detail, function(j, val) {
                                    str+='<div>'+((val.debet !== '0')?numberToCurrency(val.debet):'&nbsp;')+'</div>';
                                });
                            str+='</td>'+ 
                            '<td align="right">';
                                $.each(v.detail, function(j, val) {
                                    str+='<div>'+((val.kredit !== '0')?numberToCurrency(val.kredit):'&nbsp;')+'</div>';
                                });
                            str+='</td>'+
                            '<td align="right">'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_jurnal(\''+v.no_transaksi+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_jurnal(\''+v.no_transaksi+'\','+p+')"><i class="fa fa-trash-o"></i></button>'+
                            '</td>'+
                        '</tr>';
                    $('#table_data_no tbody').append(str);
                });
            },
            error: function(e){
                hide_ajax_indicator();
                access_failed(e.status);
            }
        });    
    }

    function edit_jurnal(no_transaksi) {
        $('#datamodal_no').modal('show');
        $('#datamodal_no .title h4').html('Edit Jurnal Umum');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/akuntansi/jurnal') ?>',
            data: 'no_transaksi='+no_transaksi,
            dataType: 'json',
            success: function(msg) {
                $('#rows_debet, #rows_kredit, #total_debet, #total_kredit').html('');
                var data = msg.data[0];
                $('#tanggal').datepicker('update',datefmysql(data.tanggal));
                $('#kode_transaksi, #hidden_kode_transaksi').val(data.no_transaksi);
                $('#keterangan').val(data.ket_transaksi);
                var noD = 0;
                var noK = 0;
                $.each(data.detail, function(i, v) {
                    if (v.debet !== '0') {
                        tambah_rek_debet();
                        $('#rekening_auto'+noD).val(v.id_rekening);
                        $('#s2id_rekening_auto'+noD+' a .select2-chosen').html(v.kode+' '+v.rekening);
                        $('#nilai'+noD).val(money_format(v.debet));
                        noD++;
                    }
                    if (v.kredit !== '0') {
                        tambah_rek_kredit();
                        $('#rekening_auto2'+noK).val(v.id_rekening);
                        $('#s2id_rekening_auto2'+noK+' a .select2-chosen').html(v.kode+' '+v.rekening);
                        $('#nilai2'+noK).val(money_format(v.kredit));
                        noK++;
                    }
                });
            },
            complete: function() {
                total_debet();
                total_kredit();
            }
        });
    }

    function delete_jurnal(id, p){        
        bootbox.dialog({
          message: "Anda yakin akan menghapus data ini?",
          title: "Konfirmasi Hapus Data",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {

              }
            },
            hapus: {
              label: '<i class="fa fa-check-circle"></i>  Ya',
              className: "btn-primary",
              callback: function() {
                $.ajax({
                    type : 'GET',
                    url: '<?= base_url("api/akuntansi/jurnal_del") ?>',
                    data: 'no_transaksi='+id,
                    success: function(data) {
                        tampilkan(p);
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

    function removeRekDebet(el) {
        var parent = el.parentNode.parentNode;
        parent.parentNode.removeChild(parent);
        var jumlah = $('.rows_debet').length;
        var col = 0;
        for (i = 0; i <= jumlah-1; i++) {
            $('.rows_debet:eq('+col+')').children('div').children('.rekening').attr('id', 'rekening'+i);
            $('.rows_debet:eq('+col+')').children('div').children('div').children('.nilai').attr('id', 'nilai'+i);
            col++;
        }
        total_debet();
    }

    function total_debet() {
        var jml = $('.rows_debet').length;
        var nilai = 0;
        for (i = 0; i <= jml-1; i++) {
            nilai = nilai + parseFloat(currencyToNumber($('#nilai'+i).val()));
        }
        $('#total_debet').html(numberToCurrency(nilai));
    }

    function tambah_rek_debet() {
        var jml = $('.rows_debet').length;
        var str = '<div class="rows_debet" style="margin-bottom: 5px;">'+
                '<div class="col-lg-8"><input type="text" name="rekening[]" id="rekening_auto'+jml+'" class="select2-input rekening" style="float: left; margin-right: 3px;"></div>'+
                '<div class="col-lg-3"><div class="input-group" style="float: left; margin-right: 3px;">'+
                    '<span class="input-group-addon">Rp</span>'+
                    '<input type="text" name="nilai[]" id="nilai'+jml+'" class="form-control nilai" style="width: 100px;" />'+
                '</div>'+
                '</div>'+
                '<div class="col-lg-1"><button type="button" class="btn btn-xs" onclick="removeRekDebet(this);"><i class="fa fa-trash-o"></i></button></div></div>';
        $('#rows_debet').append(str);
        $('#nilai'+jml).blur(function() {
            FormNum(this);
            total_debet();
        });
        $('#rekening_auto'+jml).select2({
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
    }

    function removeRekKredit(el) {
        var parent = el.parentNode.parentNode;
        parent.parentNode.removeChild(parent);
        var jumlah = $('.rows_kredit').length;
        var col = 0;
        for (i = 0; i <= jumlah-1; i++) {
            $('.rows_kredit:eq('+col+')').children('div').children('.rekening_kredit').attr('id', 'rekening_kredit2'+i);
            $('.rows_kredit:eq('+col+')').children('div').children('div').children('.nilai2').attr('id', 'nilai2'+i);
            col++;
        }
        total_kredit();
    }

    function total_kredit() {
        var jml = $('.rows_kredit').length;
        var nilai = 0;
        for (i = 0; i <= jml-1; i++) {
            nilai = nilai + parseFloat(currencyToNumber($('#nilai2'+i).val()));
        }
        $('#total_kredit').html(numberToCurrency(nilai));
    }

    function tambah_rek_kredit() {
        var jml = $('.rows_kredit').length;
        var str = '<div class="rows_kredit" style="margin-bottom: 5px;">'+
                '<div class="col-lg-8"><input type="text" name="rekening_kredit[]" id="rekening_auto2'+jml+'" class="select2-input rekening_kredit" style="float: left; margin-right: 3px;" /></div>'+
                '<div class="col-lg-3"><div class="input-group" style="float: left; margin-right: 3px;">'+
                '<span class="input-group-addon">Rp</span>'+
                    '<input type="text" name="nilai_kredit[]" id="nilai2'+jml+'" class="form-control nilai2" style="width: 100px;" />'+
                '</div>'+
                '</div>'+
                '<div class="col-lg-1"><button type="button" class="btn btn-xs" onclick="removeRekKredit(this);"><i class="fa fa-trash-o"></i></button></div></div>';
        $('#rows_kredit').append(str);
        $('#nilai2'+jml).blur(function() {
            FormNum(this);
            total_kredit();
        });
        $('#rekening_auto2'+jml).select2({
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
    }

    function konfirmasi_simpan() {
        var stop = false;
        if ($('#tanggal').val() === '') {
            dc_validation('#tanggal','Tanggal transaksi tidak boleh kosong !'); stop = true;
        }
        if ($('#kode_transaksi').val() === '') {
            dc_validation('#kode_transaksi','Kode transaksi tidak boleh kosong !'); stop = true;
        }

        var debet= $('.rows_debet').length;
        var kredt= $('.rows_kredit').length;
        for (i = 0; i < debet; i++) {
            if ($('#rekening_auto'+i).val() === '') {
                dc_validation('#rekening_auto'+i, 'Rekening tidak boleh kosong !'); stop = true;
            }
            if ($('#nilai'+i).val() === '') {
                dc_validation('#nilai'+i, 'Tidak boleh kosong!'); stop = true;
            }
        }

        for (i = 0; i< kredt; i++) {
            if ($('#rekening_auto2'+i).val() === '') {
                dc_validation('#rekening_auto2'+i, 'Rekening tidak boleh kosong !'); stop = true;
            }
            if ($('#nilai2'+i).val() === '') {
                dc_validation('#nilai2'+i, 'Tidak boleh kosong!'); stop = true;
            }
        }

        if ($('#total_debet').html() !== $('#total_kredit').html()) {
            dinamic_alert('<h3>Total debet dan kredit belum sama</h3>'); stop = true;
        }

        if ($('#keterangan').val() === '') {
            dc_validation('#keterangan','Keterangan tidak boleh kosong !'); stop = true;
        }

        if (stop) {
            return false;
        }
        for (i = 0; i < debet; i++) {
            dc_validation_remove('#rekening_auto'+i);
            dc_validation_remove('#nilai'+i);
        }
        for (i = 0; i< kredt; i++) {
            dc_validation_remove('#rekening_auto2'+i);
            dc_validation_remove('#nilai2'+i);
        }

        bootbox.dialog({
          message: "Anda yakin akan menyimpan data ini?",
          title: "Konfirmasi Simpan",
          buttons: {
            batal: {
              label: '<i class="fa fa-refresh"></i> Batal',
              className: "btn-default",
              callback: function() {

              }
            },
            ya: {
              label: '<i class="fa fa-check-circle"></i>  Ya',
              className: "btn-primary",
              callback: function() {
                save_jurnal();
              }
            }
          }
        });
    }

    function save_jurnal() {

        show_ajax_indicator();
        $.ajax({
            type : 'POST',
            url: '<?= base_url("api/akuntansi/jurnal") ?>',
            data: $('#form').serialize()+'&jenis=Umum',
            cache: false,
            dataType : 'json',
            success: function(data) {
                $('#modal_rekening').modal('hide');
                message_add_success();
                tampilkan(1);
                hide_ajax_indicator();
            },
            error : function(data){
                hide_ajax_indicator();
                 if($('#id_rekening').val() !== ''){
                    message_edit_failed();
                }else{
                    message_add_failed();
                }
            }
        });
    }
    
</script>
<style>
    #form_jurnal {
        border: none;
    }
    #form_jurnal > tbody > tr > td {
        border: none;
    }
    
    #form_jurnal > tbody > tr > td:first-child {
        text-align: right;
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
                    <button class="btn btn-mini btn-primary" id="bt_cari"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    <button class="btn btn-mini" id="bt_cari_jurnal"><i class="fa fa-search"></i> Cari</button>
                    <button class="btn btn-mini" id="reset"><i class="fa fa-refresh"></i> Reload Data</button>

                </div> 
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover" id="table_data_no">
                        <thead>
                            <tr>
                                <th width="3%">No.</th>
                                <th width="5%" class="left">Tanggal</th>
                                <th width="40%" class="left">Jurnal</th>
                                <th width="10%" class="left">Ref</th>
                                <th width="10%" class="right">Debet</th>
                                <th width="10%" class="right">Kredit</th>
                                <th width="5%" class="right"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>
                    <div class="page_summary" id="page_summary"></div>
                    <div id="pagination"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

<div id="datamodal_no" class="modal fade">
    <div class="modal-dialog" style="width: 780px; height: 100%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="widget-header">
                <div class="title">
                    <h4>Tambah Jurnal</h4>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <?= form_open('','id=form role=form class=form-horizontal') ?>
            <input type="hidden" name="hidden_kode_transaksi" id="hidden_kode_transaksi" />
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-body">
                        <table width="100%" class="table" id="form_jurnal" cellspacing="0">
                            <tbody>
                                <tr valign="top">
                                    <td width="25%">Tanggal</td>
                                    <td width="1%">:</td>
                                    <td width="74%">
                                        <div class="col-lg-5"><input type="text" name="tanggal" id="tanggal" class="form-control" /></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">No. Transaksi</td>
                                    <td width="1%">:</td>
                                    <td width="74%">
                                        <div class="col-lg-12"><input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" /></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%"></td>
                                    <td width="1%"></td>
                                    <td width="74%">
                                        <div class="col-lg-12">
                                            <button type="button" class="btn btn-default btn-mini" onclick="tambah_rek_debet();"><i class="fa fa-plus-circle"></i> Rek. Debet</button>
                                            <button type="button" class="btn btn-default btn-mini" onclick="tambah_rek_kredit();"><i class="fa fa-plus-circle"></i> Rek. Kredit</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">Rekening Debet</td>
                                    <td width="1%">:</td>
                                    <td width="74%" id="rows_debet">
                                        
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">Rekening Kredit</td>
                                    <td width="1%">:</td>
                                    <td width="74%" id="rows_kredit">
                                        
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">Total Debet</td>
                                    <td width="1%">:</td>
                                    <td width="74%">
                                        <div class="col-lg-12" id="total_debet"></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">Total Kredit</td>
                                    <td width="1%">:</td>
                                    <td width="74%">
                                        <div class="col-lg-12" id="total_kredit"></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td width="25%">Catatan <i>Memorial</i></td>
                                    <td width="1%">:</td>
                                    <td width="74%">
                                        <div class="col-lg-12"><textarea name="keterangan" id="keterangan" rows="5" class="form-control"></textarea></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Close</button>
            <button type="button" class="btn btn-primary" onclick="konfirmasi_simpan();"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
    </div>
</div>
<div id="datamodal_jurnal" class="modal fade">
    <div class="modal-dialog" style="width: 510px; height: 100%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="widget-header">
                <div class="title">
                    <h4> Parameter Pencarian</h4>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <?= form_open('','id=form_jurnal role=form class=form-horizontal') ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-body">
                        <div class="form-group">
                            <label for="barang" class="col-lg-3 control-label">Tanggal:</label>
                            <div class="col-lg-9">
                                <input type="text" name="awal" class="form-control" id="awal" value="" style="width: 145px; float: left; margin-right: 10px;">
                                <input type="text" name="akhir" class="form-control" id="akhir" value="" style="width: 145px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Rekening</label>
                            <div class="col-lg-9">
                                <input type="text" name="rekening" id="rekening_2_auto" class="select2-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Close</button>
            <button type="button" class="btn btn-primary" onclick="tampilkan(1);"><i class="fa fa-search"></i> Tampilkan</button>
        </div>
    </div>
    </div>
</div>
