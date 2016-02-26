 counter = 0;
 
 var message="Sorry, right-click has been disabled";
 

function clickIE() {
           if (document.all) {
                      (message);
           return false;
           }
}

function clickNS(e) {
           if (document.layers||(document.getElementById&&!document.all)) { 
                      if (e.which==2||e.which==3) {
                                 (message);return false;
                      }
           }
}

function roundToTwo(num) {    
    return +(Math.round(num + "e+2")  + "e-2");
}


function numtocurr(a){
    if (a !== null) {
        a=a.toString();       
        var b = a.replace(/[^\d\,]/g,'');
        var dump = b.split(',');
        var c = '';
        var lengthchar = dump[0].length;
        var j = 0;
        for (var i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = dump[0].substr(i-1,1) + '.' + c;
                } else {
                        c = dump[0].substr(i-1,1) + c;
                }
        }
        
        if(dump.length>1){
                if(dump[1].length>0){
                        c += ','+dump[1];
                }else{
                        c += ',';
                }
        }
        return c;
    } else {
        return '0';
    }
}

function money_format(num) {
    if (num >= 0) {
        if(num.toString().indexOf('.') !== -1) {
            var coma = roundToTwo(num).toString().split('.');
            var new_coma = numtocurr(coma[0]);
            var new_coma2= '';
            if (coma[1] !== undefined && coma[1].length === 1) {
                new_coma2= coma[1]+'0';
            }
            else if (coma[1] === undefined) {
                new_coma2= '00';
            }
            else {
                new_coma2= coma[1];
            }
            var new_num  = new_coma+','+new_coma2;
            return new_num;
        } else {
            return numtocurr(num)+',00';
        }
    } if (num < 0) {
        if(Math.abs(num).toString().indexOf('.') !== -1) {
            var coma = roundToTwo(num).toString().split('.');
            var new_coma = numtocurr(coma[0]);
            var new_coma2= '';
            if (coma[1].length === 1) {
                new_coma2= coma[1]+'0';
            } else {
                new_coma2= coma[1];
            }
            var new_num  = new_coma+','+new_coma2;
            return '-'+new_num;
        } else {
            return '-'+numtocurr(num)+',00';
        }
    }
}

function money_format_save(value) {
    var str = value.toString().split('.').join('');
    var new_str = str.replace(/,/g, '.');
    return new_str;
}

function IsNumeric(input)
{
    return (input - 0) == input && (input+'').replace(/^\s+|\s+$/g, "").length > 0;
}
function strip(html) {
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}
function pembulatan_seratus(angka) {
    var kelipatan = 100;
    var sisa = angka % kelipatan;
    if (sisa !== 0) {
        var kekurangan = kelipatan - sisa;
        var hasilBulat = angka + kekurangan;
        return Math.ceil(hasilBulat);
    } else {
        return Math.ceil(angka);
    }   
}

function round(value, precision, mode) {
  //  discuss at: http://phpjs.org/functions/round/
  // original by: Philip Peterson
  //  revised by: Onno Marsman
  //  revised by: T.Wild
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
  //    input by: Greenseed
  //    input by: meo
  //    input by: William
  //    input by: Josep Sanz (http://www.ws3.es/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //        note: Great work. Ideas for improvement:
  //        note: - code more compliant with developer guidelines
  //        note: - for implementing PHP constant arguments look at
  //        note: the pathinfo() function, it offers the greatest
  //        note: flexibility & compatibility possible
  //   example 1: round(1241757, -3);
  //   returns 1: 1242000
  //   example 2: round(3.6);
  //   returns 2: 4
  //   example 3: round(2.835, 2);
  //   returns 3: 2.84
  //   example 4: round(1.1749999999999, 2);
  //   returns 4: 1.17
  //   example 5: round(58551.799999999996, 2);
  //   returns 5: 58551.8

  var m, f, isHalf, sgn; // helper variables
  precision |= 0; // making sure precision is integer
  m = Math.pow(10, precision);
  value *= m;
  sgn = (value > 0) | -(value < 0); // sign of the number
  isHalf = value % 1 === 0.5 * sgn;
  f = Math.floor(value);

  if (isHalf) {
    switch (mode) {
      case 'PHP_ROUND_HALF_DOWN':
        value = f + (sgn < 0); // rounds .5 toward zero
        break;
      case 'PHP_ROUND_HALF_EVEN':
        value = f + (f % 2 * sgn); // rouds .5 towards the next even integer
        break;
      case 'PHP_ROUND_HALF_ODD':
        value = f + !(f % 2); // rounds .5 towards the next odd integer
        break;
      default:
        value = f + (sgn > 0); // rounds .5 away from zero
    }
  }

  return (isHalf ? value : Math.round(value)) / m;
}

 //Format Nilai Uang
 function number_format(a, b, c, d) {
            a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);
            e = a + '';
            f = e.split('.');
            if (!f[0]) {
            f[0] = '0';
            }
            if (!f[1]) {
            f[1] = '';
            }
            if (f[1].length < b) {
            g = f[1];
            for (i=f[1].length + 1; i <= b; i++) {
            g += '0';
            }
            f[1] = g;
            }
            if(d != '' && f[0].length > 3) {
            h = f[0];
            f[0] = '';
            for(j = 3; j < h.length; j+=3) {
            i = h.slice(h.length - j,h.length - j + 3);
            f[0] = d + i +  f[0] + '';
            }
            j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
            f[0] = j + f[0];
            }
            c = (b <= 0) ? '' : c;
            return f[0] + c + f[1]; 
}
    
