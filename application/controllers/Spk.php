<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'services/ImportService.php';
require_once APPPATH . 'services/SpkService.php';


class Spk extends CI_Controller
{

    public function preview_spk($id)
    {
        $excel = $this->db->get_where('excel_import', ['id' => $id])->row();

        $import = new ImportService();

        $data = $import->parse_excel($excel);

        print_r($data);
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    public function index()
    {
        $this->load->view('spk/komponen/page1');
    }

    public function process_excel()
    {
        // 1. VALIDASI PROCESS
        // 2. VALIDASI DATA EXCEL
        $excel_json = $this->input->post('excel_json');
        if (!$excel_json) {
            show_error('Data Excel belum di-import');
        }

        $rows = json_decode($excel_json, true);
        $import = new ImportService();
        $data_excel = $import->parse_excel_rows($rows);

   $type = trim($data_excel['item']);

$spkService = new SpkService();
$process = $spkService->getProsesByType($type);

// ❌ hanya cek item ada atau tidak
if (!$process || empty($process)) {
    $this->session->set_flashdata('error', 'Item tidak ditemukan di master data, hubungi IT');
    redirect('spk');
    return;
}
        $op_number = $data_excel['production_order'];

        if (!$rows || !is_array($rows)) {
            show_error('Format data Excel tidak valid');
        }
        $existing = $this->db->get_where('tabel_excel', ['production_order' => $op_number])->row();

        // 🔔 JIKA OP SUDAH ADA & BELUM DIKONFIRMASI
        if ($existing && !$this->input->post('confirm')) {
            $this->session->set_userdata('pending_excel_json', $excel_json);
            $this->session->set_userdata('pending_process', $process);
            $this->load->view('spk/komponen/confirm', ['op' => $op_number]);
            return;
        }

        if ($existing) {
            // Jika ada, Timpa (Update)
            $this->db->where('production_order', $op_number);
          $data_excel['updated_at'] = date('Y-m-d H:i:s');

$this->db->update('tabel_excel', $data_excel);
            $id_excel = $existing->id;
// Hapus data lama di tabel relasi biar fresh
$this->db->delete('spk_drawing', ['id_data' => $id_excel]);
$this->db->delete('spk_stranding', ['id_data' => $id_excel]);
$this->db->delete('spk_stranding2', ['id_data' => $id_excel]); // TAMBAH INI
$this->db->delete('spk_insulation', ['id_data' => $id_excel]);

            $this->session->set_flashdata('message', "Data OP $op_number sudah diperbarui!");
        } else {
            // Jika belum ada, Simpan Baru (Insert)
          $data_excel['created_at'] = date('Y-m-d H:i:s');
$data_excel['updated_at'] = date('Y-m-d H:i:s');

$this->db->insert('tabel_excel', $data_excel);
            $id_excel = $this->db->insert_id();
            $this->session->set_flashdata('message', "Data baru berhasil disimpan!");
        }
// 🔥 CEGAH DOUBLE STRANDING
if (in_array('stranding2', $process)) {
    $process = array_diff($process, ['stranding']);
}

// 5. SIMPAN KE TABEL SPK SESUAI PILIHAN
foreach ($process as $p) {
    if ($p === 'drawing') {
        $this->db->insert('spk_drawing', [
            'id_data'     => $id_excel,
            'qty_drawing' => $data_excel['ordered_quantity'],
        ]);
    }

    if ($p === 'stranding') {
        $this->db->insert('spk_stranding', [
            'id_data'       => $id_excel,
            'qty_stranding' => $data_excel['ordered_quantity'],
        ]);
    }

    if ($p === 'stranding2') {
        $this->db->insert('spk_stranding2', [
            'id_data' => $id_excel
        ]);
    }

    if ($p === 'insul') {
        $this->db->insert('spk_insulation', [
            'id_data'        => $id_excel,
            'qty_insulation' => $data_excel['ordered_quantity'],
        ]);
    }

}

        $this->session->set_userdata('filter_spk_' . $id_excel, $process);

        // BALIK KE PAGE 2 (LIST)
        redirect('spk/next');
    }

   public function next()
{
    $data['rows'] = $this->db
        ->order_by('updated_at', 'DESC') // 🔥 INI FIX NYA
        ->get('tabel_excel')
        ->result();

    $this->load->view('spk/komponen/page2', $data);
}

    public function view_spk($id_excel)
    {
        $service = new SpkService();

        $data = $service->get_spk_data($id_excel);

        $this->load->view('spk/halaman', $data);
    }
public function get_process($id_excel)
{
    $process = [];

    if ($this->db->where('id_data', $id_excel)->get('spk_drawing')->num_rows() > 0) {
        $process[] = 'drawing';
    }

    if ($this->db->where('id_data', $id_excel)->get('spk_stranding')->num_rows() > 0) {
        $process[] = 'stranding';
    }

    if ($this->db->where('id_data', $id_excel)->get('spk_stranding2')->num_rows() > 0) {
        $process[] = 'stranding2';   // TAMBAH INI
    }

    if ($this->db->where('id_data', $id_excel)->get('spk_insulation')->num_rows() > 0) {
        $process[] = 'insul';
    }

    if (in_array('stranding2', $process)) {
    $process = array_diff($process, ['stranding']);
}

    echo json_encode($process);
}


    // UBAH INI:
    // public function update_process_ajax() 
    // JADI INI:
    public function update_process()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['status' => 'error', 'message' => 'No data received']);
            return;
        }

        $id_excel = $input['id_excel'];
        $process  = $input['process'] ?? [];

        // Sisanya tetap sama...
      $this->db->delete('spk_drawing', ['id_data' => $id_excel]);
