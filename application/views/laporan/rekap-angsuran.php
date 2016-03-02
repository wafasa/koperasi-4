<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_angsuran(1);
        $('#cari_button').click(function() {
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Pencarian');
        });
        
        $('#awal, #akhir').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_angsuran').click(function() {
            reset_form();
            get_list_angsuran(1);
        });
        
        $('#jml_kali_angsur').change(function() {
            var kali = parseInt($(this).val());
            var tagihan = parseFloat($('#tagihan_perbulan').val());
            $('#nominal_angsuran').val(money_format(kali*tagihan));
        });
        
        $('#export-excel').click(function() {
            location.href='<?= base_url('printing/excel_rekap_angsuran') ?>/?'+$('#forma_search').serialize();
        });
    });
    
    function get_list_angsuran(p, id) {
        $('#datamodal').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/angsurans") ?>/page/'+p+'/id/'+id,
            data: $('#forma_search').serialize(),
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_angsuran(p-1);
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
                            '<td align="center">'+datefmysql(v.tgl_bayar)+'</td>'+
                            '<td align="center">'+v.nomor_rekening+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right">'+money_format(v.bsr_angsuran)+'</td>'+
                            '<td align="right">'+money_format(v.angsuran_pokok)+'</td>'+
                            '<td align="right">'+money_format(v.jasa_angsuran)+'</td>'+
                            '<td align="right">'+money_format(v.sisa_angsuran)+'</td>'+
                        '</tr>';
                    $('#example-advanced tbody').append(str);
                });
            },
            complete: function() {
                hide_ajax_indicator();
                //$("#example-advanced").treetable({ expandable: true });
            },
            error: function(e){
                dinamic_message('Warning','Failed to load data !');
                hide_ajax_indicator();
            }
        });
    }
    
    function print_angsuran(id) {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('printing/print_angsuran/') ?>?id='+id,'Cetak Transaksi Pajak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#awal').val('<?= date("01/m/Y") ?>');
        $('#akhir').val('<?= date("d/m/Y") ?>');
        $('#s2id_norek a .select2-chosen').html('&nbsp;');
    }

    function edit_angsuran(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/angsurans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data[0].id);
                $('#tanggal').val(datefmysql(data.data[0].tanggal));
                $('#nokode').val(data.data[0].kode_akun_angsuran);
                $('#nobukti').val(data.data[0].no_bukti);
                $('#nominal').val(numberToCurrency(data.data[0].nominal));
                $('#perhitungan').val(money_format(data.data[0].hasil_angsuran));
                $('#jenis_transaksi').val(data.data[0].jenis_transaksi);
                $('#jenis_angsuran').val(data.data[0].jenis_angsuran);
                $('#uraian').val(data.data[0].uraian);
            }
        });
    }
        
    function paging(p) {
        get_list_angsuran(p);
    }

    function konfirmasi_save() {
        //$('#isi_angsuran').val(tinyMCE.get('isi').getContent());
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
                    save_angsuran();
                }
              }
            }
          });
      }

    function save_angsuran() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/angsuran') ?>',
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
                    get_list_angsuran(1);
                } else {
                    $('#datamodal').modal('hide');
                    message_edit_success();
                    get_list_angsuran(page);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_angsuran(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_angsuran(id, page) {
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
                        url: '<?= base_url('api/transaksi/angsuran') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_angsuran(page);
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
                    <button id="cari_button" class="btn btn-info btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="export-excel" class="btn btn-mini"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                    <button id="reload_angsuran" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                          <th width="3%">No</th>
                          <th width="7%">Tanggal</th>
                          <th width="7%">No. Rek.</th>
                          <th width="35%" class="left">Nama</th>
                          <th width="10%" class="right">Angsuran</th>
                          <th width="10%" class="left">Angs.&nbsp;Pokok</th>
                          <th width="8%" class="left">Bunga</th>
                          <!--<th width="10%" class="left">Jenis</th>-->
                          <th width="10%" class="left">Sisa&nbsp;Pinj.</th>
                          
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
        <div id="datamodal" class="modal fade" style="overflow-y: auto">
            <div class="modal-dialog" style="width: 700px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="forma_search" method="post" role="form">
                <!--<input type="hidden" name="id" id="id" />-->
                <div class="form-group tight">
                    <label class="control-label">Tanggal Angsuran:</label>
                    <span><input type="text" name="awal" class="form-control" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("01/m/Y") ?>" /></span>
                    <span><input type="text" name="akhir" class="form-control" style="width: 145px;" id="akhir" value="<?= date("d/m/Y") ?>" /></span>
                </div>
                <div class="form-group tight">
                    <label class="control-label">Nomor Rekening:</label>
                    <input type="text" name="norek"  class="form-control" id="norek">
                </div>
                <div class="form-group tight">
                    <label class="control-label">Nama Anggota:</label>
                    <input type="text" name="nama"  class="form-control" id="nama">
                </div>
            </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_angsuran(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>