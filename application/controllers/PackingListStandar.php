<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PackingListStandar extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PackingListStandar_model');
        $this->load->model('Datahaspel_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->database();
    }

    public function index()
    {
        $data['packinglist'] = $this->PackingListStandar_model->get_all();
        $this->load->view('packing_list_standar', $data);
    }

    public function save()
    {
        $data = $this->input->post();
        $this->PackingListStandar_model->insert($data);
        redirect('PackingListStandar');
    }

    public function update($id)
    {
        $data = $this->input->post();
        $this->PackingListStandar_model->update($id, $data);
        redirect('PackingListStandar');
    }

    public function delete($id)
    {
        $this->PackingListStandar_model->delete($id);
        redirect('PackingListStandar');
    }

    // ✅ Fungsi Import Excel (kalau dibutuhkan)
    public function import_excel()
    {
        $file = $_FILES['file']['tmp_name'];
        if ($file) {
            $this->load->library('Spreadsheet_Excel_Reader');
            $data = new Spreadsheet_Excel_Reader($file);

            $rowcount = $data->rowcount($sheet_index = 0);
            $result = [];

            for ($i = 2; $i <= $rowcount; $i++) {
                $type_size = trim($data->val($i, 1));
                $drum_number = trim($data->val($i, 2));
                $length = trim($data->val($i, 3));
                $gross = trim($data->val($i, 4));
                $netto = trim($data->val($i, 5));
                $dimension = trim($data->val($i, 6));

                preg_match('/^[A-Za-z\/\-]+/', $type_size, $matches);
                $prefix = isset($matches[0]) ? $matches[0] : 'Lainnya';

                $result[$prefix][] = [
                    'type_size' => $type_size,
                    'drum_number' => $drum_number,
                    'length' => $length,
                    'gross' => $gross,
                    'netto' => $netto,
                    'dimension' => $dimension
                ];
            }

            $this->session->set_userdata('grouped_data', $result);
            redirect('PackingListStandar/view_grouped');
        } else {
            echo "Gagal mengimpor file.";
        }
    }

    // ✅ Ambil semua data berat dari tabel data_haspel
public function get_berat_haspel_all()
{
    $this->db->select('haspel, berat, panjang, lebar, tinggi, m3');
    $query = $this->db->get('data_haspel')->result();

    $hasil = [];

    foreach ($query as $row) {
        // Simpan key haspel UTUH (tanpa filter angka)
        $key = trim($row->haspel);

        $hasil[$key] = [
            'berat'   => $row->berat,
            'panjang' => $row->panjang,
            'lebar'   => $row->lebar,
            'tinggi'  => $row->tinggi,
            'm3'      => $row->m3
        ];
    }

    echo json_encode($hasil);
}

}
