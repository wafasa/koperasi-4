<script type="text/javascript">
    $(function() {
        get_list_shu();
        $('#bt_show').click(function() {
            $('#datamodal_no').modal('show');
        });
        
        $("#tanggal").datepicker({
            format: 'dd/mm/yyyy'
        }).on('changeDate', function(){
            $(this).datepicker('hide');
        });
        
        $('#bt_cetak').click(function() {
            print_shu();
        });

        $('.form-control').change(function() {
            if ($(this).val() !== '') {
                dc_validation_remove(this);
            }
        });
    });
    
    function print_shu() {
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url() ?>akuntansi/load_shu?'+$('#formsearch').serialize()+'&do=cetak','Cetak SHU','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
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
    
    function get_list_shu() {
        if ($('#tanggal').val() === '') {
            dc_validation('#tanggal','Tanggal harus di isi !'); return false;
        }
        $('#datamodal_no').modal('hide');
        $.ajax({
            url: '<?= base_url('api/akuntansi/laba_rugi') ?>',
            data: $('#formsearch').serialize(),
            beforeSend: function() {
                show_ajax_indicator();
            },
            success: function(data) {
                hide_ajax_indicator();
                create_left_side(data.pendapatan, data.beban);
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
    
    function create_left_side(pendapatan, beban) {
        var str = '<table id="table_rekening" class="table" style="border-bottom: 1px solid #ccc; width: 60%;">'+
                    ''+
                    '<tbody><tr><td colspan="5"><b>PENDAPATAN</b></td></tr>';
                    var total = 0;
                    $.each(pendapatan, function(i, v) {
                        var subtotal = parseFloat(v.subtotal);
                        str+='<tr>'+
                            '<td width="3%"></td>'+
                            '<td width="5%">'+v.kode+'</td>'+
                            '<td width="53%">'+v.nama+'</td>'+
                            '<td width="20%" align="right"><span style="float: left;">Rp. </span>'+((subtotal < 0)?'('+money_format(Math.abs(subtotal))+')':money_format(Math.abs(subtotal)))+'</td>'+
                            '<td width="20%"></td>'+
                        '</tr>';
                        total += parseFloat(v.subtotal);
                    });
                    var total_pendapatan = ((total >= 0)?money_format(total):'('+money_format(Math.abs(total))+')');
                    str+='<tr><td colspan="3" align="center">TOTAL PENDAPATAN</td><td></td><td align="right"><span style="float: left;">Rp. </span><b>'+total_pendapatan+'</b></td></tr>'+
                         '<tr><td colspan="5">&nbsp;</td></tr>'+
                         '<tr><td colspan="5"><b>BIAYA</b></td></tr>';
                    var total_biaya = 0;
                    $.each(beban, function(i, v) {
                        var subtotal = parseFloat(v.subtotal);
                        str+='<tr>'+
                            '<td></td>'+
                            '<td>'+v.kode+'</td>'+
                            '<td>'+v.nama+'</td>'+
                            '<td align="right"><span style="float: left;">Rp. </span>'+money_format(Math.abs(subtotal))+'</td>'+
                            '<td></td>'+
                        '</tr>';
                        total_biaya += parseFloat(v.subtotal);
                    });
                    var total_beban = ((total_biaya >= 0)?money_format(total_biaya):'('+money_format(Math.abs(total_biaya))+')');
                    
                    var grand_total = (total + (total_biaya));
                    str+='<tr><td colspan="3" align="center">TOTAL BIAYA</td><td></td><td align="right"><span style="float: left;">Rp. </span><b>'+total_beban+'</b></td></tr>'+
                         '<tr><td colspan="5" style="border-bottom: 2px solid #000;">&nbsp;</td></tr>'+
                         '<tr><td colspan="4"><b>LABA (RUGI) BERSIH</b></td><td align="right"><span style="float: left;">Rp. </span><b>'+((grand_total < 0)?'('+money_format(Math.abs(grand_total))+')':money_format(grand_total))+'</b></td></tr>';
                    str+='</tbody></table>';
          $('#load-shu').html(str);
    }
</script>
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
                        <label for="barang" class="col-lg-3 control-label">LR sampai tanggal:</label>
                        <div class="col-lg-8">
                            <input type="text" name="tanggal" id="tanggal" value="<?= date("d/m/Y") ?>" class="form-control" style="width: 145px;" />
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-refresh"></i> Batal</button>
        <button type="button" class="btn btn-primary" onclick="get_list_shu();"><i class="fa fa-eye"></i> Tampilkan</button>
    </div>
    </div>
    </div>
</div>
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
                    <button class="btn btn-mini btn-primary" id="bt_show"><i class="fa fa-search"></i> Pencarian</button>
                    <button class="btn btn-mini" id="bt_cetak"><i class="fa fa-print"></i> Cetak</button>

                </div> 
            </div>
            <div class="grid-body">
              <div class="scroller" data-height="220px">
                <div id="result">
                    <div id="load-shu"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>