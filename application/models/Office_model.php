<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Office_model extends CI_Model
{
    public function count_all()
    {
        return (int) $this->db
            ->count_all('offices');
    }

    public function get_all()
    {
        return $this->db
            ->order_by('name', 'ASC')
            ->get('offices')
            ->result();
    }
}
