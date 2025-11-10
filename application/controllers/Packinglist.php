<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packinglist extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Material_model');
        $this->load->model('Datahaspel_model');
    }

    public function index() {
        $this->load->view('packinglist_view');
    }

    // ✅ Ambil Kode Serial Material
    public function get_serial_code() {
        $desc = $this->input->get('desc');
        $material = $this->Material_model->get_by_description($desc);

        if ($material) {
            $tahun_2digit = substr($material['tahun'], -2);

            $kode_serial = 
                $material['identitas_jenis_material'] .
                $material['kode_pabrik_jembo'] .
                $tahun_2digit;

            echo json_encode(['kode_serial' => $kode_serial]);
        } else {
            echo json_encode(['kode_serial' => '']);
        }
    }

   // /application/controllers/Packinglist.php
public function get_berat_haspel() {
    $standard_desc = $this->input->get('standard_desc');

    // ambil angka pertama dari standard_desc (contoh: "HASPEL KAYU 160 LOKAL" -> "160")
    preg_match('/\d+/', $standard_desc, $match);
    $angka = isset($match[0]) ? $match[0] : '';

    if (!$angka) {
        echo json_encode(['berat_haspel' => 0]);
        return;
    }

    // model mengembalikan baris yang kolom 'haspel' = angka
    $haspel = $this->Datahaspel_model->get_by_standard_desc($angka);

    if ($haspel) {
        echo json_encode(['berat_haspel' => floatval($haspel->berat)]);
    } else {
        echo json_encode(['berat_haspel' => 0]);
    }
}

}
