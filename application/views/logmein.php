<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<!--------------------
LOGIN FORM
by: Amit Jakhu
www.amitjakhu.com
--------------------->

<!--META-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login Form | <?= $title ?></title>

<!--STYLESHEETS-->
<link href="<?= base_url('assets/css/login.css') ?>" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>" />
<link href="<?= base_url('assets/css/pnotify.custom.min.css') ?>" rel="stylesheet" type="text/css" />
<!--SCRIPTS-->
<script type="text/javascript" src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.cookies.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pnotify.custom.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/library.js') ?>"></script>
<!--Slider-in icons-->
<script type="text/javascript">
    $(document).ready(function() {
        $(".username").focus(function() {
                $(".user-icon").css("left","-48px");
        });
        $(".username").blur(function() {
                $(".user-icon").css("left","0px");
        });

        $(".password").focus(function() {
                $(".pass-icon").css("left","-48px");
        });
        $(".password").blur(function() {
                $(".pass-icon").css("left","0px");
        });
        $.cookie('url', null);
        $('#username').focus();
        $('.warning').hide();
        $('input').live('keyup', function(e) {
            if (e.keyCode===13) {
                loginForm();
            }
        });
        
    });
    
    /*$(document).ready(function(){
            $.cookie('url', null);
            $('#username').focus();
            $('.warning').hide();
            $('input').live('keyup', function(e) {
                if (e.keyCode===13) {
                    loginForm();
                }
            });
        });*/
    
    function loginForm() {
        var Url = '<?= base_url('user/login') ?>';
        $.ajax({
            type : 'POST',
            url: Url,               
            data: $('#loginform').serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status === 'login'){
                    location.href='<?= base_url('user/') ?>';
                } else {
                    dinamic_message('Peringatan','Username dan password harus diisikan secara benar !');
                }            
            }, error: function() {
                dinamic_message('Peringatan','Username dan password harus diisikan secara benar !');
            }
        });
        return false;
    }

</script>       
</head>
<body>

<!--WRAPPER-->
<div id="wrapper">

	<!--SLIDE-IN ICONS-->
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->

<!--LOGIN FORM-->
<form name="login-form" id="loginform" class="login-form" action="" method="post">

	<!--HEADER-->
    <div class="header">
        <!--TITLE--><h3>Koperasi </h3><h1><?= $title ?></h1><!--END TITLE-->
    <!--DESCRIPTION--><span>Fill out the form below to login to administrator's system.</span><!--END DESCRIPTION-->
    </div>
    <!--END HEADER-->
	
	<!--CONTENT-->
    <div class="content">
	<!--USERNAME--><input name="username" type="text" class="input username" placeholder="Username ... " /><!--END USERNAME-->
    <!--PASSWORD--><input name="password" type="password" class="input password" placeholder="Password ..." /><!--END PASSWORD-->
    </div>
    <!--END CONTENT-->
    
    <!--FOOTER-->
    <div class="footer">
        <!--LOGIN BUTTON--><input type="button" name="submit" value="Login" class="button" onclick="loginForm();" /><!--END LOGIN BUTTON-->
    <!--REGISTER BUTTON-->
        <input type="reset" name="submit" value="Cancel" class="register" />
    <!--END REGISTER BUTTON-->
    </div>
    <!--END FOOTER-->

</form>
<!--END LOGIN FORM-->

</div>
<!--END WRAPPER-->

<!--GRADIENT--><div class="gradient"></div><!--END GRADIENT-->

</body>
</html>