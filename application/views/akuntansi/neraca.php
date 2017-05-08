<script type="text/javascript">
    $(function() {
        get_list_neraca();
        $('#bt_show').click(function() {
            $('#datamodal_no').modal('show');
        });
        
        $("#tanggal").datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        $('#bt_cetak').click(function() {
            print_neraca();
        });

        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove(this);
            }
        });
    });
    
    function print_neraca() {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('printing/cetak_neraca') ?>?'+$('#formsearch').serialize()+'&do=cetak','Cetak SHU','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
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
    
    function get_list_neraca() {
        if ($('#tanggal').val() === '') {
            dc_validation('#tanggal','Tanggal harus di isi !'); return false;
        }
        show_ajax_indicator();
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/akuntansi/neraca_saldo') ?>',
            data: $('#formsearch').serialize(),
            success: function(data) {
                hide_ajax_indicator();
                create_left_side(data.aktiva);
                create_right_side(data.lajur_kanan);
            },
            complete: function() {
                sejajar();
            },
            error: function(e){
                hide_ajax_indicator();
                access_failed(e.status);
            }
        });
        $('#datamodal_no').modal('hide');
    }
    
    function create_left_side(data) {
        var str = '<table id="table_rekening" width="100%" style="border-bottom: 1px solid #ccc;">'+
                    '<tbody>';
                    var total = 0;
                    $.each(data, function(i, v) {
                        var subtotal = parseFloat(v.subtotal);
                        str+='<tr>'+
                            '<td width="10%">'+v.kode+'</td>'+
                            '<td width="60%">'+v.nama+'</td>'+
                            '<td width="30%" align="right">'+((subtotal < 0)?'('+money_format(Math.abs(subtotal))+')':money_format(Math.abs(subtotal)))+'</td>'+
                        '</tr>';
                        total += parseFloat(v.subtotal);
                    });
                    str+='</tbody>'+
                         '<tfoot>'+
                            '<tr><td colspan="2" align="center">TOTAL AKTIVA</td><td align="right"><b>'+money_format(Math.abs(total))+'</b></td></tr>'+
                         '</tfoot>'+
                  '</table>';
          $('#load_aktiva').html(str);
    }
    
    function create_right_side(data) {
        var str = '<table id="table_rekening2" width="100%" style="border-bottom: 1px solid #ccc;">'+
                    '<tbody>';
                    var total = 0;
                    $.each(data, function(i, v) {
                        var subtotal = parseFloat(v.subtotal);
                        str+='<tr>'+
                            '<td width="10%">'+v.kode+'</td>'+
                            '<td width="60%">'+v.nama+'</td>'+
                            '<td width="30%" align="right">'+((subtotal > 0)?money_format(Math.abs(subtotal)):money_format(Math.abs(subtotal)))+'</td>'+
                        '</tr>';
                        total += parseFloat(v.subtotal);
                    });
                    str+='</tbody>'+
                         '<tfoot>'+
                            '<tr><td colspan="2" align="center">TOTAL PASIVA</td><td align="right"><b>'+money_format(Math.abs(total))+'</b></td></tr>'+
                         '</tfoot>'+
                  '</table>';
          $('#load_pasiva').html(str);
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
                    <button class="btn btn-mini btn-primary" id="bt_show"><i class="fa fa-plus-circle"></i> Cari</button>
                    <button class="btn btn-mini" onclick="get_list_neraca();"><i class="fa fa-refresh"></i> Reload Data</button>

                </div> 
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <table class="table labarugi" id="table_data_no">
                        <thead>
                            <tr class="heading">
                                <th width="50%">AKTIVA</th>
                                <th width="50%">PASIVA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="load_aktiva"></td>
                                <td id="load_pasiva"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              </div>
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
                <h4>Pencarian</h4>
            </div>
        </div>
      </div>
        
      <div class="modal-body">
        <?= form_open('','id=formsearch role=form class=form-horizontal') ?>
          <div class="row">
            <div class="col-md-12">
                <div class="widget-body">
                    <!--<div class="form-group">
                        <label for="barang" class="col-lg-2 control-label">Tampilkan:</label>
                        <div class="col-lg-10">
                            <select name="show" id="show" class="form-control"><option value="rincian">Rincian</option><option value="rekap">Rekap</option></select>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="barang" class="col-lg-4 control-label">Neraca Per Tanggal:</label>
                        <div class="col-lg-4">
                            <input type="text" name="tanggal" id="tanggal" class="form-control" value="<?= date("d/m/Y") ?>" />
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
        <button type="button" class="btn btn-primary" onclick="get_list_neraca();"><i class="fa fa-eye"></i> Tampilkan</button>
    </div>
    </div>
    </div>
</div>


