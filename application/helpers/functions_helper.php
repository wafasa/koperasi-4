<?php

function sql_count($table, $q = ""){
    $sql = "select count(id) as count from ".$table." where id is not null ".$q;
    return $sql;
}

function sql_count_auto($table, $q = ''){
    $sql = "select count(id) as count from ".$table." where ".$q;
    return $sql;
}

function pembulatan_seratus($angka) {
    $kelipatan = 100;
    $sisa = $angka % $kelipatan;
    if ($sisa != 0) {
        $kekurangan = $kelipatan - $sisa;
        $hasilBulat = $angka + $kekurangan;
        return ceil($hasilBulat);
    } else {
        return ceil($angka);
    }
}

function dayBetweenDates($awal, $akhir){
    $datetime1 = date_create($awal);
    $datetime2 = date_create($akhir);
    $interval = date_diff($datetime1, $datetime2);
    $day = 0;
    if ($interval->h >= 1) {
        $day = $interval->days + 1;
    }else{
        $day = $interval->days;
    }
    return $day;
}
function inttocur($jml) {
    $int = number_format($jml, 0, '', '.');
    return $int;
}

function rupiah($jml) {
    $int = number_format(ceil($jml), 0, '', '.');
    return $int;
}

function currency($jml) {
    $int = number_format(ceil($jml), 0, '', '.');
    if ($jml < 0) {
        return '('.str_replace('-', '', $int).')';
    }
    else if (($jml === NULL) or ($jml === 0) or ($jml === '0') or ($jml === '')) {
        return '0';
    } else {
        return $int;
    }
}

function form_hidden($name, $value = NULL, $attr = NULL) {
    return '<input type=hidden name="'.$name.'" value="'.$value.'" '.$attr.' />';
}

function rupiah2($jml) {
    $int = number_format($jml, 0, '', '.');
    return $int;
}

function get_date_from_dt($dt) {
    $var = explode(" ", $dt);
    return $var[0];
}

function get_day($date){
    $datetime = new DateTime($date);
    $day = $datetime->format('N');
    $hari = '';
    switch ($day) {
        case '1': $hari = 'Senin'; break;
        case '2': $hari = 'Selasa'; break;
        case '3': $hari = 'Rabu'; break;
        case '4': $hari = 'Kamis'; break;
        case '5': $hari = 'Jumat'; break;
        case '6': $hari = 'Sabtu'; break;
        case '7': $hari = 'Minggu'; break;
        
        default:
            # code...
            break;
    }

    return $hari;
}

function get_date_format($date){
     $datetime = new DateTime($date);
     $month = $datetime->format('m');
     $bulan = '';
     switch ($month) {
        case '01': $bulan = 'Januari'; break;
        case '02': $bulan = 'Februari'; break;
        case '03': $bulan = 'Maret'; break;
        case '04': $bulan = 'April'; break;
        case '05': $bulan = 'Mei'; break;
        case '06': $bulan = 'Juni'; break;
        case '07': $bulan = 'Juli'; break;
        case '08': $bulan = 'Agustus'; break;
        case '09': $bulan = 'September'; break;
        case '10': $bulan = 'Oktober'; break;
        case '11': $bulan = 'November'; break;
        case '12': $bulan = 'Desember'; break;
        
        default:
            # code...
            break;
        }
     
     return $datetime->format('d')." ".$bulan." ".$datetime->format('Y');
}

function datetime($dt) {
    if ($dt != NULL and $dt != '0000-00-00 00:00:00') {
        $var = explode(" ", $dt);
        $var1 = explode("-", $var[0]);
        $var2 = "$var1[2]/$var1[1]/$var1[0]";
        return $var2 . " " . substr($var[1], 0, 5);
    } else {
        return '-';
    }
}

function datetimefmysql($dt, $time = NULL) {
    $var = explode(" ", $dt);
    $var1 = explode("-", $var[0]);
    $var2 = "$var1[2]/$var1[1]/$var1[0]";
    if ($time != NULL) {
        return $var2 . ' ' . $var[1];
    } else {
        return $var2;
    }
}

function datetime2mysql($dt) {
    $var = explode(" ", $dt);
    $var1 = explode("/", $var[0]);
    $var2 = "$var1[2]-$var1[1]-$var1[0]";

    return $var2 . " " . $var[1];
}

