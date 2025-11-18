<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Material_model');
    }

    public function index() {
        $data['materials'] = $this->Material_model->get_all();
        $this->load->view('kombinasi/material_list', $data);
    }

    public function tambah() {
        if ($this->input->post()) {
            $inserted = $this->Material_model->insert($this->input->post());
            header('Content-Type: application/json');
            if($inserted) {
                echo json_encode(['status'=>'success','message'=>'Data berhasil ditambahkan 🤩']);
            } else {
                echo json_encode(['status'=>'error','message'=>'Gagal menyimpan data 😢']);
            }
        } else {
            $this->load->view('kombinasi/material_form');
        }
    }

    public function edit($id) {
        if ($this->input->post()) {
            $updated = $this->Material_model->update($id, $this->input->post());
            header('Content-Type: application/json');
            if($updated) {
                echo json_encode(['status'=>'success','message'=>'Data berhasil diperbarui 🤩']);
            } else {
                echo json_encode(['status'=>'error','message'=>'Gagal memperbarui data 😢']);
            }
        } else {
            $data['material'] = $this->Material_model->get_by_id($id);
            $this->load->view('kombinasi/material_form', $data);
        }
    }

    public function hapus($id) {
        $deleted = $this->Material_model->delete($id);
        header('Content-Type: application/json');
        if($deleted) {
            echo json_encode(['status'=>'success','message'=>'Data berhasil dihapus 🤩']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Gagal menghapus data 😢']);
        }
    }
}
