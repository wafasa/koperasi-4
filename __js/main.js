// pencarian
function showCari () {
	var cari=document.getElementById('TabelCari');
	var display = document.getElementById('display');
	if(cari.style.display == 'none' || cari.style.display == '' ){
		cari.style.display = 'block';
		display.value = 'style="display:block"';
	}else{
		cari.style.display = 'none';
		display.value = 'style="display:none"';
	}
}

//Check box cek all
function doCheckAll()
{
	
  if (document.frmData.cekAll.checked==true   ){
	  with (document.frmData) {
		for (var i=1; i < elements.length; i++) {
			if (elements[i].type == 'checkbox'){
			   if( elements[i].disabled ){
			   		elements[i].checked = false;
			   }else{
			   		elements[i].checked = true;
			   }
			}
		}
	  }
  }
  else{
		with (document.frmData) {
		for (var i=1; i < elements.length; i++) {
			if (elements[i].type == 'checkbox'){
			   elements[i].checked = false;
			   //unHighlightRow("row"+i);
			}
		}
	  }

  }
}

function PilihBaris(no){
	var baris = $('cbRow'+no).checked;
	if(baris){
		highlightRow("row"+no);
	}else{
		unHighlightRow("row"+no);
	}
}


/* color of highlighted row */
var strRowColor = '#e0ff92';
/* color of highlighted row based on checkbox */
var strRowSelColor = '#e0ff92';
//  Tebel hight light
function highlightRow(strRowID)
{
	// get the descendants TD of row
	var descElmnt = $(strRowID).immediateDescendants();
	// iterate TD and set style for each of it
	descElmnt.each(function(elmnt) {
		if (elmnt.hasClassName('cbChecked')) {
			return;
		}

		elmnt.setStyle({
			backgroundColor: strRowColor
		});
	});
}

/* unhighlight the selected row */
function unHighlightRow(strRowID)
{
	// get the descendants TD of row
	var descElmnt = $(strRowID).immediateDescendants();
	// iterate TD and set style for each of it
	descElmnt.each(function(elmnt) {
		if (elmnt.hasClassName('cbChecked')) {
			return;
		}
		// reset the background color property
		elmnt.setStyle({
			backgroundColor: ''
		});
	});
}

function GoTo(ling){
	return window.location.href = ling;
}

/* untuk slider menu balance */
/* Use to keep the quiz timer on-screen as the user scrolls. */
function movecounter(balancebox) {
    var pos;
    if (window.innerHeight) {
        pos = window.pageYOffset
    } else if (document.documentElement && document.documentElement.scrollTop) {
        pos = document.documentElement.scrollTop
    } else if (document.body) {
        pos = document.body.scrollTop
    }

    if (pos < theTop) {
        pos = theTop;
    } else {
        pos += 100;
    }
    if (pos == old) {
        balancebox.style.top = pos + 'px';
    }
    old = pos;
    temp = setTimeout('movecounter(balancebox)',100);
}

function showBalance(){
	var balancebox = document.getElementById('balance');
	if( balancebox.style.display == 'none'){
		balancebox.style.display = 'block';
	}else{
		balancebox.style.display = 'none';
	}
}

function ajaxFillSelect(str_handler_file, field, str_container_ID)
{

	// fill the select list
    var ajaxUpdate = new Ajax.Updater(
		field,
        str_handler_file,
        {
            method: 'post',
            parameters: '&id=' + str_container_ID 
        });
}

function prosesajax(hal, field, kode){
	var rand = Math.random(9999);
	var url = hal;
	var pars = '?kode=' + kode ;
	var myAjax = new Ajax.Request( url, {
		method: 'post', 
		parameters: pars, 
		onSuccess: function(t) { cekproses(t, field); /* cek apakah proses simpan berhasil*/ }
	} );
}

function cekproses(t, field)
{ //t object is automatically passed
	var pesan = t.responseText;
	$(field).value = pesan;
}


function pilih_all_wajib(jum, wajib){
	var total = parseInt(document.getElementById('total').innerHTML);
	var wajib = parseInt(wajib);
	var pilih = document.getElementById('cekAll').checked;
	if( pilih == true ){
		for(var i=0; i < jum; i++) {
			var cek = document.frmData.elements['id_wajib['+i+']'].checked;
			if( cek == true ){
				total = parseInt(document.getElementById('total').innerHTML);
			}
		}
	}	
	for(var i=0; i < jum; i++) {
		if( pilih == true ){
			document.frmData.elements['id_wajib['+i+']'].checked = true;
			total = total + wajib;
		}else if( pilih == false ){
			document.frmData.elements['id_wajib['+i+']'].checked = false;
			total = total - wajib;
		}
	}
	if( total < 0){ total = 0;}
	document.getElementById('total').innerHTML = total;
	document.getElementById('jum_total').value = total;
}

function pilih_wajib(id, wajib){
	var total = parseInt(document.getElementById('total').innerHTML);
	var wajib = parseInt(wajib);
	var pilih = document.frmData.elements['id_wajib['+id+']'].checked;
	if( pilih == true ){
		total = total + wajib;
	}else if( pilih == false ){
		total = total - wajib;
	}
	if( total < 0){ total = 0;}
	document.getElementById('total').innerHTML = total;
	document.getElementById('jum_total').value = total;
}

function pilih_all_angsuran(jum, angsuran){
	var total = parseInt(document.getElementById('total').innerHTML);
	var angsuran = parseInt(angsuran);
	var pilih = document.getElementById('cekAll2').checked;
	if( pilih == true ){
		for(var i=0; i < jum; i++) {
			var cek = document.frmData.elements['id_ang['+i+']'].checked;
			if( cek == true ){
				total = 0;
			}
		}
	}
	for(var i=0; i < jum; i++) {
		if( pilih == true ){
			document.frmData.elements['id_ang['+i+']'].checked = true;
		}else if( pilih == false ){
			document.frmData.elements['id_ang['+i+']'].checked = false;
		}
	}
	if( document.getElementById('cekAll2').checked == true ){
		total = total + angsuran;
	}if( document.getElementById('cekAll2').checked == false){ 
		total = total - angsuran;
	}
	if( total < 0){ total = 0;}
	document.getElementById('total').innerHTML = total;
	document.getElementById('jum_total').value = total;
}



function pilih_angsuran(id, angsuran){
	var total = parseInt(document.getElementById('total').innerHTML);
	var angsuran = parseInt(angsuran);
	var pilih = document.frmData.elements['id_ang['+id+']'].checked;
	if( pilih == true ){
		total = total + angsuran;
	}if( pilih == false){ 
		total = total - angsuran;
	}
	if( total < 0){ total = 0;}
	document.getElementById('total').innerHTML = total;
	document.getElementById('jum_total').value = total;
}


function cetak_tabungan(ling, kdtab){
	window.location.href= ling + '&kdtab=' + kdtab;
}