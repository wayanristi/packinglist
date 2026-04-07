<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_model extends CI_Model {

    protected $table = 'tabel_excel';

    // =========================
    // BASIC DATA
    // =========================

    public function find($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    // =========================
    // RELASI ONE TO MANY
    // =========================

    // tabel_excel HAS MANY spk_drawing
    public function drawings($id_excel)
    {
        return $this->db
            ->where('id_data', $id_excel)
            ->get('spk_drawing')
            ->result();
    }

    // tabel_excel HAS MANY spk_stranding
    public function strandings($id_excel)
    {
        return $this->db
            ->where('id_data', $id_excel)
            ->get('spk_stranding')
            ->result();
    }

    // tabel_excel HAS MANY spk_insulation
    public function insulations($id_excel)
    {
        return $this->db
            ->where('id_data', $id_excel)
            ->get('spk_insulation')
            ->result();
    }
}