//Menghitung Umur dari tanggal ditentukan    

function hitungUmur(tanggal){
        var elem = tanggal.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari  = elem[2];
       
        var now=new Date();
        var day =now.getUTCDate();
        var month =now.getUTCMonth()+1;
        var year =now.getYear()+1900;
        
        tahun=year-tahun;
        bulan=month-bulan;
        hari=day-hari;
        
        var jumlahHari;
        var bulanTemp=(month==1)?12:month-1;
        if(bulanTemp==1 || bulanTemp==3 || bulanTemp==5 || bulanTemp==7 || bulanTemp==8 || bulanTemp==10 || bulanTemp==12){
            jumlahHari=31;
        }else if(bulanTemp==2){
            if(tahun % 4==0)
                jumlahHari=29;
            else
                jumlahHari=28;
        }else{
            jumlahHari=30;
        }

        if(hari<=0){
            hari+=jumlahHari;
            bulan--;
        }
        if(bulan<0 || (bulan==0 && tahun!=0)){
            bulan+=12;
            tahun--;
        }
        if (tanggal === '0000-00-00') {
            return "-";
        } else {
            return tahun+' Th ' +bulan+ ' Bl ' +hari+ ' Hr';
        }
}

function datefmysql(tanggal) {
    if (tanggal !== undefined && tanggal !== null && tanggal !== 'null') {
        var elem = tanggal.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari  = elem[2];
        return hari+'/'+bulan+'/'+tahun;
    } else {
        return '';
    }
}

function date2mysql(tgl) {
    var tanggal=tgl;
    var elem = tanggal.split('/');
    var tahun = elem[2];
    var bulan = elem[1];
    var hari  = elem[0];
    return tahun+'-'+bulan+'-'+hari;
}



function datetimefmysql(waktu, status) {
    if ((waktu !== undefined) & (waktu !== null)) {
        var el = waktu.split(' ');
        var tgl= datefmysql(el[0]);
        var tm = el[1].split(':');
        if (status === undefined) {
            return tgl+' '+tm[0]+':'+tm[1];
        } else {
            return tgl;
        }
    } else {
        return '-';
    }
    
}

function datetime2date(waktu) {
    if (waktu !== null) {
        var el = waktu.split(' ');
        var tgl= datefmysql(el[0]);
        return tgl;
    } else {
        return '-';
    }
    
}

function datetime2mysql(waktu){
    var el = waktu.split(' ');
    var tgl= date2mysql(el[0]);
    var tm = el[1].split(':');
    return tgl+' '+tm[0]+':'+tm[1];
}

function Angka(obj) {
        a = obj.value;
        b = a.replace(/[^\d]/g,'');
        c = '';
        lengthchar = b.length;
        j = 0;
        for (i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = b.substr(i-1,1) + '' + c;
                } else {
                        c = b.substr(i-1,1) + c;
                }
        }
        obj.value = c;
}

function FormNum(obj) {
        var a = obj.value;
        b = a.replace(/[^\d]/g,'');
        c = '';
        lengthchar = b.length;
        j = 0;
        for (i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = b.substr(i-1,1) + '.' + c;
                } else {
                        c = b.substr(i-1,1) + c;
                }
        }
        obj.value = c;
}

function Desimal(obj){
    a=obj.value;   
    var reg=new RegExp(/[0-9]+(?:\.[0-9]{0,2})?/g)
    b=a.match(reg,'');
    if(b==null){
        obj.value='';
    }else{
        obj.value=b[0];
    }
    
}

function titikKeKoma(obj){
    var a=obj.toString();
    var b='';
    if(a!=null){
        b=a.replace(/\./g,',');
    }
    return b;
}

function komaKeTitik(obj){
    var a=obj.toString();
    var b='';
    if(a!=null){
        b=a.replace(/\,/g,'.');
    }
    return b;
}

