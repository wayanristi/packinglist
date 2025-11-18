<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datahaspel_model extends CI_Model
{
    private $table = 'data_haspel';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Insert dengan mengembalikan true/false
    public function insert()
    {
        $data = [
            'haspel'  => $this->input->post('haspel'),
            'panjang' => $this->input->post('panjang'),
            'lebar'   => $this->input->post('lebar'),
            'tinggi'  => $this->input->post('tinggi'),
            'm3'      => $this->input->post('m3'),
            'berat'   => $this->input->post('berat')
        ];

        return $this->db->insert($this->table, $data); // kembalikan status true/false
    }

    // Update dengan mengembalikan true/false
    public function update($id)
    {
        $data = [
            'haspel'  => $this->input->post('haspel'),
            'panjang' => $this->input->post('panjang'),
            'lebar'   => $this->input->post('lebar'),
            'tinggi'  => $this->input->post('tinggi'),
            'm3'      => $this->input->post('m3'),
            'berat'   => $this->input->post('berat')
        ];

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data); // kembalikan status true/false
    }

    // Delete dengan mengembalikan true/false
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    // Cari berdasarkan haspel
    public function get_by_standard_desc($angka)
    {
        $angka = trim($angka);
        $this->db->where('haspel', $angka);
        $query = $this->db->get($this->table);
        return $query->row();
    }
}
