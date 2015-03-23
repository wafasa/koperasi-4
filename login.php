
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>::Koperasi : LOGIN ::</title>

<link rel='stylesheet' type='text/css' href='__css/mystyle.css'/>

<script type="text/JavaScript">

function ceklogin(isian){

	if(isian.username.value == ""){

		alert("Username harus diisi");

		isian.username.focus();

		return false;

	}

	if(isian.passwd.value == ""){

		alert("Maaf, password harus diisi");

		isian.passwd.focus();

		return false;

	}



}

</script>

</head>



<body onload="document.formData.username.focus();" class="body-login">

<div class="login-wrapper">
	<div class="login-header"></div>
	<div class="login-body">
    
<h1>HALAMAN LOGIN</h1>  
<form action="plogin.php" method="post" name="formData" id="formData" onsubmit="return ceklogin(this);">
<table width="300" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto">
  <tr>
    <td height="25" align="left" valign="middle">Username</td>

    <td align="left" valign="middle">

      <input name="username" type="text" class="TextBox" id="username" onfocus="this.className='TextBoxOn'" onblur="this.className='TextBox'" value="" size="25" maxlength="25" />    </td>

  </tr>

  <tr>

    <td height="25" align="left" valign="middle">Password</td>

    <td align="left" valign="middle">

      <input name="passwd" type="password" class="TextBox" id="passwd" onfocus="this.className='TextBoxOn'" onblur="this.className='TextBox'" value="" size="25" maxlength="25" />    </td>

  </tr>

  <tr>

    <td height="45" colspan="2" align="center" valign="bottom"><input type="submit" name="imageField" class="login-button" value=""/></td>

    </tr>

</table>

</form>


</div>
<div class="login-footer">&copy; 2010. <a href="" target="__blank">Arvin Nizar, S.Kom</a> </div>
</div>



</body>

</html>

