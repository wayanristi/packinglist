<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datahaspel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Datahaspel_model');
    }

    // Menampilkan list data
    public function index() {
        $data['title'] = 'Data Haspel';
        $data['datahaspel'] = $this->Datahaspel_model->get_all();
        $this->load->view('datahaspel/index', $data);
    }

    // Halaman tambah data
    public function create() {
        $data['title'] = 'Tambah Data Haspel';
        $this->load->view('datahaspel/form', $data);
    }

    // Simpan data baru
    public function store() {
        if($this->input->post()) {
            $inserted = $this->Datahaspel_model->insert();

            header('Content-Type: application/json');
            echo json_encode($inserted ? 
                ['status'=>'success','message'=>'Data berhasil ditambahkan 😃'] :
                ['status'=>'error','message'=>'Gagal menyimpan data 😢']);
        }
    }

    // Halaman edit data
    public function edit($id) {
        $data['title'] = 'Edit Data Haspel';
        $data['data'] = $this->Datahaspel_model->get_by_id($id);
        $this->load->view('datahaspel/form', $data);
    }

    // Update data
    public function update($id) {
        if($this->input->post()) {
            $updated = $this->Datahaspel_model->update($id);

            header('Content-Type: application/json');
            echo json_encode($updated ? 
                ['status'=>'success','message'=>'Data berhasil diperbarui 😃'] :
                ['status'=>'error','message'=>'Gagal memperbarui data 😢']);
        }
    }

    // Hapus data
    public function delete($id) {
        $deleted = $this->Datahaspel_model->delete($id);

        header('Content-Type: application/json');
        echo json_encode($deleted ? 
            ['status'=>'success','message'=>'Data berhasil dihapus 😃'] :
            ['status'=>'error','message'=>'Gagal menghapus data 😢']);
    }
}