function datetimetomysql($dt) {
    // $dt = 2013-03-06 00:00:00
    $var = explode(" ", $dt);
    $date = explode("-", $var[0]);
    $time = explode(":", $var[1]);

    return $date[2] . "/" . $date[1] . "/" . $date[0] . " " . $time[0] . ":" . $time[1];
}

function dateconvert($tgl) {
    $new = explode('-', $tgl);
    if ($new[1] == '01') {
        $month = 'Januari';
    }
    if ($new[1] == '02') {
        $month = 'Februari';
    }
    if ($new[1] == '03') {
        $month = 'Maret';
    }
    if ($new[1] == '04') {
        $month = 'April';
    }
    if ($new[1] == '05') {
        $month = 'Mei';
    }
    if ($new[1] == '06') {
        $month = 'Juni';
    }
    if ($new[1] == '07') {
        $month = 'Juli';
    }
    if ($new[1] == '08') {
        $month = 'Agustus';
    }
    if ($new[1] == '09') {
        $month = 'September';
    }
    if ($new[1] == '10') {
        $month = 'Oktober';
    }
    if ($new[1] == '11') {
        $month = 'November';
    }
    if ($new[1] == '12') {
        $month = 'Desember';
    }
    return $new[2] . " " . $month . " " . $new[0];
}

function indo_tgl($tgl) {
    //$x = explode(' ', $tgl);
    $baru = explode("-", $tgl);
    if ($baru[1] == '01')
        $mo = "Januari";
    if ($baru[1] == '02')
        $mo = "Februari";
    if ($baru[1] == '03')
        $mo = "Maret";
    if ($baru[1] == '04')
        $mo = "April";
    if ($baru[1] == '05')
        $mo = "Mei";
    if ($baru[1] == '06')
        $mo = "Juni";
    if ($baru[1] == '07')
        $mo = "Juli";
    if ($baru[1] == '08')
        $mo = "Agustus";
    if ($baru[1] == '09')
        $mo = "September";
    if ($baru[1] == '10')
        $mo = "Oktober";
    if ($baru[1] == '11')
        $mo = "November";
    if ($baru[1] == '12')
        $mo = "Desember";
    $new = "$baru[2] $mo $baru[0]";

    return $new;
}

function indo_time($time, $jam = false){
    // time = Y-m-d H:i:s
    $split = explode(' ', $time);
    $data = indo_tgl($split[0])." ";
    if ($jam = true) {
        $data .= $split[1];
    }
    return $data;
}

function indo_tgl_graph($tgl) {
    $baru = explode("-", $tgl);
    if ($baru[1] == '01')
        $mo = "Jan";
    if ($baru[1] == '02')
        $mo = "Feb";
    if ($baru[1] == '03')
        $mo = "Mar";
    if ($baru[1] == '04')
        $mo = "Apr";
    if ($baru[1] == '05')
        $mo = "Mei";
    if ($baru[1] == '06')
        $mo = "Jun";
    if ($baru[1] == '07')
        $mo = "Jul";
    if ($baru[1] == '08')
        $mo = "Agu";
    if ($baru[1] == '09')
        $mo = "Sep";
    if ($baru[1] == '10')
        $mo = "Okt";
    if ($baru[1] == '11')
        $mo = "Nov";
    if ($baru[1] == '12')
        $mo = "Des";
    $new = "$baru[2] $mo";

    return $new;
}

function tampil_bulan($tgl) {
    $tgl = explode('-', $tgl);
    if ($tgl[1] == '01')
        $mo = "Januari";
    if ($tgl[1] == '02')
        $mo = "Februari";
    if ($tgl[1] == '03')
        $mo = "Maret";
    if ($tgl[1] == '04')
        $mo = "April";
    if ($tgl[1] == '05')
        $mo = "Mei";
    if ($tgl[1] == '06')
        $mo = "Juni";
    if ($tgl[1] == '07')
        $mo = "Juli";
    if ($tgl[1] == '08')
        $mo = "Agustus";
    if ($tgl[1] == '09')
        $mo = "September";
    if ($tgl[1] == '10')
        $mo = "Oktober";
    if ($tgl[1] == '11')
        $mo = "November";
    if ($tgl[1] == '12')
        $mo = "Desember";

    return $mo.' '.$tgl[0];
}

