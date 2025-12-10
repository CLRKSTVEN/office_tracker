<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff_model extends CI_Model
{
    public function count_active()
    {
        return (int) $this->db
            ->where('is_active', 1)
            ->count_all_results('staff');
    }

    public function find($staffId)
    {
        return $this->db
            ->get_where('staff', array('staff_id' => (int) $staffId))
            ->row();
    }

    public function search($limit = 20, $offset = 0)
    {
        return $this->db
            ->order_by('last_name', 'ASC')
            ->order_by('first_name', 'ASC')
            ->get('staff', $limit, $offset)
            ->result();
    }

    public function recent($limit = 6)
    {
        $limit = max(1, (int) $limit);

        return $this->db
            ->order_by('created_at', 'DESC')
            ->order_by('staff_id', 'DESC')
            ->get('staff', $limit)
            ->result();
    }
}
