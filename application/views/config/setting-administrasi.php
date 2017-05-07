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
            success: function(data) {
                $('#administrasi').val(data.administrasi);
                $('#persen1').val(data.persen_jasa_usaha);
                $('#persen2').val(data.persen_simpanan);
                $('#bunga_pinjaman').val(data.bunga_pinjaman);
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
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        
                    </div>
                    <div class="col-lg-5">
                        <form id="chpass">
                        <div class="form-group">
                        <label class="form-label">Biaya Administrasi Pinjaman (%):</label>
                            <div class="controls">
                                <input type="number" min="1" max="100" maxlength="3" name="administrasi" id="administrasi" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Bunga Pinjaman (%):</label>
                            <div class="controls">
                                <input type="text" name="bunga_pinjaman" id="bunga_pinjaman" onblur="FormNum(this);" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Persentase Jasa Usaha (SHU) (%):</label>
                            <div class="controls">
                                <input type="text" name="persen1" id="persen1" onblur="FormNum(this);" maxlength="2" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Persentase Jasa Simpanan (SHU) (%):</label>
                            <div class="controls">
                                <input type="text" name="persen2" id="persen2" onblur="FormNum(this);" maxlength="2" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label"></label>
                            <div class="controls">
                                <button class="btn btn-info btn-cons" onclick="save_administrasi(); return false;"><i class="fa fa-paste"></i> Simpan Data</button>
                            </div>
                        </div>
                        </form>
                    </div>
<!--                    <div class="col-lg-5">
                        <div class="form-group">
                        <label class="form-label">Kode Tabungan:</label>
                            <div class="controls">
                                <input type="text" maxlength="3" name="kode_tabungan" id="kode_tabungan" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Kode Peminjaman:</label>
                            <div class="controls">
                                <input type="text" maxlength="3" name="kode_pinjaman" id="kode_pinjaman" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Kode Koreksi:</label>
                            <div class="controls">
                                <input type="text" maxlength="3" name="kode_pinjaman" id="kode_pinjaman" class="form-control" />
                            </div>
                        </div>
                    </div>-->
                  </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END PAGE -->
    </div>