function datetopg($tgl) {
    $new = null;
    $tgl = explode("/", $tgl);
    if (empty($tgl[2]))
        return "";
    $new = "$tgl[2]-$tgl[1]-$tgl[0]";
    return $new;
}

function date2mysql($tgl) {
    $new = null;
    $tgl = explode("/", $tgl);
    if (empty($tgl[2]))
        return "";
    $new = "$tgl[2]-$tgl[1]-$tgl[0]";
    return $new;
}

function datefmysql($tgl) {
    if ($tgl == '' || $tgl == null) {
        return "";
    } else {
        $tgl = explode("-", $tgl);
        $new = $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0];
        return $new;
    }
}

function datefrompg($tgl) {
    if ($tgl == '' || $tgl == null) {
        return "";
    } else {
        $tgl = explode("-", $tgl);
        $new = $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0];
        return $new;
    }
}

function createUmur($tgl1) {

    $tgl2 = date("Y-m-d");
    $sql = mysql_query("select datediff('$tgl2', '$tgl1') as tahun");
    $rows = mysql_fetch_array($sql);
    return floor($rows['tahun'] / 365);
}

function is_anak($tgl_lahir){
    $umur = createUmur($tgl_lahir);

    if ($umur <= 12) {
        $is_anak = true;
    }else{
        $is_anak = false;
    }

    return $is_anak;
}

function hitungUmur($tgl, $label = null) {
    $tanggal = explode("-", $tgl);
    $tahun = $tanggal[0];
    $bulan = $tanggal[1];
    $hari = $tanggal[2];

    $thn_label = "Th"; $bln_label = "Bl"; $hari_label = "Hr";
    if ($label !== null) {
        $thn_label = "Thn";
        $bln_label = "Bln";
        $hari_label = "Hari";
    }
    
    if ($tahun != '0000') {
    
        $day = date('d');
        $month = date('m');
        $year = date('Y');

        $tahun = $year - $tahun;
        $bulan = $month - $bulan;
        $hari = $day - $hari;

        $jumlahHari = 0;
        $bulanTemp = ($month == 1) ? 12 : $month - 1;
        if ($bulanTemp == 1 || $bulanTemp == 3 || $bulanTemp == 5 || $bulanTemp == 7 || $bulanTemp == 8 || $bulanTemp == 10 || $bulanTemp == 12) {
            $jumlahHari = 31;
        } else if ($bulanTemp == 2) {
            if ($tahun % 4 == 0)
                $jumlahHari = 29;
            else
                $jumlahHari = 28;
        }else {
            $jumlahHari = 30;
        }

        if ($hari <= 0) {
            $hari+=$jumlahHari;
            $bulan--;
        }
        if ($bulan < 0 || ($bulan == 0 && $tahun != 0)) {
            $bulan+=12;
            $tahun--;
            if ($bulan >= 12) {
                $tahun++;
                $bulan = 0;
            }
        }
        $result = $tahun . " ".$thn_label." " . $bulan . " ".$bln_label." " . $hari . " ".$hari_label;
    } else {
        $result = "-";
    }
    return $result;
}

function currencyToNumber($a) {
    $var        = str_replace(".", "", $a);
    $real_var   = str_replace(",", ".", $var);
    return $real_var;
}

function int_to_money($nominal) {
    return number_format($nominal, 0, '', '.');
}

function get_umur($tgl_lahir) {
    $tglawal = date('Y');  // Format: Tanggal/Bulan/Tahun -> 12 Desember 2010
    $year1 = explode('-', $tgl_lahir);
    $selisih = $tglawal - $year1[0];
    return $selisih;
}

