<title><?= $title ?></title>
<script type="text/javascript">
    $(function() {
        get_data_institusi();
    });
    function get_data_institusi() {
        $.ajax({
            type: 'GET',
            url: '<?= base_url('api/config/institusi') ?>',
            dataType: 'json',
            success: function(data) {
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#provinsi').val(data.provinsi);
                $('#kabupaten').val(data.kabupaten);
                $('#kecamatan').val(data.kecamatan);
                $('#kelurahan').val(data.kelurahan);
                $('#kepsek').val(data.kepala);
                $('#nipkepsek').val(data.nip_kepala);
                $('#ketua_komite').val(data.ketua_komite);
                $('#bendahara').val(data.bendahara);
                $('#nip_bendahara').val(data.nip_bendahara);
                $('#nsm').val(data.nsm);
            }
        });
    }
    
    function save_institusi() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/save_institusi') ?>',
            data: $('#chpass').serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status === false) {
                    message_edit_failed();
                } else {
                    message_edit_success();
                    get_data_institusi();
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
                    <div class="col-md-8 col-sm-8 col-xs-8">
                        <form id="chpass">
                        <div class="form-group">
                            <label class="form-label">Nama Institusi:</label>    
                            <div class="controls">
                                <input type="text" name="nama" id="nama" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Alamat:</label>
                            <div class="controls">
                                <textarea name="alamat" id="alamat" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Provinsi:</label>
                            <div class="controls">
                                <input type="text" name="provinsi" id="provinsi" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Kabupaten:</label>
                            <div class="controls">
                                <input type="text" name="kabupaten" id="kabupaten" class="form-control" />
                            </div>
                        </div>
                            <div class="form-group">
                        <label class="form-label">Kecamatan:</label>
                            <div class="controls">
                                <input type="text" name="kecamatan" id="kecamatan" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Kelurahan / Desa:</label>
                            <div class="controls">
                                <input type="text" name="kelurahan" id="kelurahan" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label"></label>
                            <div class="controls">
                                <button class="btn btn-info btn-cons" onclick="save_institusi(); return false;"><i class="fa fa-paste"></i> Simpan Data</button>
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