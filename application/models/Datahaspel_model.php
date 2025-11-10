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

    public function insert()
    {
        $data = [
            'haspel' => $this->input->post('haspel'),
            'panjang' => $this->input->post('panjang'),
            'lebar' => $this->input->post('lebar'),
            'tinggi' => $this->input->post('tinggi'),
            'm3' => $this->input->post('m3'),
            'berat' => $this->input->post('berat')
        ];
        $this->db->insert($this->table, $data);
    }

    public function update($id)
    {
        $data = [
            'haspel' => $this->input->post('haspel'),
            'panjang' => $this->input->post('panjang'),
            'lebar' => $this->input->post('lebar'),
            'tinggi' => $this->input->post('tinggi'),
            'm3' => $this->input->post('m3'),
            'berat' => $this->input->post('berat')
        ];
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
    }
    // /application/models/Datahaspel_model.php
public function get_by_standard_desc($angka) {
    $angka = trim($angka);
    // lakukan pencarian persis di kolom 'haspel'
    $this->db->where('haspel', $angka);
    $query = $this->db->get($this->table); // $this->table = 'data_haspel'
    return $query->row();
}

}
