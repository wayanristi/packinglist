<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datahaspel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Datahaspel_model');
    }

    public function index() {
        $data['title'] = 'Data Haspel';
        $data['datahaspel'] = $this->Datahaspel_model->get_all();
        $this->load->view('datahaspel/index', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Data Haspel';
        $this->load->view('datahaspel/form', $data);
    }

    public function store() {
        $this->Datahaspel_model->insert();
        redirect('datahaspel');
    }

    public function edit($id) {
        $data['title'] = 'Edit Data Haspel';
        $data['data'] = $this->Datahaspel_model->get_by_id($id);
        $this->load->view('datahaspel/form', $data);
    }

    public function update($id) {
        $this->Datahaspel_model->update($id);
        redirect('datahaspel');
    }

    public function delete($id) {
        $this->Datahaspel_model->delete($id);
        redirect('datahaspel');
    }
    
}
