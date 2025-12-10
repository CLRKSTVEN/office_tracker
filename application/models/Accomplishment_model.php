<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accomplishment_model extends CI_Model
{
    protected $table = 'staff_accomplishments';

    public function get_for_staff($staffId)
    {
        return $this->db
            ->where('staff_id', (int) $staffId)
            ->order_by('start_date', 'DESC')
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function count_for_staff($staffId)
    {
        return (int) $this->db
            ->where('staff_id', (int) $staffId)
            ->count_all_results($this->table);
    }

    public function count_public_for_staff($staffId)
    {
        return (int) $this->db
            ->where('staff_id', (int) $staffId)
            ->where('is_public', 1)
            ->count_all_results($this->table);
    }

    public function count_all()
    {
        return (int) $this->db
            ->count_all($this->table);
    }

    public function find($id, $staffId)
    {
        if ($id <= 0 || $staffId <= 0) {
            return null;
        }

        return $this->db
            ->where('id', (int) $id)
            ->where('staff_id', (int) $staffId)
            ->get($this->table)
            ->row();
    }

    public function create($payload)
    {
        $payload['created_at'] = date('Y-m-d H:i:s');
        $payload['updated_at'] = $payload['created_at'];
        return $this->db->insert($this->table, $payload);
    }

    public function update($id, $staffId, $payload)
    {
        $payload['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $payload, array(
            'id' => (int) $id,
            'staff_id' => (int) $staffId,
        ));
    }

    public function delete($id, $staffId)
    {
        return $this->db->delete($this->table, array(
            'id' => (int) $id,
            'staff_id' => (int) $staffId,
        ));
    }

    public function categories_for_staff($staffId)
    {
        return $this->db
            ->select('category')
            ->where('staff_id', (int) $staffId)
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->group_by('category')
            ->order_by('category', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Fetch latest accomplishments across all staff for admin overview.
     */
    public function recent($limit = 5)
    {
        $limit = max(1, (int) $limit);

        return $this->db
            ->select('a.*, s.first_name, s.last_name, s.position_title')
            ->from($this->table . ' a')
            ->join('staff s', 's.staff_id = a.staff_id', 'left')
            ->order_by('a.updated_at', 'DESC')
            ->order_by('a.start_date', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }
}
