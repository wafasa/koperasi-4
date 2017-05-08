<style>
    .table-bordered tr th, .table-bordered tr td {
        border: 1px solid #ccc;
    }
</style>
<script type="text/javascript">
    $(function() {
        //tampilkan(1);
        $('#bt_cari').click(function() {
            $('#datamodal_no').modal('show');
            $('#modal_title').html('Tambah barang');
        });
        $('#awal').datepicker({
            format: 'yyyy-mm',
            viewMode: 'months', 
            minViewMode: 'months'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });

        $('#reset').click(function() {
            $('#awal').val('<?= date("Y-m") ?>');
            $('#s2id_rekening_auto a .select2-chosen').html('');
            $('#rekening_auto').val('');
            //tampilkan(1);
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
                return data.kode+' '+data.nama;
            }
        }); 
    });

    function paging(p) {
        tampilkan(p);
    }

    function tampilkan(p, id_bukubesar) {
        $('#result').empty();
        $('#datamodal_no').modal('hide');
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/akuntansi/bukubesars') ?>/page/1/',
            data: $('#form').serialize(),
            success: function(data) {
                //var data = msg.data;
                $.each(data.data, function(i, v) {
                    create_table(v.detail, '<table width="100%"><tr><td width="50%"><i class="fa fa-bug"></i> Buku Besar: <b>'+v.nama+' </b></td><td width="50%"> Nomor Account: '+v.kode+'</td></tr></table>', i);
                });
                //create_table(data.data);
            }
        });    
    }
    
    function create_table(data, caption, j) {
        var str = caption;
        str+= '<table width="100%" class="table table-bordered table-stripped table-hover" id="data-penjamin" cellspacing="0">'+
                '<thead>'+
                    '<tr>'+
                        '<th width="3%" rowspan="2">No.</th>'+
                        '<th width="5%" rowspan="2">Tanggal</th>'+
                        '<th width="30%" rowspan="2">Penjelasan</th>'+
                        '<th width="10%" rowspan="2">Ref</th>'+
                        '<th width="10%" rowspan="2">Debet</th>'+
                        '<th width="10%" rowspan="2">Kredit</th>'+
                        '<th width="20%" colspan="2">Saldo</th>'+
                    '</tr>'+
                    '<tr>'+
                        '<th width="10%">Debet</th>'+
                        '<th width="10%">Kredit</th>'+
                    '</tr>'+
                '</thead><tbody>';
        var debet = 0; var kredit = 0;
        $.each(data, function(i, v) {
            debet += parseFloat(v.debet)-parseFloat(v.kredit);
            if (debet < 0) {
                kredit += parseFloat(v.debet)-parseFloat(v.kredit);
            }
            str+='<tr>'+
                    '<td align="center">'+(++i)+'</td>'+
                    '<td align="center">'+datefmysql(v.tanggal)+'</td>'+ 
                    '<td>'+v.ket_transaksi+'</td>'+ 
                    '<td>'+v.no_kwitansi+'</td>'+ 
                    '<td align="right">'+((v.debet !== '0')?money_format(v.debet):'')+'</td>'+
                    '<td align="right">'+((v.kredit !== '0')?money_format(v.kredit):'')+'</td>'+
                    '<td align="right">'+((debet > 0)?money_format(Math.abs(debet)):'')+'</td>'+
                    '<td align="right">'+((kredit < 0)?money_format(Math.abs(kredit)):'')+'</td>'+
                '</tr>';
        });
        str+='</tbody></table>';
        $('#result').append(str);
    }
    
    function delete_jurnal(id, p){        
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
                    url: '<?= base_url("api/akuntansi/bukubesar") ?>/id/'+id,
                    cache: false,
                    dataType : 'json',
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
                    <button class="btn btn-mini btn-primary" id="bt_cari"><i class="fa fa-search"></i> Pencarian</button>
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
                                <th width="30%" class="left">Jurnal</th>
                                <th width="10%" class="left">Ref</th>
                                <th width="10%" class="right">Debet</th>
                                <th width="10%" class="right">Kredit</th>
                                <!--<th width="2%" class="right"></th>-->
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
        <div id="datamodal_no" class="modal fade">
            <div class="modal-dialog" style="width: 610px; height: 100%;">
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
                    <?= form_open('','id=form role=form class=form-horizontal') ?>
                    
                    <div class="col-md-12">
                        <div class="widget-body">
                            <div class="form-group">
                                <label for="barang" class="col-lg-3 control-label">Tanggal:</label>
                                <div class="col-lg-9">
                                    <input type="text" name="awal" class="form-control" id="awal" value="<?= date("Y-m") ?>" style="width: 145px; float: left; margin-right: 10px;">
                                </div>
                            </div>
                            <div class="form-group edit_hide">
                                <label class="col-lg-3 control-label">Rekening</label>
                                <div class="col-lg-9">
                                    <input type="text" name="rekening" id="rekening_auto" class="select2-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Close</button>
                    <button type="button" class="btn btn-primary" onclick="tampilkan(1);"><i class="fa fa-eye"></i> Tampilkan</button>
                </div>
            </div>
            </div>
        </div>
          
      </div>
</div>



<!--<div class="page_summary" id="page_summary"></div>
<div id="pagination"></div>-->