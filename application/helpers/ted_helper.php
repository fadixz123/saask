<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function identity($string) {
    $identity = '_surat';
    return $string . $identity;
}

///////////////////////////////////////////////////////////////////////////////////////////// EO KGB
function makedate($tahun, $bulan, $tanggal) {
    $test = new DateTime($bulan . '/' . $tanggal . '/' . $tahun);
    return date_format($test, 'Y-m-d'); // 2011-07-01 00:00:00
}

function ucwords_strtolower($string) {
    return ucwords(strtolower($string));
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

function redalert($message, $target) {
    $CI = & get_instance();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('" . $message . "')
    window.location.href='" . $target . "';
    </SCRIPT>");
}

function red($target) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.location.href='" . $target . "';
    </SCRIPT>");
}

function log_event($event, $detail = NULL) {
    $CI = & get_instance();
    $data = array(
        'log_event' => $event,
        'log_user' => $CI->ion_auth->user()->row()->username,
        'log_detail' => $detail,
        'log_time' => date('Y-m-d H:i:s'),
    );
    $CI->db->insert('log', $data);
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

function get_user_name() {
    $CI = & get_instance();
    $result = $CI->ion_auth->user()->row()->username;
    return (string) $result;
}

function get_user_id() {
    $CI = & get_instance();
    $result = $CI->ion_auth->user()->row()->id;
    return (string) $result;
}

?>