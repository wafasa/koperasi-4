<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        get_data_administrasi();
    });
    function get_data_administrasi() {
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/administrasi') ?>',
            dataType: 'json',
            success: function(msg) {
                var data = msg.adm;
                $('#administrasi').val(data.administrasi);
                $('#persen1').val(data.persen_jasa_usaha);
                $('#persen2').val(data.persen_simpanan);
                $('#bunga_pinjaman').val(data.bunga_pinjaman);
                $('#simpanan_pokok').val(numberToCurrency(data.simpanan_pokok));
                $('#simpanan_wajib').val(numberToCurrency(data.simpanan_wajib));
                $('#persentase').val(msg.denda);
            }
        });
    }
    
    function konfirmasi_save() {
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
                    save_administrasi();
                }
              }
            }
        });
    }
    
    function save_administrasi() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/save_administrasi') ?>',
            data: $('#chpass').serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status === false) {
                    message_edit_failed();
                } else {
                    message_edit_success();
                    get_data_administrasi();
                }
            }
        });
    }
    
    function reset_form() {
        $('input, select, textarea').val('');
        $('#oldpict').html('');
        $('input[type=checkbox], input[type=radio]').removeAttr('checked');
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
              <h4><?= $title ?></h4>
                <div class="tools"> 
                    
                </div>
            </div>
            <div class="grid-body">
                <div class="row">
                    <div class="col-md-12">
                    <form id="chpass" class="form-horizontal">
                    <div class="col-lg-5">
                        <div class="form-group">
                        <label class="form-label col-lg-12">Biaya Administrasi Pinjaman (%):</label>
                            <div class="controls col-lg-12">
                                <input type="number" min="1" max="100" maxlength="3" name="administrasi" id="administrasi" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12">Bunga Pinjaman (%):</label>
                            <div class="controls col-lg-12">
                                <input type="text" name="bunga_pinjaman" id="bunga_pinjaman" onblur="FormNum(this);" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12">Persentase Jasa Usaha (SHU) (%):</label>
                            <div class="controls col-lg-12">
                                <input type="text" name="persen1" id="persen1" onblur="FormNum(this);" maxlength="2" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12">Persentase Jasa Simpanan (SHU) (%):</label>
                            <div class="controls col-lg-12">
                                <input type="text" name="persen2" id="persen2" onblur="FormNum(this);" maxlength="2" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12"></label>
                            <div class="controls col-lg-12">
                                <button class="btn btn-info btn-cons" onclick="konfirmasi_save(); return false;"><i class="fa fa-paste"></i> Simpan Data</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                        <label class="form-label col-lg-12">Simpanan Pokok:</label>
                            <div class="controls col-lg-12">
                                <input type="text" name="simpanan_pokok" id="simpanan_pokok" onblur="FormNum(this);" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12">Simpanan Wajib:</label>
                            <div class="controls col-lg-12">
                                <input type="text" name="simpanan_wajib" id="simpanan_wajib" onblur="FormNum(this);" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label col-lg-12">Persentase Denda per hari:</label>
                        <div class="controls col-lg-12">
                            <input type="text" name="persentase" id="persentase" class="form-control" placeholder="Persesntase denda ..." />
                        </div>
                        </div>
                    </div>
                    </form>
                    </div>
                  </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END PAGE -->
    </div>