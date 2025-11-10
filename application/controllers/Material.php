<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Material_model');
    }

    // public function index() {
    //     $data['material'] = $this->Material_model->get_all();
    //     $this->load->view('kombinasi/material_form');
    // }

    public function index() {
        $data['materials'] = $this->Material_model->get_all();
        $this->load->view('kombinasi/material_list', $data);
    }

    public function tambah() {
        if ($this->input->post()) {
            $this->Material_model->insert($this->input->post());
            redirect('material');
        } else {
            $this->load->view('kombinasi/material_form');
        }
    }

    public function edit($id) {
        if ($this->input->post()) {
            $this->Material_model->update($id, $this->input->post());
            redirect('material');
        } else {
            $data['material'] = $this->Material_model->get_by_id($id);
            $this->load->view('kombinasi/material_form', $data);
        }
    }

    public function hapus($id) {
        $this->Material_model->delete($id);
        redirect('material');
    }

    public function print() {
        $data['materials'] = $this->Material_model->get_all();
        $this->load->view('kombinasi/material_print', $data);
    }
}