function numberToCurrency(a){
    if (a !== null) {
        a= a.toString();       
        var b = a.replace(/[^\d\,]/g,'');
        var dump = b.split(',');
        var c = '';
        var lengthchar = dump[0].length;
        var j = 0;
        for (var i = lengthchar; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                        c = dump[0].substr(i-1,1) + '.' + c;
                } else {
                        c = dump[0].substr(i-1,1) + c;
                }
        }
        
        if(dump.length>1){
                if(dump[1].length>0){
                        c += ','+dump[1];
                }else{
                        c += ',';
                }
        }
        if (parseFloat(a) < 0) {
            return '-'+c;
        } else {
            return c;
        }
    } else {
        return '0';
    }
}


function currencyToNumber(a){
    var c = 0; var n = 0;
    if (a !== null && a !== undefined) {
        c=a.replace(/\.+/g, '');
        n= c.replace(/,/g, '.');
    }
    return parseFloat(n);
}

function formatNumber(obj) {
    var a = obj.value;
    obj.value = numberToCurrency(a);
}
 

function removeMe(el) {
    var parent = el.parentNode;
    parent.parentNode.removeChild(parent);
}


function removeHtmlTag(strx){
    if(strx.indexOf("<")!=-1) {
        var s = strx.split("<");
        for(var i=0;i<s.length;i++){
        if(s[i].indexOf(">")!=-1){
            s[i] = s[i].substring(s[i].indexOf(">")+1,s[i].length);
        }
    }
    strx = s.join(" ");
    }
    return strx;
}



function parseDate(str) {
  var m = str.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
  return (m) ? new Date(m[3], m[2]-1, m[1]) : null;
}

function isNama(str){
  var reg=/^[a-zA-Z ]+$/g;
  return reg.test(str);
}

function getCookies(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++)
  {
      x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
      y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
      x=x.replace(/^\s+|\s+$/g,"");
      if (x==c_name)
        {
        return unescape(y);
        }
      }
}

function setCookies(c_name,value,exminutes)
{
    var exdate=new Date();
    exdate.setMinutes(exdate.getMinutes()+exminutes,0,0);
    var c_value=escape(value) + ((exminutes==null) ? "" : "; expires="+exdate.toUTCString());
    //alert(c_value+'-->'+exdate.getMinutes()+''+exminutes);
    document.cookie=c_name + "=" + c_value;
}

function checkEmpty(id, value, hasil) {
    if ($('#'+id).val() == '') {
        alert('Data '+value+' tidak boleh kosong !');
        $('#'+id).focus();
        hasil;
    }
}

function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";

        var fixedName = '<%= Request["formName"] %>';
        name = fixedName + name;

        document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function ageByBirth(b){
    // format tgl lahir = Y-m-d
    var format;
    try{
        var elem = b.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari  = elem[2];

        var now=new Date();
        var day =now.getUTCDate();
        var month =now.getUTCMonth()+1;
        var year =now.getYear()+1900;

        tahun=year-tahun;
        bulan=month-bulan;
        hari=day-hari;

        var jumlahHari;
        var bulanTemp=(month==1)?12:month-1;
        if(bulanTemp==1 || bulanTemp==3 || bulanTemp==5 || bulanTemp==7 || bulanTemp==8 || bulanTemp==10 || bulanTemp==12){
            jumlahHari=31;
        }else if(bulanTemp==2){
            if(tahun % 4==0)
                jumlahHari=29;
            else
                jumlahHari=28;
        }else{
            jumlahHari=30;
        }

        if(hari<=0){
            hari+=jumlahHari;
            bulan--;
        }
        if(bulan<0 || (bulan==0 && tahun!=0)){
            bulan+=12;
            tahun--;
        }

        format = tahun+" Tahun "+bulan+" Bulan "+hari+" Hari";
    }catch(err){
        format = "-";
    }
    return format;

}

function pagination(total_data, limit, page,tab){
    var str = '';
    var total_page = Math.ceil(total_data/limit);

    var first = '<li><a onclick="paging(1,'+tab+')">First</a></li>';
    var last = '<li><a onclick="paging('+((total_page===0)?1:total_page)+','+tab+')">Last</a></li>';
    var click_prev = '';
    if (page > 1) {
        click_prev = 'onclick="paging('+(page - 1)+','+tab+')"';
    };
    var prev = '<li><a '+click_prev+'>&laquo;</a></li>';
    
    var click_next = '';
    if (page < total_page) {
        click_next = 'onclick="paging('+(page + 1)+','+tab+')"';
    };
    var next = '<li><a '+click_next+'>&raquo;</a></li>';

    var page_numb = '';
    var act_click = '';
    var start = page - 2;
    var finish = page + 2;
    if (start < 1) {
        start = 1;
    }

    if (finish > total_page){
        finish = total_page;
    }


    for (var p = start; p <= finish; p++) {

        if (p !== page) {
            page_numb += '<li><a onclick="paging('+p+','+tab+')">'+p+'</a></li>';
        }else{
            page_numb += '<li class="active"><a>'+p+'</a></li>';
        }
       
    };


    return '<ul class="pagination pointer">'+first+prev+page_numb+next+last+'</ul>';
}

