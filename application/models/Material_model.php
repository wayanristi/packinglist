<?php
class Material_model extends CI_Model {

    public function get_all() {
        return $this->db->get('material')->result();
    }

    public function insert($data) {
        return $this->db->insert('material', $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where('material', ['id' => $id])->row();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('material', $data);
    }

    public function delete($id) {
        return $this->db->delete('material', ['id' => $id]);
    }

    // 🟢 Tambahkan fungsi ini
    public function get_by_description($desc) {
        $this->db->like('deskripsi_material', $desc);
        return $this->db->get('material')->row_array();
    }
}
