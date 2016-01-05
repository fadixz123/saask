<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
    }

    public function index() {
        
    }

    public function ekspedisi($bagian = NULL, $jenis = NULL, $start = NULL, $finish = NULL, $creator = NULL) {
        if ($this->ion_auth->logged_in()) {
            $this->load->model('model_outbox');
            $this->data['data_outbox'] = $this->model_outbox->view($bagian, $jenis, $start, $finish, $creator);
            //echo $bagian . $jenis . $start . $finish . $creator;
            $this->load->view('cetak/fpdf-ekspedisi', $this->data);
        }
    }

    public function outbox() {
        $this->load->model('model_outbox');
        $this->data['data_bagian'] = $this->model_outbox->select_bagian();
        $this->data['data_jenis'] = $this->model_outbox->select_jenis();
        $this->load->view('page/v-report', $this->data);
    }

}