function page_summary(total_data, total_datapage,limit, page){
    var start = ((page -1) * limit)+1;
  
    var finish = (start -1) + total_datapage;
    if (finish < 1) {
        start = 0;
    };
    var str = '<div class="dataTables_info">Showing '+start+' to '+finish+' of '+total_data+' entries</div>';

    return str;
}

function my_ajax(url,element){
    $.ajax({
        url: url,
        dataType: '',
        success: function( response ) {
            $(element).html(response);
        },
        error: function(e){
            access_failed(e.status);
        }
    });
}

function dc_validation(element, pesan){
    $(element).next().remove();
    $(element).after('<div class="error">'+pesan+'</div>').closest('table td').removeClass('has-success').addClass('has-error');
}

function dc_validation_remove(element){
    $('.error').remove();
    $(element).closest('table td').removeClass('has-error');
}

function get_date_app(){
    var d =  new Date();
    var date = d.getDate();
    var month = d.getMonth();
    month++;

    if (month < 10) {
        month = '0'+String(month);
    };

    if(date < 10){
        date = '0'+String(date);
    }
    return date+'/'+month+'/'+d.getFullYear()+' '+d.getHours()+':'+d.getMinutes();
}

function indo_tgl(date){
     var buf = date.split('-');
     var bulan = ''
     switch (buf[1]) {
        case '01': bulan = 'Januari'; break;
        case '02': bulan = 'Februari'; break;
        case '03': bulan = 'Maret'; break;
        case '04': bulan = 'April'; break;
        case '05': bulan = 'Mei'; break;
        case '06': bulan = 'Juni'; break;
        case '07': bulan = 'Juli'; break;
        case '08': bulan = 'Agustus'; break;
        case '09': bulan = 'September'; break;
        case '10':bulan = 'Oktober'; break;
        case '11':bulan = 'November'; break;
        case '12':bulan = 'Desember'; break;
        
        default:
            break;
        }
     
     return buf[2]+" "+bulan+" "+buf[0];
}

function get_mont_format(date){
     var buf = date.split('/');
     var bulan = ''
     switch (buf[0]) {
        case '1': bulan = 'Januari'; break;
        case '2': bulan = 'Februari'; break;
        case '3': bulan = 'Maret'; break;
        case '4': bulan = 'April'; break;
        case '5': bulan = 'Mei'; break;
        case '6': bulan = 'Juni'; break;
        case '7': bulan = 'Juli'; break;
        case '8': bulan = 'Agustus'; break;
        case '9': bulan = 'September'; break;
        case '10':bulan = 'Oktober'; break;
        case '11':bulan = 'November'; break;
        case '12':bulan = 'Desember'; break;
        
        default:
            break;
        }
     
     return bulan+" "+buf[1];
}

function romanize(num) {
	if (!+num)
		return false;
	var	digits = String(+num).split(""),
		key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
		       "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
		       "","I","II","III","IV","V","VI","VII","VIII","IX"],
		roman = "",
		i = 3;
	while (i--)
		roman = (key[+digits.pop() + (i * 10)] || "") + roman;
	return Array(+digits.join("") + 1).join("M") + roman;
}

function deromanize(str) {
	var	str = str.toUpperCase(),
		validator = /^M*(?:D?C{0,3}|C[MD])(?:L?X{0,3}|X[CL])(?:V?I{0,3}|I[XV])$/,
		token = /[MDLV]|C[MD]?|X[CL]?|I[XV]?/g,
		key = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},
		num = 0, m;
	if (!(str && validator.test(str)))
		return false;
	while (m = token.exec(str))
		num += key[m[0]];
	return num;
}

function show_ajax_indicator(){
    $('body').block({ 
          message: '<span><img src="assets/img/loading-black.gif" /> Loading ...</span>', 
          css: { 
              border: '1px solid #999',
              padding: '5px',
              backgroundColor: '#f4f4f4', 
              '-webkit-border-radius': '4px', 
              '-moz-border-radius': '4px', 
              opacity: 1, 
              width: '120px',
              color: '#000' 
          } 
      }); 
  }

function show_ajax_indicator_with_message(msg){
    $('body').block({ 
          message: '<span><img src="assets/img/loading-black.gif" /> '+msg+'</span>', 
          css: { 
              border: '1px solid #000',
              padding: '5px',
              backgroundColor: '#f4f4f4', 
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: 1, 
              width: 'auto',
              color: '#000' 
          } 
    }); 
}
function hide_ajax_indicator(){
    $('body').unblock(); 
}

function strip_tags(input, allowed) {
  allowed = (((allowed || '') + '')
    .toLowerCase()
    .match(/<[a-z][a-z0-9]*>/g) || [])
    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}