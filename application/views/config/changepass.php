<title><?= $title ?></title>
<script type="text/javascript">
    
    function ubah_password() {
        if ($('#passlama').val() === '') {
            dc_validation('#passlama','Password lama tidak boleh kosong !'); return false;
        }
        if ($('#passbaru').val() === '') {
            dc_validation('#passbaru','Password baru tidak boleh kosong !'); return false;
        }
        if ($('#ulangipass').val() === '') {
            dc_validation('#ulangipass','Password retype tidak boleh kosong !'); return false;
        }
        if ($('#passbaru').val() !== $('#ulangipass').val()) {
            dc_validation('#ulangipass','Password baru harus sama dengan password konfirmasi !'); return false;
        }
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/config/change_password') ?>',
            data: $('#chpass').serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status === false) {
                    message_edit_failed();
                } else {
                    message_edit_success();
                    reset_form();
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
              <h4>Form Ubah Password</h4>
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
                            <label class="form-label">Password Lama:</label>    
                            <div class="controls">
                                <input type="password" name="passlama" id="passlama" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Password Baru:</label>
                            <div class="controls">
                                <input type="password" name="passbaru" id="passbaru" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label">Ulangi Password:</label>
                            <div class="controls">
                                <input type="password" name="ulangipass" id="ulangipass" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="form-label"></label>
                            <div class="controls">
                                <button class="btn btn-info btn-cons" onclick="ubah_password(); return false;"><i class="fa fa-paste"></i> Ubah Password</button>
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