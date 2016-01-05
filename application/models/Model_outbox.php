<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_outbox extends CI_Model {

    function select($id_surat) {
        $this->db->where('id_surat', $id_surat);
        $query = $this->db->get('view_outbox');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function view($bagian, $jenis, $start, $finish, $creator) {
        $id = get_user_id();
        if ($bagian != '00') {
            $this->db->where('kd_bagian', $bagian);
        }
        if ($jenis != '00') {
            $this->db->where('kd_jenis', $jenis);
        }
        if ($creator == 'false') {
            $this->db->where('creator', $id);
        }
        $this->db->where('tgl_masuk >=', date('Y-m-d', strtotime($start)));
        $this->db->where('tgl_masuk <=', date('Y-m-d', strtotime($finish)));
        $query = $this->db->get('view_outbox');
        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $result) {
                $return[] = $result;
            }
            return $return;
        } else {
            return false;
        }
    }

    function select_bagian() {
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('surat_bagian');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function select_jenis() {
        $query = $this->db->get('surat_jenis');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function hapus($id_surat) {
        $this->db->where('id_surat', $id_surat);
        //$query = $this->db->delete('surat_outbox');

        $this->db->set('deleted', 1);
        $query = $this->db->update('surat_outbox');

        echo $this->db->last_query();
        if ($query) {
            return 1;
        } else {
            return 0;
        }
    }

    function simpan($newdata, $mode) {
        @$newdata['tgl_masuk'] = date("Y-m-d", strtotime($newdata['tgl_masuk']));
        @$newdata['tgl_surat'] = date("Y-m-d", strtotime($newdata['tgl_surat']));
        @$newdata['creator'] = get_user_id();
        @$newdata['tujuan'] = strtoupper(@$newdata['tujuan']);
        @$newdata['perihal'] = strtoupper(@$newdata['perihal']);
        @$newdata['ptt'] = strtoupper(@$newdata['ptt']);
        @$newdata['user'] = strtoupper(@$newdata['user']);
        $data = $newdata;
        if ($mode == 'tambah') {
            @$newdata['created'] = date('Y-m-d H:i:s', now());
            @$data['no_surat'] = $this->create_no_surat(FALSE, $data['kd_jenis'], $data['kd_bagian'], $data['tgl_surat']);
            $query = $this->db->insert('surat_outbox', $data);
            //echo $this->db->last_query() . '<br/>';
        } else if ($mode == 'edit') {
            $data_surat = explode('/', $data['no_surat']);
            $no_surat = $data_surat[0];
            //echo $no_surat;
            $id_surat = $data['id_surat'];
            unset($data['id_surat']);

            $data['no_surat'] = $this->create_no_surat($no_surat, $data['kd_jenis'], $data['kd_bagian'], $data['tgl_surat']);
            $this->db->where('id_surat', $id_surat);
            $query = $this->db->update('surat_outbox', $data);
            //echo $this->db->last_query() . '<br/>';
        }
        //echo $this->db->last_query();
        if ($query) {
            $hasil = $this->get_info_jenis($data['kd_jenis']);
            return $hasil->count;
        } else {
            return 0;
        }
    }

    function get_info_jenis($kd_jenis) {
        $this->db->where('kd_jenis', $kd_jenis);
        $get = $this->db->get('surat_jenis');
        if ($get->num_rows() > 0) {
            $data = $get->row();
            if ($data->for_year == date('Y')) { //apakah tahun ini sama dengan for_year
                return $data;
            } else { //tahun ini tidak sama dengan for_year
                $this->db->where('kd_jenis', $kd_jenis);
                $this->db->update('surat_jenis', array('count' => 0, 'for_year' => date('Y'))); //reset count ke 0 dan ubah for_year sama dengan tahun ini
                return get_jenis($kd_jenis);
            }
            //echo $this->db->last_query() . '<br/>';
        } else {
            return false;
        }
    }

    function create_no_surat($no_surat, $kd_jenis, $kd_bagian, $tgl_surat) {
        $jenis = $this->get_info_jenis($kd_jenis);
        $count = $jenis->count;
        if ($no_surat == FALSE) {
            $count = $count + 1;
            $no_surat = sprintf('%03d', $count);

            //TAMBAH JUMLAH COUNT DI TABEL SURAT_JENIS
            $no_surat;
        }
        if ($kd_jenis != '' && $kd_bagian != '' && $tgl_surat != '') {
            //MENGHASILKAN KODE INSTANSI DARI DATABASE
            $this->db->where('nama_option', 'kd_instansi');
            $get = $this->db->get('surat_option');
            if ($get->num_rows() > 0) {
                $data = $get->row();
                $kd_instansi = $data->value_option;
                $this->db->where('kd_jenis', $kd_jenis);
                $this->db->update('surat_jenis', array('count' => $count));
            } else {
                $kd_instansi = '-';
            }

            //MENGHASILKAN BULAN ROMAWI
            $bulan = @romawi(sprintf('%02d', date('m', strtotime($tgl_surat))));

            //MENGHASILKAN TAHUN
            $tahun = date('Y', strtotime($tgl_surat));
            $result = $no_surat . "/" . $kd_jenis . "/K/" . $kd_instansi . "/" . $bulan . "/" . $tahun;
            //echo $result;
            return $result;
        }
    }

}