function paging($jmldata, $dataPerPage, $tab = NULL) {

    $showPage = NULL;
    ob_start();
    echo "
        <div class='body-page'>";
    if (!empty($_GET['page'])) {
        $noPage = $_GET['page'];
    } else {
        $noPage = 1;
    }

    $dataPerPage = $dataPerPage;
    $offset = ($noPage - 1) * $dataPerPage;


    $jumData = $jmldata;
    $jumPage = ceil($jumData / $dataPerPage);
    $get = $_GET;
    if ($jumData > $dataPerPage) {
        $onclick = null;
        if ($noPage > 1) {
            $get['page'] = ($noPage - 1);
            $onclick = "onClick=location.href='" .  base_url(''). "'";
        }
        echo "<span class='page-prev' $onclick>prev</span>";
        for ($page = 1; $page <= $jumPage; $page++) {
            if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) {
                if (($showPage == 1) && ($page != 2))
                    echo "...";
                if (($showPage != ($jumPage - 1)) && ($page == $jumPage))
                    echo "...";
                if ($page == $noPage)
                    echo " <span class='noblock'>" . $page . "</span> ";
                else {
                    $get['page'] = $page;

                    if ($tab != NULL) {
                        $get['tab'] = $tab;
                    }

                    echo " <a class='block' href='" . base_url('') . "'>" . $page . "</a> ";
                }
                $showPage = $page;
            }
        }
        $onClick = null;
        if ($noPage < $jumPage) {
            $get['page'] = ($noPage + 1);
            $onClick = "onClick=location.href='" . base_url('') . "'";
        }
        echo "<span class='page-next' $onClick>next</span>";
    }
    echo "</div>";

    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function generate_get_parameter($get, $addArr = array(), $removeArr = array()) {
    if ($addArr == null)
        $addArr = array();
    foreach ($removeArr as $rm) {
        unset($get[$rm]);
    }
    $link = "";
    $get = array_merge($get, $addArr);
    foreach ($get as $key => $val) {
        if ($link == null) {
            $link.="$key=$val";
        }else
            $link.="&$key=$val";
    }
    return $link;
}

function form_type_button($value = null, $attr = null) {
    $val = null;
    if ($value != '') {
        $val = $value;
    }
    $atrib = null;
    if ($attr != null) {
        $atrib = $attr;
    }

    return '<input type="button" value="' . $val . '" "' . $atrib . '" />';
}

function get_duration($date1, $date2) {
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);
    $durasi = $date1->diff($date2);
    return array('day' => $durasi->d, 'hour' => $durasi->h, 'minute'=>$durasi->i);
}

function get_last_id($table, $kolom) {
    $CI = & get_instance();
    $sql = "select max($kolom)+1 as id from $table";
    $id = $CI->db->query($sql)->row();
    return isset($id->id) ? $id->id : '1';
}

function save_jurnal_debet($kode_rekening, $nama_transaksi, $debet, $id_transaksi = NULL, $keterangan = NULL, $id_rekening = NULL) {
    $CI = & get_instance();
    if ($id_rekening !== NULL) {
        $id_rek = $id_rekening;
    } else {
        $get= $CI->db->query("select id from dc_rekening where kode = '$kode_rekening'")->row();
        $id_rek = isset($get->id)?$get->id:NULL;
    }
    $data_jurnal = array(
        'id_transaksi' => ($id_transaksi !== NULL)?$id_transaksi:NULL,
        'transaksi' => $nama_transaksi,
        'ket_transaksi' => ($keterangan !== NULL)?$keterangan:'',
        'id_rekening' => $id_rek,
        'debet' => currencyToNumber($debet)
    );
    $CI->db->insert('dc_jurnal', $data_jurnal);
    if ($CI->db->trans_status() === FALSE) {
        $CI->db->trans_rollback();
    }
}

function save_jurnal_kredit($kode_rekening, $nama_transaksi, $kredit, $id_transaksi = NULL, $keterangan = NULL, $id_rekening = NULL) {
    $CI = & get_instance();
    if ($id_rekening !== NULL) {
        $id_rek = $id_rekening;
    } else {
        $get= $CI->db->query("select id from dc_rekening where kode = '$kode_rekening'")->row();
        $id_rek = isset($get->id)?$get->id:NULL;
    }
    $data_jurnal = array(
        'id_transaksi' => ($id_transaksi !== NULL)?$id_transaksi:NULL,
        'transaksi' => $nama_transaksi,
        'ket_transaksi' => ($keterangan !== NULL)?$keterangan:'',
        'id_rekening' => $id_rek,
        'kredit' => currencyToNumber($kredit)
    );
    $CI->db->insert('dc_jurnal', $data_jurnal);
    if ($CI->db->trans_status() === FALSE) {
        $CI->db->trans_rollback();
    }
}

