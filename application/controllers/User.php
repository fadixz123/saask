<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class user extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->ion_auth->set_error_delimiters('', '');
        $this->form_validation->set_error_delimiters('', '');
        $this->lang->load('auth');
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            $this->view_user();
        } else {
            red('user/login');
        }
    }

    function login() {
        if ($this->ion_auth->logged_in()) {
            redirect();
        }
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                redirect();
            } else {
                // if the login was un-successful
                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect('user/login', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->load->view('base/base-login', $this->data);
        }
    }

    function logout() {
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        redirect();
    }

    function forgot_password() {
        if ($this->ion_auth->logged_in()) {
            redirect();
        }
        // setting validation rules by checking wheather identity is username or email
        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');

        if ($this->form_validation->run() == false) {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->load->view('base/base-forgot_password', $this->data);
        } else {
            $identity = $this->ion_auth->where('email', $this->input->post('email'))->users()->row();

            if (empty($identity)) {
                $this->ion_auth->set_error('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                red('forgot_password');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                red('login');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                red('forgot_password');
            }
        }
    }

    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
                // display the form
                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

                $this->data['user_id'] = $user->id;
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // render
                $this->load->view('base/base-reset_password', $this->data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("user/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('user/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("user/forgot_password", 'refresh');
        }
    }

    public function view_user() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            red('');
        } else {
            $this->load->view('page/v-user');
        }
    }

    public function view_user_ajax() {
        $this->load->library('datatables_ssp'); //panggil library datatables
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        $table = 'ion_users';
        $primaryKey = 'id';
        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'username', 'dt' => 'username'),
            array('db' => 'email', 'dt' => 'email'),
            array(
                'db' => 'id',
                'dt' => 'groups',
                'formatter' => function($n) {
            $group = $this->ion_auth->get_users_groups($n);
            $x = '';
            $c = '';
            foreach ($group->result() as $g) {
                $x.=$c . $g->name;
                $c = ' | ';
            }
            return $x;
        }),
            array('db' => 'first_name', 'dt' => 'first_name'),
            array('db' => 'last_name', 'dt' => 'last_name'),
            array('db' => 'active', 'dt' => 'active'),
        );
        echo json_encode(
                Datatables_ssp::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        );
    }

    public function create_user() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            $tables = $this->config->item('tables', 'ion_auth');
            // validate form input
            $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
            $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
            $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_identity_label'), 'required|is_unique[' . $tables['users'] . '.username]');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
            $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
            $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

            if ($this->input->post()) {
                if ($this->form_validation->run() == true) {
                    $email = strtolower($this->input->post('email'));
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    $additional_data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                    );
                }
                if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
                    echo 'success';
                } else {
                    echo (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                }
            } else {
                $this->load->view('page/v-user_create');
            }
        }
    }

    function edit_user($id) {
        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)) || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            $tables = $this->config->item('tables', 'ion_auth');
            $user = $this->ion_auth->user($id)->row();
            $groups = $this->ion_auth->groups()->result_array();
            $currentGroups = $this->ion_auth->get_users_groups($id)->result();

            // validate form input
            $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
            $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
            $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
            $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');
            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', $this->lang->line('edit_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
            }
            if ($this->input->post()) {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }
                // update the password if it was posted
                if ($this->input->post('password')) {
                    $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
                }

                if ($this->form_validation->run() === TRUE) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                        'email' => $this->input->post('email'),
                    );

                    // update the password if it was posted
                    if ($this->input->post('password')) {
                        $data['password'] = $this->input->post('password');
                    }

                    // Only allow updating groups if user is admin
                    if ($this->ion_auth->is_admin()) {
                        //Update the groups user belongs to
                        $groupData = $this->input->post('groups');

                        $this->ion_auth->remove_from_group('', $id);
                        if (isset($groupData) && !empty($groupData)) {
                            foreach ($groupData as $grp) {
                                $this->ion_auth->add_to_group($grp, $id);
                            }
                        }
                    }

                    // check to see if we are updating the user
                    if ($this->ion_auth->update($user->id, $data)) {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        echo 'success';
                    } else {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        echo $this->ion_auth->errors();
                    }
                } else {
                    echo validation_errors();
                }
            } else {

                // display the edit user form
                $this->data['csrf'] = $this->_get_csrf_nonce();

                // pass the user to the view
                $this->data['groups'] = $groups;
                $this->data['currentGroups'] = $currentGroups;

                $this->data['user'] = $user;
                $this->data['first_name'] = $user->first_name;
                $this->data['last_name'] = $user->last_name;
                $this->data['email'] = $user->email;
                $this->data['company'] = $user->company;
                $this->data['phone'] = $user->phone;

                $this->load->view('page/v-user_edit', $this->data);
            }
        }
    }

    function delete_user($id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            red('');
        } else {
            $delete = $this->ion_auth->delete_user($id);
            echo 'success';
        }
    }

    function create_group() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');
            if (isset($_POST) && !empty($_POST)) {
                if ($this->form_validation->run() == TRUE) {
                    $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
                    if ($new_group_id) {
                        // check to see if we are creating the group
                        // redirect them back to the admin page
                        echo 'success';
                    } else {
                        echo $this->ion_auth->errors();
                    }
                } else {
                    echo validation_errors();
                }
            } else {
                $this->load->view('page/v-user_group_create');
            }
        }
    }

    function edit_group($id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            if (!$id || empty($id)) {
                red('');
            }
            $group = $this->ion_auth->group($id)->row();

            $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

            if (isset($_POST) && !empty($_POST)) {
                if ($this->form_validation->run() === TRUE) {
                    $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                    if ($group_update) {
                        echo 'success';
                    } else {
                        echo $this->ion_auth->errors();
                    }
                } else {
                    echo validation_errors();
                }
            } else {
                $this->data['group'] = $group;
                $readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';
                $this->data['readonly'] = $readonly;
                $this->data['group_name'] = $group->name;
                $this->data['group_description'] = $group->description;

                $this->load->view('page/v-user_group_edit', $this->data);
            }
        }
    }

    function view_group() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $groups = $this->ion_auth->groups()->result_array();

        if (isset($_POST) && !empty($_POST)) {
            
        } else {
            // set the flash data error message if there is one
            // pass the user to the view
            $this->data['groups'] = $groups;
            $this->load->view('page/v-user_group_view', $this->data);
        }
    }

    function view_group_ajax() {
        $this->load->library('datatables_ssp'); //panggil library datatables
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        $table = 'ion_groups';
        $primaryKey = 'id';
        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'name', 'dt' => 'name'),
            array('db' => 'description', 'dt' => 'description'),
        );
        echo json_encode(
                Datatables_ssp::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        );
    }

    function delete_group($id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            red('');
        } else {
            $delete = $this->ion_auth->delete_group($id);
            echo 'success';
        }
    }

    function activate($id, $code = false) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            $activation = $this->ion_auth->activate($id);
            if ($activation) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
    }

    function deactivate($id = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->input->is_ajax_request()) {
            if ($this->input->post()) {
                return show_error('You must be an administrator to view this page.');
            } else {
                red('');
            }
        } else {
            $deactivation = $this->ion_auth->deactivate($id);
            if ($deactivation) {
                echo 'success';
            } else {
                echo 'failed';
            }
        }
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
