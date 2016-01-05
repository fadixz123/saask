<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class json extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        if (!$this->ion_auth->logged_in()) {
            red('user/login');
        }
    }

    public function index() {
        
    }

    public function pendidikan() {
        $this->output->set_content_type('application/json');
        $this->load->model('model_json');
        $hasil = $this->model_json->pendidikan($this->input->get('q'));
        //echo $this->input->get('q');
    }

    public function nip_nama() {
        $this->output->set_content_type('application/json');
        $this->load->model('model_json');
        $hasil = $this->model_json->nip_nama($this->input->get('q'));
        //echo $this->input->get('q');
    }

}
