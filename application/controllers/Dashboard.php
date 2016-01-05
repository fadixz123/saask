<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        if (!$this->ion_auth->logged_in()) {
            red('user/login');
        }
    }

    public function index() {
        $data['header'] = 'Dashboard';
        $data['tema'] = 'default';
        $this->load->model('model_dashboard');
        $this->load->view('page/v-maintenance', $data);
    }

}
