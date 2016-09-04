<title><?= $title ?></title>
<script src="<?= base_url('assets/js/wizard/bwizard.js') ?>"></script>
<script type="text/javascript">
    $(function() {
        $("#wizard").bwizard();
        get_list_pembiayaan(1);
        $('#add_pembiayaan').click(function() {
            reset_form();
            $('#wizard').bwizard('show','0');
            $('#datamodal').modal('show');
            $('#datamodal h4.modal-title').html('Tambah <?= $title ?>');
            $('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
        });
        
        $('#tanggal, #tanggal_disetujui, #awal, #akhir').datepicker({
                format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#cari_button').click(function() {
            $('#datamodal_search').modal('show');
        });
        $('#reload_pembiayaan').click(function() {
            reset_form();
            get_list_pembiayaan(1);
        });
        
        $('#jumlah').focus(function() {
            var nilai = $(this).val();
            $(this).val(currencyToNumber(nilai));
        });
        
        $('#nama').select2({
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
                $('#alamat').val(data.alamat);
                $('#norekening').val(data.no_rekening);
                $('#nama_anggota').val(data.nama);
                return data.no_rekening+' - '+data.nama;
            }
        });
        
        $('#koderekening').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('api/masterdata_auto/norek_pinjaman_auto') ?>",
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
                var markup = data.no_rekening+' - '+data.nama;
                return markup;
            }, 
            formatSelection: function(data){
                return data.no_rekening+' - '+data.nama;
            }
        });
    });
    
    function get_list_pembiayaan(p, id) {
        $('#form-pencarian').modal('hide');
        var id_transaksi = '';
        if (id !== undefined) {
            id_transaksi = id;
        }
        $.ajax({
            type : 'GET',
            url: '<?= base_url("api/transaksi/pembiayaans") ?>/page/'+p+'/id/'+id_transaksi,
            data: $('#form_search').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
                $("#example-advanced").treetable('destroy');
            },
            success: function(data) {
                if ((p > 1) & (data.data.length === 0)) {
                    get_list_pembiayaan(p-1);
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
                            '<td align="center">'+datefmysql(v.tgl_pinjam)+'</td>'+
                            '<td align="center">'+v.no_rekening+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right">'+money_format(v.jml_pinjaman)+'</td>'+
                            '<td align="right">'+money_format(v.bsr_angsuran)+'</td>'+
                            '<td align="right">'+money_format(v.angsuran_pokok)+'</td>'+
                            '<td align="right">'+money_format(v.jasa_angsuran)+'</td>'+
                            '<td align="right">'+money_format(v.sisa_angsuran)+'</td>'+
                            '<td align="right" class=aksi>'+
                                //'<button type="button" class="btn btn-mini" onclick="print_pajak(\''+v.id+'\')"><i class="fa fa-print"></i></button> '+
                                '<button type="button" class="btn btn-default btn-mini" onclick="edit_pembiayaan(\''+v.id+'\')"><i class="fa fa-pencil"></i></button> '+
                                //'<button type="button" class="btn btn-default btn-mini" onclick="delete_pembiayaan(\''+v.id+'\','+data.page+');"><i class="fa fa-trash-o"></i></button>'+
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
        $('#awal').val('');
        $('#akhir').val('');
        $('#tanggal').val('<?= date("d/m/Y") ?>');
        $('.select2-chosen').html('');
    }

    function edit_pembiayaan(id) {
        $('#oldpict').html('');
        $('#datamodal').modal('show');
        $('#wizard').bwizard('show','0');
        $('#datamodal h4.modal-title').html('Edit <?= $title ?>');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/transaksi/pembiayaans') ?>/page/1/id/'+id,
            dataType: 'json',
            success: function(data) {
                //$('#tanggal_disetujui, #jumlah, #lama').removeAttr('disabled');
                var data = data.data[0];
                var jml_pinjaman = data.jml_pinjaman.split('.');
                $('#id').val(data.id);
                $('#s2id_nama a .select2-chosen').html(data.nama);
                $('#nama').val(data.id_debitur);
                $('#norekening').val(data.no_rekening);
                $('#nama_anggota').val(data.nama);
                $('#tanggal').val(datefmysql(data.tgl_pinjam));
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
                $('#rencana_pembiayaan').val(data.rencana_pembiayaan);
                $('#infodari').val(data.info_dari);
                
                $('#tanggal_disetujui').val(datefmysql(data.tgl_pinjam));
                $('#tanggal_disetujui').datepicker('setDate', datefmysql(data.tgl_pinjam));
                $('#jumlah').val(numberToCurrency(jml_pinjaman[0]));
                $('#lama').val(data.lama_pinjaman);
                
                //if (parseFloat(data.sisa_angsuran) !== parseFloat(data.ttl_pengembalian)) {
                    //$('#tanggal_disetujui, #jumlah, #lama').attr('disabled','disabled');
                //}
            }
        });
    }
        
    function paging(p) {
        get_list_pembiayaan(p);
    }

    function konfirmasi_save() {
        //$('#isi_pembiayaan').val(tinyMCE.get('isi').getContent());
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
                    save_pembiayaan();
                }
              }
            }
          });
      }

    function save_pembiayaan() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/transaksi/pembiayaan') ?>',
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
                    get_list_pembiayaan(1);
                }
            },
            error: function() {
                hide_ajax_indicator();
            }
        });
    }

    function delete_pembiayaan(id, page) {
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
                        url: '<?= base_url('api/transaksi/pembiayaan') ?>/id/'+id,
                        dataType: 'json',
                        success: function(data) {
                            message_delete_success();
                            get_list_pembiayaan(page);
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
                    <button id="add_pembiayaan" class="btn btn-info btn-mini"><i class="fa fa-plus-circle"></i> Tambah</button>
                    <button id="cari_button" class="btn btn-mini"><i class="fa fa-search"></i> Cari</button>
                    <button id="reload_pembiayaan" class="btn btn-mini"><i class="fa fa-refresh"></i> Reload Data</button>
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
                          <th width="7%">No. Rek.</th>
                          <th width="25%" class="left">Nama</th>
                          <th width="10%" class="right">Jumlah</th>
                          <th width="10%" class="right">Angsuran</th>
                          <th width="10%" class="right">Angs.&nbsp;Pokok</th>
                          <th width="8%" class="right">Bunga</th>
                          <!--<th width="10%" class="left">Jenis</th>-->
                          <th width="10%" class="right">Sisa&nbsp;Pinj.</th>
                          <th width="5%"></th>
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
            <div class="modal-dialog" style="width: 800px">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="formadd" method="post" role="form" class="form-horizontal">
                    <div id="wizard">
                        <ol>
                          <li>Data Utama</li>
                          <li>Data Permohonan Pembiayaan</li>
                        </ol>
                        <div>
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="norekening" id="norekening" />
                            <input type="hidden" name="nama_anggota" id="nama_anggota" />
                            <div class="form-group">
                                <label class="control-label col-lg-3">Tanggal Pengajuan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="tanggal" class="form-control" style="width: 145px;" id="tanggal" value="<?= date("d/m/Y") ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Nama Anggota:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nama"  class="select2-input" id="nama">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Alamat:</label>
                                <div class="col-lg-8">
                                    <textarea name="alamat" id="alamat" disabled="" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Nama Pasangan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="nama_psg"  class="form-control" id="nama_psg">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Pekerjaan Pasangan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="pekerjaan_psg"  class="form-control" id="pekerjaan_psg" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Status Rumah:</label>
                                <div class="col-lg-8">
                                    <select name="status_rumah" id="status_rumah" class="form-control">
                                        <option value="">Pilih ...</option>
                                        <?php foreach ($status_rumah as $data) { ?>
                                        <option value="<?= $data ?>"><?= $data ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Penghasilan Per-Bulan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="penghasilan" onchange="FormNum(this);"  class="form-control" id="penghasilan" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Pengeluaran Per-Bulan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="pengeluaran" onchange="FormNum(this);" class="form-control" id="pengeluaran" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Jaminan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="jaminan"  class="form-control" id="jaminan" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Keperluan Pembiayaan:</label>
                                <div class="col-lg-8">
                                    <textarea name="rencana_pembiayaan" id="rencana_pembiayaan" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="jenis_pinjaman" value="1" />
                            <div class="form-group">
                                <label class="control-label col-lg-3">Tanggal Mulai Angsur:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="tanggal_disetujui" class="form-control" style="width: 145px;" id="tanggal_disetujui" value="<?= date("d/m/Y") ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Jumlah Pembiayaan:</label>
                                <div class="col-lg-8">
                                    <input type="text" name="jumlah" onblur="FormNum(this);" class="form-control" id="jumlah" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">Lama Pembiayaan:</label>
                                <div class="col-lg-8">
                                    <select name="lama" id="lama" class="form-control">
                                        <option value="">Pilih ...</option>
                                        <?php foreach ($lama_pembiayaan as $data) { ?>
                                        <option value="<?= $data->durasi ?>"><?= $data->durasi ?> Bulan</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
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
                        <span><input type="text" name="awal" id="awal" class="form-control" value="" style="width: 145px; float: left; margin-right: 10px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                        <span><input type="text" name="akhir" id="akhir" class="form-control" value="" style="width: 145px;" id="awal" value="<?= date("d/m/Y") ?>" /> </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">No. Rekening:</label>
                        <input type="text" name="koderekening" id="koderekening" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
              <button type="button" class="btn btn-primary" onclick="get_list_pembiayaan(1);"><i class="fa fa-eye"></i> Tampilkan</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
      </div>
      <!-- END PAGE -->
    </div>