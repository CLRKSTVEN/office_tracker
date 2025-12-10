<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Login_model', 'Staff_model', 'Office_model', 'Accomplishment_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    /**
     * Login screen (staff/admin).
     * Uses application/views/home_page.php
     */
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            return redirect('dashboard');
        }

        $this->load->view('home_page');
    }

    /**
     * Handle login form POST.
     */
    public function auth()
    {
        $username = trim($this->input->post('username', TRUE));
        $password = (string) $this->input->post('password', TRUE);

        if ($username === '' || $password === '') {
            $this->session->set_flashdata('auth_error', 'Username and password are required.');
            return redirect('login');
        }

        $user = $this->Login_model->authenticate($username, $password);

        if (!$user) {
            $this->session->set_flashdata('auth_error', 'Invalid username or password.');
            return redirect('login');
        }

        $fullName = trim(
            ($user->first_name ?? '') . ' ' .
                ($user->middle_name ? substr($user->middle_name, 0, 1) . '. ' : '') .
                ($user->last_name ?? '')
        );

        $sessionData = [
            'user_id'   => $user->id,
            'staff_id'  => $user->staff_id,
            'username'  => $user->username,
            'full_name' => $fullName,
            'role'      => $user->role,
            'logged_in' => TRUE,
        ];

        $this->session->set_userdata($sessionData);
        return redirect('dashboard');
    }

    public function dashboard()
    {
        $this->_require_login();

        $role = $this->session->userdata('role') ?: 'staff';
        $staffId = (int) $this->session->userdata('staff_id');

        $stats = [
            'total_staff'        => $this->Staff_model->count_active(),
            'total_offices'      => $this->Office_model->count_all(),
            'total_accomplishments' => $this->Accomplishment_model->count_all(),
            'my_accomplishments' => $this->Accomplishment_model->count_for_staff($staffId),
            'public_accomplishments' => $this->Accomplishment_model->count_public_for_staff($staffId),
        ];

        $accomplishments = $staffId > 0
            ? $this->Accomplishment_model->get_for_staff($staffId)
            : [];
        $categories = $staffId > 0
            ? $this->Accomplishment_model->categories_for_staff($staffId)
            : [];

        $data = [
            'stats'          => $stats,
            'accomplishments' => $accomplishments,
            'accomplishment_categories' => array_filter(array_map(function ($row) {
                return $row->category;
            }, $categories)),
            'office_list'    => $this->Office_model->get_all(),
            'dashboard_nav'  => [
                ['target' => '#statsSection', 'label' => 'Overview', 'count' => $stats['total_staff']],
                ['target' => '#accomplishmentsSection', 'label' => 'My accomplishments', 'count' => $stats['my_accomplishments']],
                ['target' => '#formSection', 'label' => 'Quick entry', 'count' => max(1, $stats['public_accomplishments'])],
            ],
            'can_manage_accomplishments' => $staffId > 0,
        ];

        $view = $role === 'admin' ? 'dashboard_admin' : 'dashboard_staff';
        $this->load->view($view, $data);
    }

    public function save_accomplishment()
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to save accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('is_public', 'Visibility', 'integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            return redirect('dashboard#formSection');
        }

        $startDate = $this->input->post('start_date', TRUE);
        $endDate   = $this->input->post('end_date', TRUE);
        $payload = [
            'staff_id'   => $staffId,
            'title'      => $this->input->post('title', TRUE),
            'category'   => $this->input->post('category', TRUE),
            'location'   => $this->input->post('location', TRUE),
            'description'=> $this->input->post('description', TRUE),
            'start_date' => $startDate !== '' ? $startDate : null,
            'end_date'   => $endDate !== '' ? $endDate : null,
            'is_public'  => (int)$this->input->post('is_public', TRUE),
        ];

        $this->Accomplishment_model->create($payload);
        $this->session->set_flashdata('success', 'Accomplishment saved.');
        redirect('dashboard');
    }

    public function update_accomplishment()
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to update accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $this->form_validation->set_rules('id', 'Accomplishment', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            return redirect('dashboard#formSection');
        }

        $id = (int)$this->input->post('id', TRUE);
        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Accomplishment not found.');
            return redirect('dashboard');
        }

        $startDate = $this->input->post('start_date', TRUE);
        $endDate   = $this->input->post('end_date', TRUE);
        $payload = [
            'title'      => $this->input->post('title', TRUE),
            'category'   => $this->input->post('category', TRUE),
            'location'   => $this->input->post('location', TRUE),
            'description'=> $this->input->post('description', TRUE),
            'start_date' => $startDate !== '' ? $startDate : null,
            'end_date'   => $endDate !== '' ? $endDate : null,
            'is_public'  => (int)$this->input->post('is_public', TRUE),
        ];

        $this->Accomplishment_model->update($id, $staffId, $payload);
        $this->session->set_flashdata('success', 'Accomplishment updated.');
        redirect('dashboard');
    }

    public function delete_accomplishment($id = null)
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to remove accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $id = (int)$id;
        if ($id <= 0) {
            $this->session->set_flashdata('error', 'Invalid accomplishment.');
            return redirect('dashboard');
        }

        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Accomplishment not found.');
            return redirect('dashboard');
        }

        $this->Accomplishment_model->delete($id, $staffId);
        $this->session->set_flashdata('success', 'Accomplishment deleted.');
        redirect('dashboard');
    }


    /**
     * Logout.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
     * Show staff registration form.
     */
    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            // Already logged in, go to dashboard (optional)
            return redirect('dashboard');
        }

        // Load offices + addresses for dropdowns
        $data['offices']  = $this->db->get('offices')->result();
        $data['addresses'] = $this->db->get('settings_address')->result();

        $this->load->view('staff_register', $data);
    }

    /**
     * Handle staff registration POST.
     * Creates record in staff + users (role=staff).
     */
    public function register_save()
    {
        // Basic validation
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name',  'Last Name',  'required|trim');
        $this->form_validation->set_rules('position_title', 'Position', 'required|trim');
        $this->form_validation->set_rules('office_id', 'Office', 'required|integer');
        $this->form_validation->set_rules('address_id', 'Address', 'required|integer');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            // Reload form with errors
            $data['offices']   = $this->db->get('offices')->result();
            $data['addresses'] = $this->db->get('settings_address')->result();
            return $this->load->view('staff_register', $data);
        }

        // Collect staff fields
        $staffData = [
            'staff_code'     => NULL, // you can generate or assign later
            'first_name'     => $this->input->post('first_name', TRUE),
            'middle_name'    => $this->input->post('middle_name', TRUE),
            'last_name'      => $this->input->post('last_name', TRUE),
            'suffix'         => $this->input->post('suffix', TRUE),
            'position_title' => $this->input->post('position_title', TRUE),
            'office_id'      => (int)$this->input->post('office_id'),
            'address_id'     => (int)$this->input->post('address_id'),
            'photo'          => NULL, // upload later if you want
            'short_bio'      => $this->input->post('short_bio', TRUE),
            'is_active'      => 1,
            'is_public'      => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        $username = $this->input->post('username', TRUE);
        $password = (string)$this->input->post('password', TRUE);

        $this->db->trans_start();

        // Insert staff
        $this->db->insert('staff', $staffData);
        $staff_id = $this->db->insert_id();

        // Insert user (role = staff)
        $userData = [
            'staff_id'      => $staff_id,
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'staff',
            'status'        => 1,
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('users', $userData);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('auth_error', 'Registration failed. Please try again.');
            return redirect('register');
        }

        // Success: redirect to login
        $this->session->set_flashdata('auth_error', 'Registration successful. You may now log in.');
        redirect('login');
    }

    /**
     * Simple guard.
     */
    private function _require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
}