function update_posted_status($table, $status) {
    $CI = & get_instance();
    $CI->db->update($table, array('posted' => $status));
    if ($CI->db->trans_status() === FALSE) {
        $CI->db->trans_rollback();
    }
}

function get_last_no_rm() {
    $CI = & get_instance();
    $sql = "select max(no_rm) as id from pasien";
    $no = $CI->db->query($sql)->row();
    $number = $no->id+1;
    $width = 6;
    $padded = str_pad((string)$number, $width, "0", STR_PAD_LEFT);
    return $padded;
    
}

function padded($nomor) {
    $padded = str_pad((string)$nomor, 4, "0", STR_PAD_LEFT);
    return $padded;
}

function get_last_repackage_id($table, $kolom, $trans) {
    $CI = & get_instance();
    $sql = "select max($kolom)+1 as id from $table where transaksi_jenis = '$trans'";
    $id = $CI->db->query($sql)->row();
    return isset($id->id) ? $id->id : '1';
}

function header_excel($namaFile) {
//    header("Pragma: public");
//    header("Expires: 0");
//    header("Cache-Control: must-revalidate, post-check=0,
//            pre-check=0");
//    header("Content-Type: application/force-download");
//    header("Content-Type: application/octet-stream");
//    header("Content-Type: application/download");
//
//    // header untuk nama file
//    header("Content-type: application/vnd.ms-excel"); 
//    header("Content-Disposition: attachment;
//            filename=" . $namaFile . "");
//    header("Content-Transfer-Encoding: binary ");
    $par_name=$namaFile;
    $fi = $par_name;
    $extns="";

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$fi.$extns);
    header('Cache-Control: max-age=0');

    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
}

function header_word($namafile) {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=".$namafile.".doc");

//    echo "<html>";
//    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
//    echo "<body>";
//    echo "<b>My first document</b>";
//    echo "</body>";
//    echo "</html>";

}

function pagination($jmldata, $dataPerPage, $klik, $tab = NULL, $search = NULL) {
    /*
     * Parameter '$search' dalam bentuk string , bisa json string atau yang lain
     * contoh 1#nama_barang#nama_pabrik
     */

    $showPage = NULL;
    ob_start();
    echo '<ul class="pagination">';
    if (!empty($klik)) {
        $noPage = $klik;
    } else {
        $noPage = 1;
    }

    $dataPerPage = $dataPerPage;


    $jumData = $jmldata;
    $jumPage = ceil($jumData / $dataPerPage);
    $get = $_GET;
    if ($jumData > $dataPerPage) {
        $onclick = null;
        if ($noPage > 1) {
            $get['page'] = ($noPage - 1);
            $onclick = $klik;
        }
        $prev = null;
        $last = ' class="last-block" ';
        if ($klik > 1) {
            $prev = "onClick=\"pagination(" . ($klik - 1) . "," . $tab . ", '" . $search . "')\" ";
        }
        echo '<li><span '.$prev.'>&laquo;</span></li>';
        for ($page = 1; $page <= $jumPage; $page++) {
            if ((($page >= $noPage - 1) && ($page <= $noPage + 1)) || ($page == 1) || ($page == $jumPage)) {
                if (($showPage === 1) && ($page !== 2)) {
                echo "<li>...</li>"; }
                if (($showPage !== ($jumPage - 1)) && ($page === $jumPage)) {
                echo "<li>...</li>"; }
                if ($page === $noPage) {
                echo " <li class='active'><span class='noblock'>" . $page . "</span></li> "; }
                else {
                    $get['page'] = $page;
                    if ($tab != NULL) {
                        $get['tab'] = $tab;
                    }
                    $next = "onClick=\"pagination(" . $page . "," . $tab . ", '" . $search . "')\" ";
                    //echo " <a class='block' href='?" . generate_get_parameter($get) . "'>" . $page . "</a> ";
                    if ($page == $jumPage) {
                        echo '<li ' . $next . '><span class="block">' . $page . '</span></li>';
                    } else {
                        echo '<li ' . $next . '><span class="block">' . $page . '</span></li>';
                    }
                }
                $showPage = $page;
            }
        }
        $next = null;
        if ($klik < $jumPage) {
            $next = "onClick=\"pagination(" . ($klik + 1) . "," . $tab . ", '" . $search . "')\" ";
        }
        echo '<li><span '.$next.'>&raquo;</span></li>';
    }
    echo "</ul>";

    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function range_year_start_from_one_year_ago() {
    $x = mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1);
    return date("Y-m-d", $x);
}

