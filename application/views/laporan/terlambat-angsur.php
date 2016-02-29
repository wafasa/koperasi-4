<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_terlambat_angsuran(1);
        $('#add_terlambat_angsuran').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
        });
        
        $('#tanggal').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reload_terlambat_angsuran').click(function() {
            reset_form();
            get_list_terlambat_angsuran(1);
        });
        
        $('#norek').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/norek_pinjaman_auto') ?>",
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
                var markup = data.nomor_rekening+' - '+data.nama+'<br/>'+data.alamat;
                return markup;
            }, 
            formatSelection: function(data){
                $('#total_pinjam').val(money_format(data.jml_pinjaman));
                $('#total_pengembalian').val(money_format(data.ttl_pengembalian));
                $('#sisa_terlambat_angsuran').val(money_format(data.sisa_terlambat_angsuran));
                $('#tagihan_perbulan').val(data.bsr_terlambat_angsuran);
                $('#jml_kali_angsur').html('<option value="">Pilih ...</option>');
                var j = 1;
                $.each(data.sisa_kali_terlambat_angsuran, function(i, v) {
                    $('#jml_kali_angsur').append('<option value="'+(j)+'">'+(++i)+'</option>');
                    j++;
                });
                return data.nomor_rekening+' - '+data.nama;
            }
        });
        
        $('#jml_kali_angsur').change(function() {
            var kali = parseInt($(this).val());
            var tagihan = parseFloat($('#tagihan_perbulan').val());
            $('#nominal_terlambat_angsuran').val(money_format(kali*tagihan));
        });
    });
    
    function get_list_terlambat_angsuran(p, id) {
        $('#form-pencarian').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/laporan/terlambat_angsurans") ?>/page/'+p+'/id/'+id,
            data: '',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_terlambat_angsuran(p-1);
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
                            '<td align="center">'+datefmysql(v.jatuh_tempo)+'</td>'+
                            '<td align="center">'+v.nomor_rekening+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td>'+v.alamat+'</td>'+
                            '<td align="right">'+money_format(v.jml_angsuran)+'</td>'+
                            '<td align="center">'+v.angsuran_ke+'</td>'+
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
    
    function print_terlambat_angsuran() {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('printing/print_terlambat_angsuran') ?>','Cetak Transaksi Pajak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#tanggal').val('<?= date("d/m/Y") ?>');
        $('#s2id_norek a .select2-chosen').html('&nbsp;');
    }

    function edit_terlambat_angsuran(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/terlambat_angsurans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                $('#id').val(data.data[0].id);
                $('#tanggal').val(datefmysql(data.data[0].tanggal));
                $('#nokode').val(data.data[0].kode_akun_terlambat_angsuran);
                $('#nobukti').val(data.data[0].no_bukti);
                $('#nominal').val(numberToCurrency(data.data[0].nominal));
                $('#perhitungan').val(money_format(data.data[0].hasil_terlambat_angsuran));
                $('#jenis_transaksi').val(data.data[0].jenis_transaksi);
                $('#jenis_terlambat_angsuran').val(data.data[0].jenis_terlambat_angsuran);
                $('#uraian').val(data.data[0].uraian);
            }
        });
    }
        
    function paging(p) {
        get_list_terlambat_angsuran(p);
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
                    <button onclick="print_terlambat_angsuran();" class="btn btn-info btn-mini"><i class="fa fa-print"></i> Cetak</button>
                    <!--<button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>-->
                    <button id="reload_terlambat_angsuran" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload</button>
                </div>
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table table-bordered table-stripped table-hover tabel-advance" id="example-advanced">
                        <thead>
                        <tr>
                          <th width="3%">No</th>
                          <th width="7%">Tempo</th>
                          <th width="7%">No. Rek.</th>
                          <th width="25%" class="left">Nama</th>
                          <th width="35%" class="left">Alamat</th>
                          <th width="10%" class="right">Angsuran</th>
                          <th width="10%" class="right">Angs. Ke</th>
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
                <form id="formadd" method="post" role="form">
                <!--<input type="hidden" name="id" id="id" />-->
                <input type="hidden" id="tagihan_perbulan" />
                <div class="form-group">
                    <label class="control-label">Tanggal:</label>
                    <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" readonly="" />
                </div>
                <div class="form-group">
                    <label class="control-label">Nomor Rekening / Nama Debitur:</label>
                    <input type="text" name="norek"  class="select2-input" id="norek">
                </div>
                <div class="form-group">
                    <label class="control-label">Total Pinjaman:</label>
                    <input type="text" class="form-control" id="total_pinjam" readonly="">
                </div>
                <div class="form-group">
                    <label class="control-label">Total Pengembalian:</label>
                    <input type="text" class="form-control" id="total_pengembalian" readonly="">
                </div>
                <div class="form-group">
                    <label class="control-label">Sisa Angsuran:</label>
                    <input type="text" class="form-control" id="sisa_terlambat_angsuran" readonly="">
                </div>
                <div class="form-group">
                    <label class="control-label">Jumlah Kali Angsuran:</label>
                    <select name="jml_kali_angsur" id="jml_kali_angsur" class="form-control">
                        
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Nominal Angsuran:</label>
                    <input name="nominal_terlambat_angsuran" id="nominal_terlambat_angsuran" class="form-control" />
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