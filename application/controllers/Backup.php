<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class backup extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        if (!$this->ion_auth->logged_in()) {
            red('user/login');
        }
    }

    public function index() {
        $this->backup();
    }

    public function backup() {
        $this->load->dbutil();
        $prefs = array(
            'tables' => array(),
            'ignore' => array(),
            'format' => 'txt',
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n"
        );
        // Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        $file_name = identity() . '_' . date("Y-m-d H.i ") . 'WITA.sql';
        write_file('/' . $file_name, $backup);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($file_name, $backup);
    }

}