function range_hours_between_two_dates($date_input, $date_trans) {
    $val = explode(" ", $date_input);
    $date = explode("-", $val[0]);
    $time = explode(":", $val[1]);

    $vals = explode(" ", $date_trans);
    $dates = explode("-", $vals[0]);
    $times = explode(":", $vals[1]);

    $now = mktime($times[0], 0, 0, $dates[1], $dates[2], $dates[0]);
    $input = mktime($time[0], 0, 0, $date[1], $date[2], $date[0]);
    $selisih = ($now - $input) / 3600;
    return $selisih;
}

function tanggal_format($tgl) {
    $data = explode("-", $tgl);
    return $data[2] . " " . tampil_bulan($tgl) . " " . $data[0];
}

function cek_karakter($teks) {

    $kata_kotor = array("Persediaan");
    $hasil = 0;
    $jml_kata = count($kata_kotor);
 
    for ($i=0;$i<$jml_kata;$i++) {
        if (stristr($teks,$kata_kotor[$i])) { 
            $hasil=1;    
        }
    }
    return $hasil;
}

function createRange($startDate, $endDate) {
    $tmpDate = new DateTime($startDate);
    $tmpEndDate = new DateTime($endDate);

    $outArray = array();
    do {
        $outArray[] = $tmpDate->format('Y-m-d');
    } while ($tmpDate->modify('+1 day') <= $tmpEndDate);

    return $outArray;
}



function get_safe($parameter){
    $CI = & get_instance();
    $string = $CI->input->get($parameter);
    $quote = str_replace("'", "`", $string);
    $hasil = str_replace(array("?", "\\"), "", $quote);
    return $hasil;
}

function post_safe($parameter){
    $CI = & get_instance();
    $string = $CI->input->post($parameter);
    $quote = str_replace("'", "`", $string);
    $hasil = str_replace(array("?", "\\"), "", $quote);
    return $hasil;
}

function birthByAge($umur){
    $today = date('Y-m-d');
    $exp = explode('-', $today);

    return ((int)$exp[0] - $umur).'-'.$exp[1].'-'.$exp[2];
}

function terbilang($x){
    $abil = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS");
    if ($x < 12) {
    return " " . $abil[$x]; }
    elseif ($x < 20) {
    return Terbilang($x - 10) . " BELAS"; }
    elseif ($x < 100) {
    return Terbilang($x / 10) . " PULUH" . Terbilang($x % 10); }
    elseif ($x < 200) {
    return " SERATUS" . Terbilang($x - 100); }
    elseif ($x < 1000) {
    return Terbilang($x / 100) . " RATUS" . Terbilang($x % 100); }
    elseif ($x < 2000) {
    return " SERIBU" . Terbilang($x - 1000); }
    elseif ($x < 1000000) {
    return Terbilang($x / 1000) . " RIBU" . Terbilang($x % 1000); }
    elseif ($x < 1000000000) {
    return Terbilang($x / 1000000) . " JUTA" . Terbilang($x % 1000000); }
}

function titik_titik($loop){
    $titik = "";

    for ($i=0; $i < $loop; $i++) { 
        $titik .= ". ";
    }

    return $titik;
}

function ubah_kelipatan_tiga($jml){
    $row = floor($jml / 3);

    if ($jml % 3 > 0) {
        $row++;
    }
    return $row;
}

function get_kode_agama($nama) { //untuk ditampilkan di lembar RM
    $result = "";
    if ($nama === 'Islam') {
        $result = 1;
    }
    if ($nama === 'Protestan') {
        $result = 2;
    }
    if ($nama === 'Katolik') {
        $result = 3;
    }
    if ($nama === 'Hindu') {
        $result = 4;
    }
    if ($nama === 'Budha') {
        $result = 5;
    }
    if ($nama === 'Lain-lain') {
        $result = 6;
    }
    return $result;
}

