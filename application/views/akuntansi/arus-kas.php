<script type="text/javascript">
    $(function() {
        get_list_arus_kas();
        $('#bt_show').click(function() {
            $('#datamodal_no').modal('show');
        });
        
        $("#tahun, #tahun_add").datepicker({
            format: "yyyy", // Notice the Extra space at the beginning
            viewMode: "years", 
            minViewMode: "years"
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        $('#bt_cetak').click(function() {
            print_arus_kas();
        });
        
        $('#button_setting').click(function() {
            $('#datamodal_setting').modal('show');
            get_setting_anggaran()
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
    
    function print_arus_kas() {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('printing_akuntansi/cetak_arus_kas') ?>?'+$('#formsearch').serialize(),'Cetak Arus Kas','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }
    
    function sejajar() {
        var tabel1 = $('#table_rekening tbody tr').length;
        var tabel2 = $('#table_rekening2 tbody tr').length;
        var selisih= Math.abs(tabel1-tabel2);
        if (tabel1 > tabel2) {
            for (i = 1; i <= selisih; i++) {
                var str = '<tr><td colspan=3>&nbsp;</td></tr>';
                $('#table_rekening2 tbody').append(str);
            }
        } else {
            for (i = 1; i <= selisih; i++) {
                var str = '<tr><td colspan=3>&nbsp;</td></tr>';
                $('#table_rekening tbody').append(str);
            }
        }
    }
    
    function edit_setting(id) {
        $.ajax({
            url: '<?= base_url('api/akuntansi/setting_anggaran') ?>',
            data: 'id='+id,
            success: function(msg) {
                var data = msg.data[0];
                $('#id').val(data.id);
                $('#tahun').val(data.tahun);
                $('#rekening_2_auto').val(data.id_rekening);
                $('#s2id_rekening_2_auto a .select2-chosen').html(data.kode+' '+data.nama);
                $('#anggaran').val(data.nominal);
            }
        });
    }   
    
    function get_list_arus_kas() {
        $('#datamodal_no').modal('hide');
        $.ajax({
            url: '<?= base_url('api/akuntansi/arus_kas') ?>',
            data: $('#formsearch').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                hide_ajax_indicator();
                create_left_side(msg);
            },
            complete: function() {
                sejajar();
            },
            error: function(e){
                hide_ajax_indicator();
                access_failed(e.status);
            }
        });
    }
    
    function create_left_side(msg) {
        var kas = msg.data;
        var str = '<table id="table_rekening" class="table" style="border-bottom: 1px solid #ccc; width: 100%;">'+
                    '<tbody>'+
                        '<tr style="background: #f4f4f4">'+
                            '<td><b>URAIAN</b></td>'+
                            '<td align="right"><b>'+msg.last_year+'</b></td>'+
                            '<td align="right"><b>'+msg.this_year+'</b></td>'+
                        '</tr>';
                    var sisa_thn_lalu = 0; var sisa_thn_ini = 0;
                    $.each(kas, function(i, v) {
                        str+='<tr>'+
                            
                            '<td width="43%">'+v.nama+'</td>'+
                            '<td width="10%" align="right"></td>'+
                            '<td width="10%"></td>'+
                        '</tr>';
                        var total_thn_lalu = 0; var total_thn_ini = 0;
                        $.each(v.child, function(i2, v2) {
                            str+='<tr>'+
                                '<td width="43%" style="padding-left: 20px;">'+v2.nama+'</td>'+
                                '<td width="10%" align="right">'+money_format(v2.last_year)+'</td>'+
                                '<td width="10%" align="right">'+money_format(v2.this_year)+'</td>'+
                            '</tr>';
                            total_thn_lalu += parseFloat(v2.last_year);
                            total_thn_ini  += parseFloat(v2.this_year);
                        });
                        str+='<tr>'+
                                '<td style="padding-left: 60px;"><b>Kas Bersih Dari '+v.nama+'</b></td>'+
                                '<td align="right">'+money_format(total_thn_lalu)+'</td>'+
                                '<td align="right">'+money_format(total_thn_ini)+'</td>'+
                             '</tr>';
                        sisa_thn_lalu += total_thn_lalu;
                        sisa_thn_ini  += total_thn_ini;
                    });
                    str+='<tr>'+
                            '<td style="padding-left: 60px;"><b>Saldo Kas</b></td>'+
                            '<td align="right">'+money_format(sisa_thn_lalu)+'</td>'+
                            '<td align="right">'+money_format(sisa_thn_ini)+'</td>'+
                         '</tr>';
                    str+='</tbody></table>';
          $('#load-arus_kas').html(str);
    }
    
    function confirm_save() {
        bootbox.dialog({
          message: "Anda yakin akan menyimpan transaksi ini?",
          title: "Konfirmasi",
          buttons: {
            batal: {
                label: '<i class="fa fa-refresh"></i> Tidak',
                className: "btn-default",
                callback: function() {

                }
            },
            save: {
                label: '<i class="fa fa-check-circle"></i>  Ya',
                className: "btn-primary",
                callback: function() {
                    save_setting();
                }
            }
            }
        });
    }
    
    function save_setting() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/akuntansi/setting_anggaran') ?>',
            data: $('#form_setting_anggaran').serialize(),
            success: function(data) {
                if (data.status === true) {
                    if (data.act === 'add') {
                        message_add_success();
                    } else {
                        message_edit_success();
                    }
                    reset_form();
                    get_setting_anggaran();
                }
            },
            error: function(e) {
                
            }
        });
    }
    
    function reload_data_form() {
        reset_form();
        get_setting_anggaran();
    }
    
    function reset_form() {
        $('input[type=text], input[type=hidden], select, textarea').val('');
        $('a .select2-chosen').html('');
        $('#tahun_add, #tahun').val('<?= date("Y") ?>');
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
                    <button class="btn btn-mini" id="bt_show"><i class="fa fa-search"></i> Pencarian</button>
                    <button class="btn btn-mini" id="bt_cetak"><i class="fa fa-refresh"></i> Cetak</button>

                </div> 
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <div id="load-arus_kas"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
<div id="datamodal_no" class="modal fade">
    <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="widget-header">
            <div class="title">
                <h4>Pencarian</h4>
            </div>
        </div>
      </div>
        
        <div class="modal-body">
            <?= form_open('','id=formsearch role=form class=form-horizontal') ?>
            <div class="row">
              <div class="col-md-12">
                  <div class="widget-body">
                      <div class="form-group">
                          <label for="barang" class="col-lg-2 control-label">Tahun:</label>
                          <div class="col-lg-10">
                              <input type="text" name="tahun" id="tahun" class="form-control" value="<?= date("Y") ?>" style="width: 145px;" />
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <?= form_close() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
            <button type="button" class="btn btn-primary" onclick="get_list_arus_kas();"><i class="fa fa-eye"></i> Tampilkan</button>
        </div>
    </div>
    </div>
</div>

<div id="datamodal_setting" class="modal fade">
    <div class="modal-dialog" style="width: 70%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="widget-header">
            <div class="title">
                <h4>Setting Anggaran Setelah Perubahan</h4>
            </div>
        </div>
      </div>
        
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <div class="widget-body">
                    <form id="form_setting_anggaran">
                    <input type="hidden" name="id" id="id" />
                    <table width="100%" class="table table-striped table-hover" cellspacing="0">
                        <tbody>
                            <tr valign="top">
                                <td width="25%">Tahun</td>
                                <td width="1%">:</td>
                                <td width="74%">
                                    <input type="text" name="tahun" id="tahun_add" class="custom-user" value="<?= date("Y") ?>" style="width: 145px;" />
                                </td>
                            </tr>
                            <tr valign="top">
                                <td width="25%">Kode Rekening</td>
                                <td width="1%">:</td>
                                <td width="74%">
                                    <input type="text" name="rekening" id="rekening_2_auto" class="select2-input">
                                </td>
                            </tr>
                            <tr valign="top">
                                <td width="25%">Anggaran</td>
                                <td width="1%">:</td>
                                <td width="74%">
                                    <input type="text" name="anggaran" id="anggaran" onblur="FormNum(this);" class="custom-user" style="width: 300px;" />
                                </td>
                            </tr>
                            <tr valign="top">
                                <td width="25%"></td>
                                <td width="1%"></td>
                                <td width="74%">
                                    <button class="btn btn-xs" onclick="confirm_save(); return false;"><i class="fa fa-save"></i> Simpan</button>
                                    <button class="btn btn-default btn-xs" onclick="reload_data_form(); return false;"><i class="fa fa-refresh"></i> Reset</button>
                                    <button class="btn btn-default btn-xs" onclick="get_setting_anggaran(); return false;"><i class="fa fa-search"></i> Cari Data</button>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td width="25%"></td>
                                <td width="1%"></td>
                                <td width="74%"></td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
                    <table class="table table-bordered table-stripped table-hover" id="setting_anggaran_list">
                        <thead>
                        <tr>
                            <th width="3%">NO</th>
                            <th width="7%" class="left">TAHUN</th>
                            <th width="7%" class="left">KODE</th>
                            <th width="50%" class="left">URAIAN</th>
                            <th width="15%" class="right">ANGGARAN</th>
                            <th width="10%"></th>
                        </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Close</button>
    </div>
    </div>
    </div>
</div>