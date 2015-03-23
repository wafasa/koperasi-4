<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    
    <script>
function Angka(obj) {
	a = obj.value;
	b = a.replace(/[^\d]/g,"");
	c = "";
	lengthchar = b.length;
	j = 0;
	for (i = lengthchar; i > 0; i--) {
	j = j + 1;
	if (((j % 3) == 1) && (j != 1)) {
	c = b.substr(i-1,1) + "" + c;
	} else {
	c = b.substr(i-1,1) + c;
	}
	}
	obj.value = c;
}
function FormNum(obj) {
	a = obj.value;
	b = a.replace(/[^\d]/g,"");
	c = "";
	lengthchar = b.length;
	j = 0;
	for (i = lengthchar; i > 0; i--) {
	j = j + 1;
	if (((j % 3) == 1) && (j != 1)) {
	c = b.substr(i-1,1) + "." + c;
	} else {
	c = b.substr(i-1,1) + c;
	}
	}
	obj.value = c;
}

	</script>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>:: Sumber Baru - Eromoko Wonogiri ::</title>
        <style type="text/css">
            <!--
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
            }
            -->
        </style>
        <link href="../__css/style_table.css" rel="stylesheet" type="text/css" />
        <link href="../__css/mystyle.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../__js/lib/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
        <link rel="stylesheet" href="../__css/dropdown.css" type="text/css" />
		<script type="text/javascript" src="../__js/dropdown.js"></script>
        <script type="text/javascript" src="../__js/lib/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
        <script type='text/javascript' src='../__js/quickmenu.js'></script>
        <script type="text/javascript" src="../__js/lib/prototype/prototype.js"></script>
        <script type="text/javascript" src="../__js/lib/scriptaculous/src/scriptaculous.js?load=effects"></script>
        <script type='text/javascript' src='../__js/main.js'></script>
        <link rel="stylesheet" href="../__css/autocomplete.css" type="text/css" media="screen">
        <script src="../__js/jquery.js" type="text/javascript"></script>
        <script src="../__js/dimensions.js" type="text/javascript"></script>
        <script src="../__js/autocomplete.js" type="text/javascript"></script>
        <script type="text/JavaScript">
    <!--
    function do_write(message){
        document.getElementById("tampil").innerHTML = message;
        setTimeout("refresh()", 6000);
    }
    function refresh(){
        x_autojasa(do_write);
    }
    //-->
        </script>
	<script language="javascript"> 
    <!--
    var state = 'none';
    
    function showhide(layer_ref) {
    
    if (state == 'block') { 
    state = 'none'; 
    } 
    else { 
    state = 'block'; 
    } 
    if (document.all) { //IS IE 4 or 5 (or 6 beta) 
    eval( "document.all." + layer_ref + ".style.display = state"); 
    } 
    if (document.layers) { //IS NETSCAPE 4 or below 
    document.layers[layer_ref].display = state; 
    } 
    if (document.getElementById &&!document.all) { 
    hza = document.getElementById(layer_ref); 
    hza.style.display = state; 
    } 
    } 
    //--> 
    </script> 
    </head>

    <!--<body onload='refresh();' id="akutansi">-->
    <body  id="akutansi">
    <script src="../__js/wz_tooltip.js"></script>
        <div id="tampil">
        
        </div>

        <div id="balance" style="display:none">
            <!--EDIT BELOW CODE TO YOUR OWN MENU-->
            <table border="0" class="tabelslider" cellpadding="0" cellspacing="0" style="width:200px;">
                <tr>
                    <td class="isibox" bgcolor="#ffffff" width="100%">
                        <table class="kotakslider" border="0" width="200" cellspacing="0" cellpadding="0">
                            <tr valign="middle">
                                <th class="headerslider" width="93%" height="18" scope="col">
                                    <?
                                    $q = mysql_query("select * from sub_module where id_sub_module = $_REQUEST[menusub]");
                                    $data = mysql_fetch_array($q);
                                    ?>
                                    BALANCE<br /><?=$data[sub_module];?>
                                </th>
                                <th class="close" width="7%">[<a href='javascript:;' onclick="showBalance();">x</a>]</th>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div id=content>
                                        <?
                                        $qbalance = mysql_query("select sum(debet), sum(kredit) from transaksi where kd_jenis = $_REQUEST[kdjenis] and hapus = 0");
                                        if($qbalance){
                                            $dbalance = mysql_fetch_row($qbalance);
                                            $awal_d = rekening_awal($_REQUEST[kdjenis],"D-K");
                                            $awal_k = rekening_awal($_REQUEST[kdjenis],"K-D");
                                            $balance_d = $dbalance[0]+$awal_d;
                                            $balance_k = $dbalance[1]+$awal_k;
                                            echo "Debet : ".rupiah($balance_d)."<br>Kredit : ".rupiah($balance_k);
                                        }
                                        ?>
                                </div>        </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--END OF EDIT-->
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="3" background="../images/backheader_02.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="72%"><img src="../images/head.gif" height="110" /></td>
                            <td width="28%" align="right">
                            <div></div>		</td>
                        </tr>
                        <tr>
                            <td height="28" style="padding-left:5px;">{menu}</td>
                            <td height="28"><div id="sapaan">Selamat datang, <span class="nama"></span></div></td>
                        </tr>
                </table></td>
            </tr>
            <tr>
                <td colspan="3" valign="top"><img src="../images/spacer.gif" width="1" height="7" /></td>
            </tr>
            <tr>
                <td width="1%">&nbsp;</td>
                <td width="96%">{isi}</td>
                <td width="1%">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" valign="middle"><img src="../images/spacer.gif" width="1" height="5" /></td>
            </tr>
            <tr>
                <td height="27" colspan="4" align="right" bgcolor="#A9D54C" id="footer">
                    Copyright <a href="http://erpie.org/" target="__blank">Arvin Nizar S.Kom</a> &copy; 2010
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            //<![CDATA[

            var balancebox = document.getElementById('balance');
            //var theTimer = document.getElementById('QuizTimer');
            var theTop = 170;
            var old = theTop;
            movecounter(balancebox);
            //]]>
        </script>
    </body>
</html>