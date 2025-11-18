<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontrak extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kontrak_model');
    }

    public function index() {
        $data['kontrak'] = $this->Kontrak_model->get_all();
        $this->load->view('kontrak/list', $data);
    }

    public function add() {
        $this->load->view('kontrak/add');
    }

    public function store() {
        if($this->input->post()) {
            $inserted = $this->Kontrak_model->insert([
                'nomor_kontrak' => $this->input->post('nomor_kontrak')
            ]);
            header('Content-Type: application/json');
            echo json_encode($inserted ? 
                ['status'=>'success','message'=>'Nomor kontrak berhasil ditambahkan 😃'] :
                ['status'=>'error','message'=>'Gagal menambahkan nomor kontrak 😢']
            );
        }
    }

    public function edit($id) {
        $data['kontrak'] = $this->Kontrak_model->get_by_id($id);
        $this->load->view('kontrak/edit', $data);
    }

    public function update($id) {
        if($this->input->post()) {
            $updated = $this->Kontrak_model->update($id, [
                'nomor_kontrak' => $this->input->post('nomor_kontrak')
            ]);
            header('Content-Type: application/json');
            echo json_encode($updated ? 
                ['status'=>'success','message'=>'Nomor kontrak berhasil diperbarui 😃'] :
                ['status'=>'error','message'=>'Gagal memperbarui nomor kontrak 😢']
            );
        }
    }

    public function delete($id) {
        $deleted = $this->Kontrak_model->delete($id);
        header('Content-Type: application/json');
        echo json_encode($deleted ? 
            ['status'=>'success','message'=>'Nomor kontrak berhasil dihapus 😃'] :
            ['status'=>'error','message'=>'Gagal menghapus nomor kontrak 😢']
        );
    }
}
