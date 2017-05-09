<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_penarikan_tabungan(1);
        $('#add_penarikan_tabungan').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
        });
        
        $('#tanggal, #awal, #akhir').datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#cari_button').click(function() {
            $('#datamodal_search').modal('show');
        });

        $('#reload_penarikan_tabungan').click(function() {
            reset_form();
            get_list_penarikan_tabungan(1);
        });
        
        $('#norek').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/anggota_auto') ?>",
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
                var markup = data.no_rekening+' - '+data.nama+'<br/>'+data.alamat;
                return markup;
            }, 
            formatSelection: function(data){
                $('#sisa_saldo').val(money_format(data.saldo));
                get_saldo_penarikan_simpanan_bebas(data.id);
                return data.no_rekening+' - '+data.nama;
            }
        });
        
        $('#norek_cari').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/anggota_auto') ?>",
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
                var markup = data.no_rekening+' - '+data.nama+'<br/>'+data.alamat;
                return markup;
            }, 
            formatSelection: function(data){
                $('#sisa_saldo').val(money_format(data.saldo));
                return data.no_rekening+' - '+data.nama;
            }
        });
        
        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove($(this));
            }
        });
    });
    
    function get_saldo_penarikan_simpanan_bebas(id_anggota) {
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/saldo_simpanan_bebas') ?>/page/1/id/'+id_anggota,
            success: function(data) {
                $('#sisa_saldo').val(money_format(data.sisa));
            }
        });
    }
    
    function get_list_penarikan_tabungan(p, id) {
        $('#datamodal_search').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/penarikan_tabungans") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_penarikan_tabungan(p-1);
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
                            '<td>'+v.no_rekening+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right">'+money_format(v.awal)+'</td>'+
                            '<td align="right">'+money_format(v.keluar)+'</td>'+
                            '<td align="right">'+money_format(v.awal-v.keluar)+'</td>'+
                            '<td align="right" class=aksi>'+
                                '<button type="button" class="btn btn-default btn-mini" onclick="cetak_penarikan_tabungan(\''+v.id_dt+'\');"><i class="fa fa-print"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_penarikan_tabungan(\''+v.id_dt+'\')"><i class="fa fa-pencil"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="delete_penarikan_tabungan(\''+v.id_dt+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
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
    
    function cetak_penarikan_tabungan(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('printing/print_penarikan_tabungan') ?>?id='+id,'Cetak Transaksi Pajak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#awal').val('<?= date("01/m/Y") ?>');
        $('#akhir, #tanggal').val('<?= date("d/m/Y") ?>');
        $('.select2-chosen').html('');
    }

    function edit_penarikan_tabungan(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/penarikan_tabungans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(msg) {
                var data = msg.data[0];
                var keluar= data.keluar.split('.');
                $('#id').val(data.id_dt);
                $('#norek').val(data.id_tabungan);
                $('#s2id_norek a .select2-chosen').html(data.no_rekening+' - '+data.nama);
                var last_sisa = parseFloat(data.saldo)+parseFloat(data.keluar);
                $('#sisa_saldo').val(numberToCurrency(last_sisa));
                $('#nominal_tabungan').val(numberToCurrency(keluar[0]));
            }
        });
    }
        
    function paging(p) {
        get_list_penarikan_tabungan(p);
    }

    function konfirmasi_save() {
        //$('#isi_penarikan_tabungan').val(tinyMCE.get('isi').getContent());
        if ($('#nominal_tabungan').val() === '' || $('#nominal_tabungan').val() === '0') {
            dc_validation('#nominal_tabungan', 'Nominal tidak boleh kosong !'); return false;
        }
        
        if (parseFloat($('#nominal_tabungan').val()) > parseFloat($('#sisa_saldo').val())) {
            dc_validation('#nominal_tabungan','Penarikan tidak boleh lebih besar dari sisa saldo !'); return false;
        }
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
                    save_penarikan_tabungan();
                }
              }
            }
          });
      }

    function save_penarikan_tabungan() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/penarikan_tabungan') ?>',
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
                    get_list_penarikan_tabungan(1);
                } else {
                    $('#datamodal').modal('hide');
                    message_edit_success();
                    get_list_penarikan_tabungan(page);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_penarikan_tabungan(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_penarikan_tabungan(id, page) {
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
                        url: '<?= base_url('api/transaksi/penarikan_tabungan') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_penarikan_tabungan(page);
                        }
                    });
                }
              }
            }
        });
    }

    function paging(page, tab, search) {
        get_list_penarikan_tabungan(page, search);
    }
    
    function hitungPajak() {
        var jumlah = currencyToNumber($('#nominal').val());
        var pajak  = $('#jenis_pajak').val();
        if (pajak === 'PPN') {
            hasil = 0.1*parseFloat(jumlah);
        }
        if (pajak === 'PPh21') {
            hasil = '0';
        }
        if (pajak === 'PPh22') {
            hasil = (jumlah- (jumlah*0.1))*(1.5/100);
        }
        if (pajak === 'PPh23') {
            hasil = 0.02*jumlah;
        }
        $('#perhitungan').val(money_format(hasil));
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
                    <button id="add_penarikan_tabungan" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="reload_penarikan_tabungan" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload Data</button>
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
                            <th width="10%" class="left">No. Rek</th>
                            <th width="15%" class="left">Nama</th>
                            <th width="10%" class="right">Awal</th>
                            <th width="10%" class="right">Keluar</th>
                            <th width="10%" class="right">Saldo</th>
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
                <form id="formadd" method="post" role="form" class="form-horizontal">
                <input type="hidden" name="id" id="id" />
                <div class="form-group">
                    <label class="control-label col-md-3">Tanggal:</label>
                    <div class="col-lg-8">
                        <input type="text" name="tanggal" class="form-control" disabled="" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">No. Rekening/Nama:</label>
                    <div class="col-lg-8">
                        <input type="text" name="norek"  class="select2-input" id="norek">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Sisa Saldo:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="sisa_saldo" readonly="">
                    </div>
                </div>
<!--                <div class="form-group">
                    <label class="control-label">Jenis Transaksi:</label>
                    <select name="jenis" id="jenis" class="form-control">
                        <option value="">Pilih ...</option>
                        <option value="Setoran">Setoran</option>
                        <option value="Penarikan">Penarikan</option>
                    </select>
                </div>-->
                <div class="form-group">
                    <label class="control-label col-lg-3">Nominal:</label>
                    <div class="col-lg-8">
                        <input name="nominal_tabungan" id="nominal_tabungan" onblur="FormNum(this)" class="form-control" />
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
                <form id="form_search" method="post" role="form">
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="control-label">Tanggal:</label>
                        <span><input type="text" name="awal" id="awal" class="form-control" value="<?= date("01/m/Y") ?>" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                        <span><input type="text" name="akhir" id="akhir" class="form-control" value="<?= date("d/m/Y") ?>" style="width: 145px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">No. Rekening:</label>
                        <input type="text" name="id_anggota"  class="select2-input" id="norek_cari">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_penarikan_tabungan(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>