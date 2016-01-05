<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class outbox extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->load->model('model_outbox');

        if (!$this->ion_auth->logged_in()) {
            red('user/login');
        }
        $this->per_preview = 5;
        $this->per_page = 10;
        $this->start_rows = $this->uri->segment('4') == '' ? 0 : $this->uri->segment('4');

        $config['per_page'] = $this->per_page;
        $this->pagination_config = $config;
    }

    public function index() {
        $this->load->view('page/v-outbox');
    }

    public function view_ajax() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            //panggil library datatables
            $this->load->library('datatables_ssp');

            //atur nama tablenya disini
            $table = 'view_outbox_' . $this->uri->segment(3);

            // Table's primary key
            $primaryKey = 'id_surat';

            $columns = array(
                array('db' => 'id_surat', 'dt' => 'id_surat'),
                array('db' => 'created', 'dt' => 'created'),
                array('db' => 'tgl_masuk', 'dt' => 'tgl_masuk'),
                array('db' => 'tgl_surat', 'dt' => 'tgl_surat'),
                array('db' => 'no_surat', 'dt' => 'no_surat'),
                array('db' => 'perihal', 'dt' => 'perihal'),
                array('db' => 'tujuan', 'dt' => 'tujuan'),
                array('db' => 'nama_bagian', 'dt' => 'nama_bagian'),
                array('db' => 'ptt', 'dt' => 'ptt'),
                array('db' => 'deleted', 'dt' => 'deleted'),
                array('db' => 'request', 'dt' => 'request'),
                array('db' => 'ptt', 'dt' => 'ptt'),
                array('db' => 'nama_nip', 'dt' => 'nama_nip'),
                array('db' => 'creator', 'dt' => 'creator'),
                array('db' => 'user_name', 'dt' => 'user_name'),
                array('db' => 'pos', 'dt' => 'pos'),
                array(
                    'db' => 'pos',
                    'dt' => 'pos',
                    'formatter' => function( $d ) {
                return $d == 1 ? 'pos' : 'langsung';
            }),
            );

            // MySQL connection information
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            echo json_encode(
                    Datatables_ssp::simple($_GET, $sql_details, $table, $primaryKey, $columns)
            );
        }
    }

    public function hapus() {
        $this->model_outbox->hapus($this->input->post('id'));
    }

    public function simpan() {
        $data = $this->input->post();
        $mode = $this->input->post('mode');
        $id_surat = $this->input->post('id_surat');
        unset($data['mode']);
        $data['creator'] = get_user_id();
        $simpan = $this->model_outbox->simpan($data, $mode);
        if ($simpan > 0) {
            echo $mode == 'tambah' ? 'Berhasil menambah pegawai' : 'Data pegawai berhasil diubah';
        }
    }

    /* public function edit() {
      $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
      $data['bagian'] = $this->model_outbox->select_bagian();
      $this->load->view('page/v-outbox-surat_edit', $data);
      } */

    public function request() {
        $data['jenis_panel'] = 'default';
        $data['kd_jenis'] = $this->uri->segment(3);
        $data['jenis_surat'] = $this->uri->segment(4);
        $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
        $data['bagian'] = $this->model_outbox->select_bagian();
        $this->load->view('page/v-outbox-surat_request', $data);
    }

    public function simpan_request() {
        $data = $this->input->post();
        $jumlah = $this->input->post('jumlah');
        $pemesan = $this->input->post('nama');
        unset($data['jumlah']);
        unset($data['nama']);
        $data['creator'] = get_user_id();
        for ($n = $jumlah; $n > 0; $n--) {
            $simpan = $this->model_outbox->simpan($data, 'tambah');
        }
        if ($simpan > 0) {
            echo $pemesan . ' telah memesan sebanyak ' . $jumlah . ' nomor surat (' . ($simpan - $jumlah + 1) . '-' . $simpan . ')';
        }
    }

    public function surat_biasa() { //kode surat SB
        $data['jenis_surat'] = 'surat-biasa';
        $data['jenis_panel'] = 'red';
        $data['kd_jenis'] = 'SB';
        $data['surat'] = 'surat_biasa';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function surat_perintah() { //kode surat SP
        $data['jenis_surat'] = 'surat-perintah';
        $data['jenis_panel'] = 'green';
        $data['kd_jenis'] = 'SP';
        $data['surat'] = 'surat_perintah';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function surat_keputusan() { //kode surat SK
        $data['jenis_surat'] = 'surat-keputusan';
        $data['jenis_panel'] = 'primary';
        $data['kd_jenis'] = 'SK';
        $data['surat'] = 'surat_keputusan';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function surat_perintah_tugas() { //kode surat SPT
        $data['jenis_surat'] = 'surat-perintah-tugas';
        $data['jenis_panel'] = 'danger';
        $data['kd_jenis'] = 'SPT';
        $data['surat'] = 'surat_perintah_tugas';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function nota_dinas() { //kode surat SPT
        $data['jenis_surat'] = 'nota-dinas';
        $data['jenis_panel'] = 'success';
        $data['kd_jenis'] = 'ND';
        $data['surat'] = 'nota_dinas';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function memo_dinas() { //kode surat SPT
        $data['jenis_surat'] = 'memo-dinas';
        $data['jenis_panel'] = 'info';
        $data['kd_jenis'] = 'MD';
        $data['surat'] = 'memo_dinas';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

    public function surat_kuasa_khusus() { //kode surat SPT
        $data['jenis_surat'] = 'surat-kuasa-khusus';
        $data['jenis_panel'] = 'warning';
        $data['kd_jenis'] = 'SKK';
        $data['surat'] = 'surat_kuasa_khusus';
        if ($this->uri->segment('3') == 'view') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            //print_r($data['agenda']);
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_view', $data);
        } else if ($this->uri->segment('3') == 'edit') {
            $data['agenda'] = $this->model_outbox->select($this->uri->segment(4));
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat_edit', $data);
        } else {
            $data['bagian'] = $this->model_outbox->select_bagian();
            $this->load->view('page/v-outbox-surat', $data);
        }
    }

}
