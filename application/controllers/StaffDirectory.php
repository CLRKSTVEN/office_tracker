<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StaffDirectory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'html']);
    }

    /**
     * Landing page:
     * - Shows a list of public/active staff (for ticker/flashing later).
     * - Supports simple search by name/position and office.
     */
    public function index()
    {
        // Safely read GET values as strings
        $rawSearch = $this->input->get('q', TRUE);
        $rawOffice = $this->input->get('office', TRUE);

        $search = trim((string) $rawSearch);
        $office = trim((string) $rawOffice);

        $this->db->select('
        s.staff_id,
        s.first_name,
        s.middle_name,
        s.last_name,
        s.position_title,
        s.photo,
        s.short_bio,
        o.name      AS office_name,
        o.location  AS office_location,
        a.Province,
        a.City,
        a.Brgy
    ');
        $this->db->from('staff s');
        $this->db->join('offices o', 'o.id = s.office_id', 'left');
        $this->db->join('settings_address a', 'a.AddID = s.address_id', 'left');
        $this->db->where('s.is_active', 1);
        $this->db->where('s.is_public', 1);

        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('s.first_name', $search);
            $this->db->or_like('s.last_name', $search);
            $this->db->or_like('s.position_title', $search);
            $this->db->group_end();
        }

        if ($office !== '') {
            $this->db->where('o.id', (int) $office);
        }

        $this->db->order_by('s.last_name', 'ASC');
        $staffList = $this->db->get()->result();

        $offices = $this->db->get('offices')->result();

        $data = [
            'staff'   => $staffList,
            'offices' => $offices,
            'search'  => $search,
            'office'  => $office,
        ];

        $this->load->view('staff_landing', $data);
    }

    /**
     * Optional: /directory/profile/{id}
     * Shows single staff profile + accomplishments.
     */
    public function profile($staff_id = null)
    {
        $staff_id = (int) $staff_id;
        if ($staff_id <= 0) {
            show_404();
        }

        $this->db->select('
            s.*,
            o.name      AS office_name,
            o.location  AS office_location,
            a.Province,
            a.City,
            a.Brgy
        ');
        $this->db->from('staff s');
        $this->db->join('offices o', 'o.id = s.office_id', 'left');
        $this->db->join('settings_address a', 'a.AddID = s.address_id', 'left');
        $this->db->where('s.staff_id', $staff_id);
        $this->db->where('s.is_active', 1);
        $this->db->where('s.is_public', 1);

        $staff = $this->db->get()->row();
        if (!$staff) {
            show_404();
        }

        $this->db->from('staff_accomplishments');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('is_public', 1);
        $this->db->order_by('start_date', 'DESC');
        $accomplishments = $this->db->get()->result();

        $data = [
            'staff'           => $staff,
            'accomplishments' => $accomplishments,
        ];

        // You can create staff_profile.php later for nicer layout
        $this->load->view('staff_profile', $data);
    }
}
