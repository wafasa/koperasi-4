<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_pendapatan_administrasi(1);
        $('#bt-search').click(function() {
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Pencarian');
            $('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
        });
        
        $('#awal, #akhir').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_pendapatan_administrasi').click(function() {
            reset_form();
            get_list_pendapatan_administrasi(1);
        });
        
        $('#export-excel').click(function() {
            location.href='<?= base_url('printing/excel_rekap_pendapatan_administrasi') ?>/?'+$('#form_search').serialize();
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/pendapatan_administrasi_auto') ?>",
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
    
    function get_list_pendapatan_administrasi(p, id) {
        $('#datamodal').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/laporan/pendapatan_administrasis") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_pendapatan_administrasi(p-1);
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
                            '<td>'+((i+1) + ((data.page - 1) * data.limit))+'</td>'+
                            '<td>'+datefmysql(v.tgl_input)+'</td>'+
                            '<td>'+v.id_pinjaman+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right">'+money_format(v.biaya_adm)+'</td>'+
//                            '<td align="right">'+money_format(v.biaya_ca)+'</td>'+
                            '<td align="right">'+v.jenis+'</td>'+
                            
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
        $('#awal').val('<?= date("01/m/Y") ?>');
        $('#akhir').val('<?= date("d/m/Y") ?>');
    }

    function detail_pendapatan_administrasi(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#wizard').bwizard('show','0');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/pendapatan_administrasis') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                //$('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
                var data = data.data[0];
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#noktp').val(data.no_ktp);
                $('#alamat').val(data.alamat);
                $('#telp').val(data.no_telp);
                $('#pekerjaan').val(data.pekerjaan);
                
                $('#agama').val(data.agama);
                $('#nama_psg').val(data.nama_psg);
                $('#pekerjaan_psg').val(data.pekerjaan_psg);
                $('#status_rumah').val(data.status_rumah);
                $('#penghasilan').val(numberToCurrency(data.penghasilan_bln));
                $('#pengeluaran').val(numberToCurrency(data.pengeluaran_bln));
                $('#jaminan').val(data.jaminan);
                $('#rencana_pendapatan_administrasi').val(data.rencana_pendapatan_administrasi);
                $('#infodari').val(data.info_dari);
                
                $('#tanggal_disetujui').val(datefmysql(data.tgl_pinjam));
                $('#jumlah').val(money_format(data.jml_pinjaman));
                $('#lama').val(data.lama_pinjaman);
                
                //if (parseFloat(data.sisa_angsuran) !== parseFloat(data.ttl_pengembalian)) {
                    $('#tanggal_disetujui, #jumlah, #lama').attr('disabled','disabled');
                //}
            }
        });
    }
        
    function paging(p) {
        get_list_pendapatan_administrasi(p);
    }

    function konfirmasi_save() {
        //$('#isi_pendapatan_administrasi').val(tinyMCE.get('isi').getContent());
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
                    save_pendapatan_administrasi();
                }
              }
            }
          });
      }

    function save_pendapatan_administrasi() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/pendapatan_administrasi') ?>',
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
                if (msg.status === true) {
                    $('#datamodal').modal('hide');
                    message_add_success();
                    get_list_pendapatan_administrasi(1);
                }
            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_pendapatan_administrasi(page);
                hide_ajax_indicator();
            }
        });
    }

    function delete_pendapatan_administrasi(id, page) {
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
                        url: '<?= base_url('api/transaksi/pendapatan_administrasi') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_pendapatan_administrasi(page);
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
                    <button id="bt-search" class="btn btn-info btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="export-excel" class="btn btn-mini"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                    <button id="reload_pendapatan_administrasi" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
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
                          <th width="7%">No. Pinjam</th>
                          <th width="25%" class="left">Nama</th>
                          <th width="10%" class="right">Nominal</th>
                          <!--<th width="10%" class="right">Keanggotaan</th>-->
                          <th width="10%" class="right">Jenis</th>
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
            <div class="modal-dialog" style="width: 500px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_search" method="post" role="form">
                    
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group tight">
                        <label class="control-label">Tanggal Daftar:</label>
                        <span><input type="text" name="awal" class="form-control" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("01/m/Y") ?>" /></span>
                        <span><input type="text" name="akhir" class="form-control" style="width: 145px;" id="akhir" value="<?= date("d/m/Y") ?>" /></span>
                    </div>
                    <div class="form-group tight">
                        <label class="control-label">No. Rekening:</label>
                        <input type="text" name="norek"  class="form-control">
                    </div>
                    <div class="form-group tight">
                        <label class="control-label">Nama:</label>
                        <input type="text" name="nama"  class="form-control">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_pendapatan_administrasi(1);"><i class="fa fa-search"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>