$this->db->delete('spk_stranding', ['id_data' => $id_excel]);
$this->db->delete('spk_stranding2', ['id_data' => $id_excel]);
$this->db->delete('spk_insulation', ['id_data' => $id_excel]);

        foreach ($process as $p) {
            if ($p === 'drawing') $this->db->insert('spk_drawing', ['id_data' => $id_excel]);
            if ($p === 'stranding') $this->db->insert('spk_stranding', ['id_data' => $id_excel]);
            if ($p === 'insul') $this->db->insert('spk_insulation', ['id_data' => $id_excel]);
        }

        $this->session->set_userdata('filter_spk_' . $id_excel, $process);
        echo json_encode(['status' => 'ok']);
    }

    public function reset()
    {
        $this->session->unset_userdata(['id_excel', 'process']);
        redirect('spk');
    }

    // render function and call zpl generate func
    public function label()
    {
        $mode = $this->input->get('mode'); // preview | zpl

        $id_excel = $this->input->get('id');
        $type     = $this->input->get('type');

        $excel = $this->db
            ->where('id', $id_excel)
            ->get('tabel_excel')
            ->row();

        if (!$excel) {
            show_404();
        }

        $diameter = $this->input->get('diameter');
        $drum     = $this->input->get('drum');
        $qtyDrum  = $this->input->get('qtydrum');
        $totalDrum = (int)$this->input->get('total');

        $lengths = json_decode($this->input->get('lengths') ?? '[]', true);

        // =========================
        // GENERATE ZPL
        // =========================

        $zpl = "";
       if ($type === "drawing") {

    $total = $totalDrum > 0 ? $totalDrum : 1;

} elseif ($type === "insul" || $type === "stranding2") {

    $drums = json_decode($excel->standard_description ?? '[]', true);

    if (is_array($drums) && count($drums) > 0) {
        $total = count($drums);
    } else {
        $total = 1;
    }

} else {

    $total = count($lengths);
    if ($total == 0) $total = 1;

}

      for ($i = 1; $i <= min($total, 3); $i++) {

            $length = "-";

            if ($type === "drawing") {
                $length = $qtyDrum;
            }

            if (($type === "stranding" || $type === "insul" || $type === "stranding2") && isset($lengths[$i - 1])) {
                $length = $lengths[$i - 1];
            }

            $zpl .= $this->generate_zpl([
                'excel' => $excel,
                'type' => $type,
                'diameter' => $diameter,
                'drum' => $drum,
                'no' => $i,
                'length' => $length,
                'lengths' => $lengths
            ]);
        }

        // =========================
        // MODE PREVIEW
        // =========================

        if ($mode === "preview") {

            $url = "http://api.labelary.com/v1/printers/8dpmm/labels/3.54331x2.75591/0/";

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $zpl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Accept: image/png"
            ]);

            $image = curl_exec($ch);
            curl_close($ch);

            header("Content-Type: image/png");
            header("X-Total-Label: " . $total);
            echo $image;
            return;
        }

        // =========================
        // MODE ZPL (PRINT)
        // =========================

        header("Content-Type: text/plain");
        echo $zpl;
    }

    // zpl generate function
    private function generate_zpl($data)
    {
        $excel    = $data['excel'];
      $typeRaw = $data['type'];

// 🔥 custom label
if ($typeRaw === 'stranding2') {
    $type = 'STRANDING';
} else {
    $type = strtoupper($typeRaw);
}
        $diameter = $data['diameter'];
        $drum     = $data['drum'];
        $no       = $data['no'];
        $length   = $data['length'];
        $lengths  = $data['lengths'] ?? [];
        $order    = $excel->production_order;
        $customer = $excel->customer_name;
        $size     = $excel->description;

        // =====================
        // FORMAT DRUM NO
        // =====================

        $drumText = "";
if ($data['type'] === "insul" || $data['type'] === "stranding2") {

   $drumsRaw = $excel->standard_description ?? '';
$lotsRaw  = $excel->lot ?? '';

$drums = json_decode($drumsRaw, true);
$lots  = json_decode($lotsRaw, true);

// 🔥 HANDLE SEMUA KASUS (INI YANG FIX MASALAH KAMU)
if (!is_array($drums)) {
    $drums = [$drumsRaw];
}

if (!is_array($lots)) {
    $lots = [$lotsRaw];
}

$sizeDrum = isset($drums[$no - 1]) ? $drums[$no - 1] : '-';
$lot      = isset($lots[$no - 1]) ? $lots[$no - 1] : '-';

    $drumText = $no . "   " . $sizeDrum . "   " . $lot;
} elseif ($data['type'] === "stranding") {

            $totalDrum = count($lengths);
            $drumUtama = $totalDrum - 1;

            $drumNomor = $no;

            if ($no > $drumUtama) {
                $drumNomor = $no - $drumUtama;
            }

            $drumText = $drum . "-" . $drumNomor;
        } else {

            $drumText = $drum . "-" . $no;
        }

        return "
            ^XA
            ^CI28
            ^PW720
            ^LL560
            ^LS0
            ^LT0

            --- Garis Luar (Tinggi dikurangi ke 540 agar ada margin) ---
            ^FO10,10^GB700,540,2^FS

            --- Garis Horizontal (Jarak antar baris dirapatkan) ---
            ^FO10,40^GB700,2,2^FS
            ^FO10,75^GB700,2,2^FS
            ^FO10,105^GB700,2,2^FS
            ^FO10,135^GB700,2,2^FS
            ^FO10,165^GB700,2,2^FS
            ^FO10,195^GB700,2,2^FS
            ^FO10,225^GB700,2,2^FS
            ^FO10,255^GB700,2,2^FS
            ^FO10,285^GB700,2,2^FS
            ^FO10,315^GB700,2,2^FS
            ^FO10,345^GB700,2,2^FS
            ^FO10,460^GB700,2,2^FS
            ^FO360,490^GB350,2,2^FS
            ^FO10,520^GB700,2,2^FS

            --- Garis Vertikal (Disesuaikan tinggi barunya) ---
            ^FO480,40^GB2,35,2^FS
            ^FO360,345^GB2,115,2^FS
            ^FO200,460^GB2,90,2^FS
            ^FO360,460^GB2,90,2^FS
            ^FO535,490^GB2,60,2^FS

            --- Header ---
            ^FO370,15^A0N,20,20^FDJCC-MV-PS-002-F004-REV 1^FS
            ^FO20,48^A0N,26,26^FDPT. JEMBO CABLE COMPANY^FS
            ^FO495,48^A0N,24,24^FDPRODUCTION^FS

            --- Data Section ---
            ^FO20,82^A0N,20,20^FDMACHINE NO.^FS ^FO230,82^A0N,20,20^FD:^FS ^FO260,82^A0N,20,20^FD^FS
            ^FO20,112^A0N,20,20^FDPROCESS^FS ^FO230,112^A0N,20,20^FD:^FS ^FO260,112^A0N,20,20^FD$type^FS
            ^FO20,142^A0N,20,20^FDORDER NO.^FS ^FO230,142^A0N,20,20^FD:^FS ^FO260,142^A0N,20,20^FD$order^FS
            ^FO20,172^A0N,20,20^FDTYPE SIZE^FS ^FO230,172^A0N,20,20^FD:^FS ^FO260,172^A0N,20,20^FH^FD$size^FS
            ^FO20,202^A0N,20,20^FDCUSTOMER^FS ^FO230,202^A0N,20,20^FD:^FS ^FO260,202^A0N,20,20^FD$customer^FS
            ^FO20,232^A0N,20,20^FDDIAMETER^FS ^FO230,232^A0N,20,20^FD:^FS ^FO260,232^A0N,20,20^FD$diameter^FS
            ^FO20,262^A0N,20,20^FDDRUM NO.^FS ^FO230,262^A0N,20,20^FD:^FS ^FO260,262^A0N,20,20^FD$drumText^FS
            ^FO20,292^A0N,20,20^FDLENGTH^FS ^FO230,292^A0N,20,20^FD:^FS ^FO260,292^A0N,20,20^FD$length^FS

            --- Remarks & Test Result ---
            ^FO20,355^A0N,24,24^FDREMARKS^FS
            ^FO375,355^A0N,24,24^FDTEST RESULT^FS

            --- Footer Labels ---
            ^FO75,465^A0N,20,20^FDDATE^FS
            ^FO255,465^A0N,20,20^FDSHIFT^FS
            ^FO490,465^A0N,20,20^FDOPERATOR^FS
            ^FO415,495^A0N,20,20^FDNAME^FS
            ^FO595,495^A0N,20,20^FDSIGN^FS

            --- Footer Values ---
            ^FO40,525^A0N,18,18^FD^FS
            ^FO230,525^A0N,18,18^FD^FS
            ^FO400,525^A0N,18,18^FD^FS
            ^FO575,525^A0N,18,18^FD^FS
            ^XZ
                ";
    }
}
