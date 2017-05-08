<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        
        get_list_tabungan(1);
        $('#add_tabungan').click(function() {
            reset_form();
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
        });
        
        $('#tanggal, #awal, #akhir').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#tanggal').blur(function() {
            var nilai_this = $('#tanggal').val();
            var nilai_hide = $('#tanggal_hide').val();
            if (nilai_this === '') {
                $('#tanggal').val(nilai_hide);
            }
        }); 
        
        $('#cari_button').click(function() {
            $('#datamodal_search').modal('show');
        });

        $('#reload_tabungan').click(function() {
            reset_form();
            get_list_tabungan(1);
        });
        
        $('#parent_code').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/tabungan_auto') ?>",
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
        
        $('#norek_cari').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/norek_tabungan_auto') ?>",
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
    });
    
    function get_list_tabungan(p, id) {
        $('#datamodal_search').modal('hide');
        var id = '';
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/laporan/simpanan_pokoks") ?>/page/'+p+'/id/'+id,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                //show_ajax_indicator();
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_tabungan(p-1);
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
                            //'<td>'+v.no_ktp+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right">'+money_format(v.awal)+'</td>'+
                            '<td align="right">'+money_format(v.masuk)+'</td>'+
                            '<td align="right">'+money_format(v.keluar)+'</td>'+
                            '<td align="right">'+money_format(v.saldo)+'</td>'+
                            '<td>'+v.username+'</td>'+
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
        window.open('<?= base_url('laporan/print_pajak/') ?>?id='+id,'Cetak Transaksi Pajak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    }

    function reset_form() {
        $('input, select, textarea').val('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
        $('#tanggal').val('<?= date("d/m/Y") ?>');
    }

    function edit_anggota(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/laporan/tabungans') ?>/page/1/id/',
            data: 'id_anggota='+id,
            dataType: 'json',
            success: function(data) {
                var data = data.data[0];
                $('#id').val(data.id);
                $('#tanggal, #tanggal_hide').val(datefmysql(data.tgl_masuk));
                $('#norek').val(data.no_rekening);
                $('#noktp').val(data.no_ktp);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
            }
        });
    }
    
    function konfirmasi_save() {
        //$('#isi_debitur').val(tinyMCE.get('isi').getContent());
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
                    save_anggota();
                }
              }
            }
          });
      }

    function save_anggota() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/masterdata/anggota') ?>',
            dataType: 'json',
            data: $('#formadd').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(msg) {
                var page = $('.pagination .active a').html();
                hide_ajax_indicator();
                
                $('#datamodal').modal('hide');
                message_edit_success();
                get_list_tabungan(page);

            },
            error: function() {
                $('#datamodal').modal('hide');
                var page = $('.pagination .active a').html();
                get_list_tabungan(page);
                hide_ajax_indicator();
            }
        });
    }
        
    function paging(p) {
        get_list_tabungan(p);
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
                    <!--<button id="add_tabungan" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>-->
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="reload_tabungan" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload Data</button>
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
                            <th width="10%" class="left">No. Anggota</th>
                            <th width="25%" class="left">Nama</th>
                            <th width="10%" class="right">Awal</th>
                            <th width="10%" class="right">Masuk</th>
                            <th width="10%" class="right">Keluar</th>
                            <th width="10%" class="right">Sisa</th>
                            <th width="15%" class="left">Petugas</th>
                            
                            
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
                <input type="hidden"class="form-control" id="tanggal_hide" />
                <div class="form-group">
                    <label class="control-label">Tanggal Masuk:</label>
                    <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" />
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. Rekening:</label>
                    <input type="text" name="norek"  class="form-control" id="norek">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">No. KTP:</label>
                    <input type="text" name="noktp"  class="form-control" id="noktp">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Nama Anggota:</label>
                    <input type="text" name="nama"  class="form-control" id="nama">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Alamat:</label>
                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
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
                        <span><input type="text" name="awal" id="awal" class="form-control" value="" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                        <span><input type="text" name="akhir" id="akhir" class="form-control" value="" style="width: 145px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">No. Rekening:</label>
                        <input type="text" name="id_anggota"  class="select2-input" id="norek_cari">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_tabungan(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
      <!-- END PAGE -->
    </div>