function formatcurrency($floatcurr, $curr = "IDR"){
        $currencies['ARS'] = array(2,',','.');          //  Argentine Peso
        $currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
        $currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
        $currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
        $currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
        $currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
        $currencies['BRL'] = array(2,',','.');          //  Brazilian Real
        $currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
        $currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
        $currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,'','.');           //  Chilean Peso
        $currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2,',','.');          //  Colombian Peso
        $currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
        $currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
        $currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
        $currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
        $currencies['DKK'] = array(2,',','.');          //  Danish Krone
        $currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
        $currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
        $currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
        $currencies['ATS'] = array(2,',','.');          //  Euro
        $currencies['BEF'] = array(2,',','.');          //  Euro
        $currencies['DEM'] = array(2,',','.');          //  Euro
        $currencies['EEK'] = array(2,',','.');          //  Euro
        $currencies['ESP'] = array(2,',','.');          //  Euro
        $currencies['EUR'] = array(2,',','.');          //  Euro
        $currencies['FIM'] = array(2,',','.');          //  Euro
        $currencies['FRF'] = array(2,',','.');          //  Euro
        $currencies['GRD'] = array(2,',','.');          //  Euro
        $currencies['IEP'] = array(2,',','.');          //  Euro
        $currencies['ITL'] = array(2,',','.');          //  Euro
        $currencies['LUF'] = array(2,',','.');          //  Euro
        $currencies['NLG'] = array(2,',','.');          //  Euro
        $currencies['PTE'] = array(2,',','.');          //  Euro
        $currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
        $currencies['ISK'] = array(0,'','.');           //  Iceland Krona
        $currencies['INR'] = array(2,'.',',');          //  Indian Rupee
        $currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
        $currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,'',',');           //  Japan, Yen
        $currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
        $currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
        $currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
        $currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
        $currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
        $currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
        $currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
        $currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
        $currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
        $currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
        $currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
        $currencies['OMR'] = array(3,'.',',');          //  Rial Omani
        $currencies['RON'] = array(2,',','.');          //  Romania, New Leu
        $currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2,',','.');          //  Russian Ruble
        $currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
        $currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
        $currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,'',',');           //  South Korea, Won
        $currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2,',','.');          //  Swedish Krona
        $currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc 
        $currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
        $currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
        $currencies['AED'] = array(2,'.',',');          //  UAE Dirham
        $currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2,'.',',');          //  US Dollar
        $currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
        $currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,'','.');           //  Viet Nam, Dong
        $currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar

        if ($curr == "INR"){    
            return formatinr($floatcurr);
        } else {
            return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]);
        }
    }
    
    function formatinr($input){
        //CUSTOM FUNCTION TO GENERATE ##,##,###.##
        $dec = "";
        $pos = strpos($input, ".");
        if ($pos === false){
            //no decimals   
        } else {
            //decimals
            $dec = substr(round(substr($input,$pos),2),1);
            $input = substr($input,0,$pos);
        }
        $num = substr($input,-3); //get the last 3 digits
        $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
        while(strlen($input) > 0) //loop the process - further get digits 2 by 2
        {
            $num = substr($input,-2).",".$num;
            $input = substr($input,0,-2);
        }
        return $num . $dec;
    }
    
    function remove_accent($str) 
{ 
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
  return str_replace($a, $b, $str); 
} 
    
    function post_slug($str) { 
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
        array('', '-', ''), remove_accent($str))); 
    }
    
    function triwulan($bulan) {
        $triwulan = '';
        if ($bulan >= 1 and $bulan <= 3) {
            $triwulan = 'Pertama';
        }
        if ($bulan >= 4 and $bulan <= 6) {
            $triwulan = 'Kedua';
        }
        if ($bulan >= 7 and $bulan <= 9) {
            $triwulan = 'Ketiga';
        }
        if ($bulan >= 10 and $bulan <= 12) {
            $triwulan = 'Keempat';
        }
        return $triwulan;
    }
    
    function get_mac_address(){
        /*
        * Getting MAC Address using PHP
        * Md. Nazmul Basher
        */

        ob_start(); // Turn on output buffering
        system('ifconfig'); //Execute external program to display output
        system('ipconfig /all'); //Execute external program to display output
        $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer

        $findme = "Physical";
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $mac=substr($mycom,($pmac+36),17); // Get Physical Address

        return $mac;
    }

?>
