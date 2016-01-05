<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function cek_login() {
    $CI = & get_instance();
    if (is_login() && $CI->agent->is_referral()) {
        
    } else {
        red(base_url());
        die();
    }
}

function get_ip_addr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function log_event($event, $detail = NULL) {
    $CI = & get_instance();
    $data = array(
        'log_event' => $event,
        'log_user' => get_user_name(),
        'log_detail' => $detail,
        'log_time' => date('Y-m-d H:i:s'),
    );
    $CI->db->insert('log', $data);
}

function is_login() {
    $CI = & get_instance();
    if ($CI->session->userdata(identity('1sl0g1n')) != 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function redalert($message, $target) {
    $CI = & get_instance();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('" . $message . "')
    window.location.href='" . $target . "';
    </SCRIPT>");
}

function red($target) {
    $CI = & get_instance();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.location.href='" . $target . "';
    </SCRIPT>");
}

function get_user_profile() {
    $CI = & get_instance();
    return (string) $CI->session->userdata(identity('user_profile'));
}

function get_user_name() {
    $CI = & get_instance();
    return (string) $CI->session->userdata(identity('user_name'));
}

function get_user_id() {
    $CI = & get_instance();
    return (string) $CI->session->userdata(identity('user_id'));
}

function get_user_level() {
    $CI = & get_instance();
    return (string) $CI->session->userdata(identity('user_level'));
}

function safe_encode($string) {
    $CI = & get_instance();
    $enc = $CI->encrypt->encode($string);
    return str_replace(array('+', '/', '='), array('-', '_', '~'), $enc);
}

function safe_decode($string) {
    $CI = & get_instance();
    $dec = str_replace(array('-', '_', '~'), array('+', '/', '='), $string);
    return $CI->encrypt->decode($dec);
}

function identity($string) {
    $identity = '_kep';
    return $string . $identity;
}

function order_by($order, $by) {
    $CI = & get_instance();
    $uri_order = $CI->uri->segment(4);
    $uri_by = $CI->uri->segment(3);
    if ($uri_order == 'asc') {
        $new_order = 'desc';
    } else if ($uri_order == 'desc') {
        $new_order = 'asc';
    } else {
        $new_order = $order;
    }
    return $by . "/" . $new_order;
}

function convert_pangkat($input) {
    $CI = & get_instance();
    $CI->db->select('pangkat');
    $CI->db->where('id_golongan', $input);
    $get = $CI->db->get('pegawai_golongan');
    //echo $CI->db->last_query()." ";
    if ($get->num_rows() > 0) {
        $data = $get->row();
        return $data->pangkat;
    } else {
        return false;
    }
}

function convert_golongan($input) {
    if (is_numeric($input)) {
        $x = '';
        switch (substr($input, 0, 1)) {
            case 1: $x.='I';
                break;
            case 2: $x.='II';
                break;
            case 3: $x.='III';
                break;
            case 4: $x.='IV';
                break;
            default : break;
        }
        switch (substr($input, 1, 1)) {
            case 1: $x.='/a';
                break;
            case 2: $x.='/b';
                break;
            case 3: $x.='/c';
                break;
            case 4: $x.='/d';
                break;
            case 5: $x.='/e';
                break;
            default : break;
        }
    } else {
        $n = explode('/', $input);
        $x = '';
        switch (strtoupper($n[0])) {
            case 'I':$x.=1;
                break;
            case 'II':$x.=2;
                break;
            case 'III':$x.=3;
                break;
            case 'IV':$x.=4;
                break;
            default : break;
        }switch (strtolower($n[1])) {
            case 'a':$x.=1;
                break;
            case 'b':$x.=2;
                break;
            case 'c':$x.=3;
                break;
            case 'd':$x.=4;
                break;
            default : break;
        }
    }
    return $x;
}

///////////////////////////////////////////////////////////////////////////////////////////// KGB
function hitung_mks($tmt_cpns, $tgl_hitung = 'now', $pmk_thn = 0, $pmk_bln = 0, $tgl_pmk = null) { //menghitung masa kerja seluruhnya
    $x_date = strtotime($tmt_cpns);
    if (strtolower($tgl_hitung) == 'now' || $tgl_hitung == '') {
        $y_date = strtotime('now');
    } else {
        $y_date = strtotime($tgl_hitung);
    }

    $thn_awal = date('Y', $x_date);
    $thn_akhir = date('Y', $y_date);
    $bln_awal = date('m', $x_date);
    $bln_akhir = date('m', $y_date);
    $pmk_thn = (strtotime($tgl_hitung) > strtotime($tgl_pmk)) ? $pmk_thn : 0;
    $pmk_bln = (strtotime($tgl_hitung) > strtotime($tgl_pmk)) ? $pmk_bln : 0;
    $thn_tot = ($thn_akhir - $thn_awal) + $pmk_thn;
    $bln_tot = ($bln_akhir - $bln_awal) + $pmk_bln;

    if ($bln_tot > 11) {
        $thn_tot+=1;
        $bln_tot-=12;
    } else if ($bln_tot < 0) {
        $thn_tot-=1;
        $bln_tot+=12;
    }
    $data = (object) array('tahun' => $thn_tot, 'bulan' => $bln_tot);
    return $data;
}

function hitung_mkg($gol_awal, $gol_akhir, $mks) { //menghitung masa kerja golongan
    $bulan = $mks->bulan;
    $tahun = $mks->tahun;
    //PENAMBAHAN MASA KERJA FIKTIF//
    if ($gol_awal == 12 || $gol_awal == 13 || $gol_awal == 14) {
        $tahun+=3;
    } else if ($gol_awal == 22 || $gol_awal == 23 || $gol_awal == 24) {
        $tahun+=3;
    }
    //PENYESUAIAN PINDAH GOLONGAN//
    if (substr($gol_awal, 0, 1) == 1 && substr($gol_akhir, 0, 1) == 2) {
        $tahun-=6;
    } else if (substr($gol_awal, 0, 1) == 1 &&
            (substr($gol_akhir, 0, 1) == 3 || substr($gol_akhir, 0, 1) == 4)) {
        $tahun-=11;
    } else if (substr($gol_awal, 0, 1) == 2 &&
            (substr($gol_akhir, 0, 1) == 3 || substr($gol_akhir, 0, 1) == 4)) {
        $tahun-=5;
    }
    $data = (object) array('tahun' => $tahun, 'bulan' => $bulan);
    return $data;
}

function tgl_kgb_baru($gol, $mkg) {
    $bulan = $mkg->bulan;
    $tahun = $mkg->tahun;
    $x_tahun = 0;
    $x_bulan = 0;
    if ($gol == 21 && $tahun < 1) {
        $x_tahun = 0;
        $x_bulan = 12 - $bulan;
    } else {
        if ($gol == 12 || $gol == 13 || $gol == 14 || $gol == 21 || $gol == 22 || $gol == 23 || $gol == 24) {
            $x_tahun = $tahun % 2;
            $x_bulan = 12 - $bulan;
        } else {
            $x_tahun = ($tahun + 1) % 2;
            $x_bulan = 12 - $bulan;
        }
    }
    $tahun = $x_tahun;
    $bulan = $x_bulan;

    $do_tahun = date("Y");
    $do_tahun+=$tahun;

    $do_bulan = date("m");
    $do_bulan+=$bulan;

    if ($do_bulan > 12) {
        $do_tahun+=1;
        $do_bulan-=12;
    }

    $data = makedate($do_tahun, $do_bulan, 1);
    return $data;
}

function mkg_kgb_baru($gol, $mkg) {
    $bulan = $mkg->bulan;
    $tahun = $mkg->tahun;
    $x_tahun = 0;
    $x_bulan = 0;
    if ($gol == 21 && $tahun < 1) {
        $x_tahun = 0;
        $x_bulan = 12 - $bulan;
    } else {
        if ($gol == 12 || $gol == 13 || $gol == 14 || $gol == 21 || $gol == 22 || $gol == 23 || $gol == 24) {
            $x_tahun = $tahun % 2;
            $x_bulan = 12 - $bulan;
        } else {
            $x_tahun = ($tahun + 1) % 2;
            $x_bulan = 12 - $bulan;
        }
    }
    $tahun += $x_tahun;
    $bulan += $x_bulan;

    if ($bulan >= 12) {
        $tahun+=1;
        $bulan-=12;
    }
    $data = (object) array('tahun' => $tahun, 'bulan' => $bulan);
    return $data;
}

function mkg_kgb_lama($gol, $mkg) {
    $bulan = $mkg->bulan;
    $tahun = $mkg->tahun;
    $x_tahun = 0;
    $x_bulan = 0;
    if ($gol == 21 && $tahun < 1) {
        $x_tahun = 0;
        $x_bulan = 12 - $bulan;
    } else {
        if ($gol == 12 || $gol == 13 || $gol == 14 || $gol == 21 || $gol == 22 || $gol == 23 || $gol == 24) {
            $x_tahun = $tahun % 2;
            $x_bulan = 12 - $bulan;
        } else {
            $x_tahun = ($tahun + 1) % 2;
            $x_bulan = 12 - $bulan;
        }
    }
    $tahun += $x_tahun;
    $bulan += $x_bulan;

    if ($bulan >= 12) {
        $tahun+=1;
        $bulan-=12;
    }
    $tahun-=2;
    $data = (object) array('tahun' => $tahun, 'bulan' => $bulan);
    return $data;
}

///////////////////////////////////////////////////////////////////////////////////////////// EO KGB
function makedate($tahun, $bulan, $tanggal) {
    $test = new DateTime($bulan . '/' . $tanggal . '/' . $tahun);
    return date_format($test, 'Y-m-d'); // 2011-07-01 00:00:00
}

function get_gaji_pokok($golongan, $mkg, $tahun) {
    $CI = & get_instance();
    $CI->db->select_max('gaji_pokok');
    if ($tahun != '') {
        $CI->db->where('tahun_buat <=', $tahun);
    }
    $CI->db->where('golongan', $golongan);
    $CI->db->where('mkg <=', $mkg);
    $get = $CI->db->get('pegawai_gaji_pokok');
//echo $CI->db->last_query()." ";
    if ($get->num_rows() > 0) {
        $data = $get->row();
        return $data->gaji_pokok;
    } else {
        return false;
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////PENDIDIKAN

function convert_pendidikan($kddik) {
    switch ($kddik) {
        case 12 : return 'SLTP Kejuruan';
            break;
        case 17 : return 'SLTA Kejuruan';
            break;
        case 05 : return 'Sekolah Dasar';
            break;
        case 10 : return 'SLTP';
            break;
        case 15 : return 'SLTA';
            break;
        case 20 : return 'Diploma I';
            break;
        case 25 : return 'Diploma II';
            break;
        case 30 : return 'Diploma III';
            break;
        case 35 : return 'Diploma IV';
            break;
        case 40 : return 'S-1';
            break;
        case 45 : return 'S-2';
            break;
        case 50 : return 'S-3';
            break;
        case 18 : return 'SLTA Keguruan';
            break;
    }
}

function nama_pendidikan($kode_pendidikan) {
    $CI = & get_instance();
    $CI->db->select_max('nama_dik');
    $CI->db->where('kode_dik', $kode_pendidikan);
    $get = $CI->db->get('pegawai_pendidikan');
//echo $CI->db->last_query()." ";
    if ($get->num_rows() > 0) {
        $data = $get->row();
        return $data->nama_dik;
    } else {
        return false;
    }
}

function detect_pendidikan($kode_dik) {

    $ci = & get_instance();
    $ci->db->where('kode_dik', $kode_dik);
    $get = $ci->db->get('pegawai_pendidikan');
    if ($get->num_rows() > 0) {
        foreach ($get->result() as $result) {
            $data = $result->nama_dik;
        }
    } else {
        return false;
    }
    $hasil = explode(' ', $data);
    $hasil = explode('/', $hasil[0]);
    $hasil = $hasil[0];
    if ($hasil == 'D-I' || $hasil == 'D-II' || $hasil == 'D-III' || $hasil == 'D-IV' ||
            $hasil == 'S-1' || $hasil == 'S-2' || $hasil == 'S-3') {

        if ($hasil == 'D-I') {
            $hasil = '20';
        } else if ($hasil == 'D-II') {
            $hasil = '25';
        } else if ($hasil == 'D-III') {
            $hasil = '30';
        } else if ($hasil == 'D-IV') {
            $hasil = '35';
        } else if ($hasil == 'S-1') {
            $hasil = '40';
        } else if ($hasil == 'S-2') {
            $hasil = '45';
        } else if ($hasil == 'S-3') {
            $hasil = '50';
        }
    } else {
        if ($kode_dik <= 2000000) {
            $hasil = '05'; //Sekolah Dasar
        } else if ($kode_dik <= 2200000) {
            $hasil = '10'; //SLTA
        } else if ($kode_dik <= 3000000) {
            //$hasil = '12'; //SLTP Kejuruan
            $hasil = '10'; //SLTP
        } else if ($kode_dik <= 3100000) {
            $hasil = '15'; //SLTA
        } else if ($kode_dik <= 3200000) {
            //$hasil = '17'; //SLTA Kejuruan
            $hasil = '15'; //SLTA
        } else if ($kode_dik <= 3300000) {
            //$hasil = '18'; //SLTA Keguruan
            $hasil = '15'; //SLTA
        } else if ($kode_dik <= 3400000) {
            //$hasil = '17'; //SLTA Kejuruan
            $hasil = '15'; //SLTA
        } else if ($kode_dik <= 4000000) {
            $hasil = '20'; //D-I
        } else if ($kode_dik <= 4100000) {
            $hasil = '25'; //D-II
        } else if ($kode_dik <= 5000000) {
            $hasil = '30'; //D-III
        } else if ($kode_dik <= 5100000) {
            //$hasil = '35'; //D-IV
            $hasil = '40'; //S-1
        } else if ($kode_dik <= 7000000) {
            $hasil = '40'; //S-1
        } else {
            $hasil = '45'; //S-2
        }
    }
    return $hasil;
}

function ucwords_strtolower($string) {
    return ucwords(strtolower($string));
}

//
function split_nip($nip) {
    $nip = preg_replace('/^.{8}/', "$0 ", $nip);
    $nip = preg_replace('/^.{15}/', "$0 ", $nip);
    $nip = preg_replace('/^.{17}/', "$0 ", $nip);
    return $nip;
}

function romawi($N) {
    $c = 'IVXLCDM';
    for ($a = 5, $b = $s = ''; $N; $b++, $a^=7) {
        for ($o = $N % $a, $N = $N / $a ^ 0; $o--; $s = $c[$o > 2 ? $b + $N - ($N&=-2) + $o = 1 : $b] . $s) {
            
        }
    }
    return $s;
}

function convert_tanggal($format, $tanggal = "now", $bahasa = "id") {
    $en = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Jan", "Feb",
        "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

    $id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",
        "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "Nopember", "Desember");

    return str_replace($en, $$bahasa, date($format, strtotime($tanggal)));
}

function terbilang_rupiah($x) {
    $terbilang = terbilang($x);
    return trim($terbilang . " rupiah");
}

function angka_rupiah($x) {
    return "Rp. " . number_format($x, "0", ",", ".") . ",-";
}

function terbilang($x) {
    $ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh",
        "delapan", "sembilan", "sepuluh", "sebelas");
    if ($x <= 0) {
        return "";
    } elseif ($x < 12) {
        return " " . $ambil[$x];
    } elseif ($x < 20) {
        return Terbilang($x - 10) . "belas";
    } elseif ($x < 100) {
        return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
    } elseif ($x < 200) {
        return " seratus" . Terbilang($x - 100);
    } elseif ($x < 1000) {
        return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
    } elseif ($x < 2000) {
        return " seribu" . Terbilang($x - 1000);
    } elseif ($x < 1000000) {
        return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
    } elseif ($x < 1000000000) {
        return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
    }
}

function replace_filename($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9]/', "_", $filename);
    return $filename;
}
?>