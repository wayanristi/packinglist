<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'controllers/Spk.php';

class Grouping2 extends Spk
{

    public function __construct()
    {
        parent::__construct();
    }

    // halaman utama grouping kabel 2
    public function index()
    {
        echo "Grouping Kabel 2 aktif";
    }

    public function stranding($id_excel)
{
    $excel = $this->db
        ->where('id', $id_excel)
        ->get('tabel_excel')
        ->row();

    if (!$excel) show_404();

    $data['excel'] = $excel;

    $this->load->view('spk/komponen/stranding_grouping2', $data);
}

}