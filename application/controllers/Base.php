<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class base extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        if (!$this->ion_auth->logged_in()) {
            red('user');
        }
        $this->lang->load('auth');
    }

    public function index() {
        $this->home();
    }

    public function home() {
        $data['base_title'] = lang('base_title');
        $data['base_organisasi'] = lang('base_company');;
        $data['base_title_singkatan'] = lang('base_title_short');
        $data['base_organisasi_singkatan'] = lang('base_company_short');
        $data['user'] = $this->ion_auth->user()->row();
        $this->load->view('base/base-index', $data);
    }

    public function maintenance() {
        $data['header'] = 'Maintenance';
        $this->load->view('page/v-maintenance', $data);
    }

}
