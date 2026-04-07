<?php

class SpkService
{

    public function get_spk_data($id_excel)
    {
        $CI = &get_instance();

        $excel = $CI->db
            ->where('id', $id_excel)
            ->get('tabel_excel')
            ->row();

        if (!$excel) {
            show_404();
        }

        $kodeKabel = trim($excel->item);

        $filter = [
            "metadata.number" => $kodeKabel
        ];

        $url = "http://192.168.10.40:8088/tds_documents/?" .
            "filter=" . urlencode(json_encode($filter));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 🔐 BASIC AUTH
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "admin:secret");

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);

        if ($response === false) {
            die('CURL ERROR: ' . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            echo "HTTP CODE: " . $httpCode;
            echo "<pre>";
            var_dump($response);
            die;
        }

        $apiData = json_decode($response, true);

        $tds_not_found = false;
        $firstData = [];

        if (!is_array($apiData) || count($apiData) === 0) {
            $tds_not_found = true;
        } else {
            $firstData = reset($apiData);
        }

        // =========================
        // DIR OF LAY (AMAN, ANTI ERROR)
        // =========================
        $dirLay1 = '-';
        $dirLay2 = '-';
        $dirLay3 = '-';
        $dirLay4 = '-';
        $dirLay5 = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers'])
        ) {
            $layers = $firstData['section']['conductor']['layers'];

            foreach ($layers as $i => $layer) {
                if (!isset($layer['specified'])) continue;

                $parts = explode('/', $layer['specified']);
                $dir = trim(end($parts));

                if ($i === 0) $dirLay1 = $dir;
                if ($i === 1) $dirLay2 = $dir;
                if ($i === 2) $dirLay3 = $dir;
                if ($i === 3) $dirLay4 = $dir;
                if ($i === 4) $dirLay5 = $dir;
            }
        }

        $meta = $firstData['metadata'] ?? [];

        $type        = $meta['type'] ?? '';
        $size        = $meta['size'] ?? '';
        $rateVoltage = $meta['rate_voltage'] ?? '';

        $specified = '';

        if (
            isset($firstData['section']['conductor']['technical_specs']) &&
            is_array($firstData['section']['conductor']['technical_specs'])
        ) {
            foreach ($firstData['section']['conductor']['technical_specs'] as $spec) {
                if (
                    isset($spec['description']) &&
                    (
                        $spec['description'] === 'Number/diameter of wire' ||
                        $spec['description'] === 'No. / nominal diameter of wire'
                    )
                ) {
                    $specified = $spec['specified'] ?? '';
                    break;
                }
            }
        }

        $lp_range_1 = '-';
        $lp_range_2 = '-';
        $lp_range_3 = '-';
        $lp_range_4 = '-';
        $lp_range_5 = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers'])
        ) {
            $layers = $firstData['section']['conductor']['layers'];

            foreach ($layers as $i => $layer) {
                if (!isset($layer['specified'])) continue;

                $parts = explode('/', $layer['specified']);

                if (isset($parts[1])) {
                    $range = trim($parts[1]); // 🔥 ini 68 - 108

                    if ($i === 0) $lp_range_1 = $range;
                    if ($i === 1) $lp_range_2 = $range;
                    if ($i === 2) $lp_range_3 = $range;
                    if ($i === 3) $lp_range_4 = $range;
                    if ($i === 4) $lp_range_5 = $range;
                }
            }
        }


        // =========================
        // AMBIL CONSTRUCTION (TDS)
        // =========================
        $construction = '';

        if (
            isset($firstData['section']['conductor']['technical_specs']) &&
            is_array($firstData['section']['conductor']['technical_specs'])
        ) {
            foreach ($firstData['section']['conductor']['technical_specs'] as $spec) {
                if (
                    isset($spec['description']) &&
                    $spec['description'] === 'Construction'
                ) {
                    $construction = $spec['specified'] ?? '';
                    break;
                }
            }
        }

        // =========================
        // SPEC INSULATION (TDS)
        // =========================
        $thickness_spec_min = '-';
        $diameter_insul_spec_min = '-';

        if (
            isset($firstData['section']['covering']['technical_specs']) &&
            is_array($firstData['section']['covering']['technical_specs'])
        ) {
            foreach ($firstData['section']['covering']['technical_specs'] as $spec) {

                // 🔥 THICKNESS (Nom thickness → 3.0)
                if (
                    isset($spec['description']) &&
                    stripos($spec['description'], 'Nom thickness') !== false
                ) {
                    $thickness_spec_min = $spec['specified'] ?? '-';
                }

                // 🔥 DIAMETER INSULATION (Outer diameter Spec. Max → 17.3)
                if (
                    isset($spec['description']) &&
                    stripos($spec['description'], 'Outer diameter') !== false
                ) {
                    $diameter_insul_spec_min = $spec['specified'] ?? '-';
                }
            }
        }

        // =========================
        // LAY PITCH (LP) LAYER 1 - MIN & MAX SAJA
        // =========================
        $lp1_min = '-';
        $lp1_max = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers']) &&
            isset($firstData['section']['conductor']['layers'][0]['specified'])
        ) {
            // contoh: "6.75 / 68 - 108 / S"
            $spec = $firstData['section']['conductor']['layers'][0]['specified'];

            $parts = explode('/', $spec);
            if (isset($parts[1])) {
                $range = trim($parts[1]); // "68 - 108"
                $nums  = explode('-', $range);

                if (count($nums) === 2) {
                    $min = trim($nums[0]);
                    $max = trim($nums[1]);

                    if (is_numeric($min) && is_numeric($max)) {
                        $lp1_min = $min;
                        $lp1_max = $max;
                    }
                }
            }
        }

        // =========================
        // LAY PITCH (LP) LAYER 2 - MIN & MAX
        // =========================
        $lp2_min = '-';
        $lp2_max = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers']) &&
            isset($firstData['section']['conductor']['layers'][1]['specified'])
        ) {
            // contoh: "11.25 / 113 - 158 / Z"
            $spec = $firstData['section']['conductor']['layers'][1]['specified'];

            $parts = explode('/', $spec);
            if (isset($parts[1])) {
                $range = trim($parts[1]); // "113 - 158"
                $nums  = explode('-', $range);

                if (count($nums) === 2) {
                    $min = trim($nums[0]); // 113
                    $max = trim($nums[1]); // 158

                    if (is_numeric($min) && is_numeric($max)) {
                        $lp2_min = $min;
                        $lp2_max = $max;
                    }
                }
            }
        }

        // =========================
        // LAY PITCH (LP) LAYER 3 - MIN & MAX
        // =========================
        $lp3_min = '-';
        $lp3_max = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers']) &&
            isset($firstData['section']['conductor']['layers'][2]['specified'])
        ) {
            // contoh: "xx.xx / 150 - 200 / S"
            $spec = $firstData['section']['conductor']['layers'][2]['specified'];

            $parts = explode('/', $spec);
            if (isset($parts[1])) {
                $range = trim($parts[1]); // "150 - 200"
                $nums  = explode('-', $range);

                if (count($nums) === 2) {
                    $min = trim($nums[0]);
                    $max = trim($nums[1]);

                    if (is_numeric($min) && is_numeric($max)) {
                        $lp3_min = $min;
                        $lp3_max = $max;
                    }
                }
            }
        }

        // =========================
        // COND. DIAMETER (mm) - NOMINAL (LAYER 1 - 3)
        // =========================
        $cond1_nom = '-';
        $cond2_nom = '-';
        $cond3_nom = '-';
        $cond4_nom = '-';
        $cond5_nom = '-';

        if (
            isset($firstData['section']['conductor']['layers']) &&
            is_array($firstData['section']['conductor']['layers'])
        ) {
            $layers = $firstData['section']['conductor']['layers'];

            // helper ambil nilai pertama sebelum "/"
            $getNominal = function ($specified) {
                if (!$specified) return '-';
                $parts = explode('/', $specified);
                return isset($parts[0]) ? trim($parts[0]) : '-';
            };

            if (isset($layers[0]['specified'])) {
                $cond1_nom = $getNominal($layers[0]['specified']);
            }

            if (isset($layers[1]['specified'])) {
                $cond2_nom = $getNominal($layers[1]['specified']);
            }

            if (isset($layers[2]['specified'])) {
                $cond3_nom = $getNominal($layers[2]['specified']);
            }

            if (isset($layers[3]['specified'])) {
                $cond4_nom = $getNominal($layers[3]['specified']);
            }

            if (isset($layers[4]['specified'])) {
                $cond5_nom = $getNominal($layers[4]['specified']);
            }
        }

        $numberOfWire = 0;
        $minDiameter = 0;
        $maxDiameterRaw = 0;

        if ($specified !== '') {
            // contoh: "19 / 2.25 ± 0.025"
            $parts = explode('/', $specified);

            $numberOfWire = (int) trim($parts[0]); // 19

            if (isset($parts[1])) {
                $rightPart = trim($parts[1]); // "2.25 ± 0.025"

                $minDiameter = (float) explode(' ', $rightPart)[0];

                if (strpos($rightPart, '±') !== false) {
                    $tol = (float) trim(explode('±', $rightPart)[1]);
                    $maxDiameterRaw = $minDiameter + $tol;
                }
            }
        }

        $avgDiameter = round(($minDiameter + $maxDiameterRaw) / 2, 2);

        $minDiameterDisplay = number_format($minDiameter, 2);
        $maxDiameterDisplay = number_format($maxDiameterRaw, 2);
        $avgDiameterDisplay = number_format($avgDiameter, 2);

        if ($specified !== '') {
            // contoh: "19 / 2.25 ± 0.025"
            $parts = explode('/', $specified);

            // Number of wire
            $numberOfWire = trim($parts[0]); // 19

            // Ambil bagian kanan: "2.25 ± 0.025"
            if (isset($parts[1])) {
                $rightPart = trim($parts[1]);

                // Ambil angka pertama sebelum spasi (2.25)
                $minDiameter = trim(explode(' ', $rightPart)[0]); // 2.25
            }
        }

        $numberOfWire = '';
        if ($specified !== '') {
            $parts = explode('/', $specified);
            $numberOfWire = trim($parts[0]); // <-- INI HASILNYA 19
        }

        $qtyOrder = (float) str_replace(',', '', $excel->ordered_quantity);

        // =========================
        // QTY INSULATION
        // =========================
        $qtyInsulation = round($qtyOrder);
        $wireCount = (int) $numberOfWire;

        $qtyDrawing = round($qtyOrder * $wireCount);

        $material = '';

        if (
            isset($firstData['section']['conductor']['raw_materials'][0]['type'])
        ) {
            $material = trim(
                $firstData['section']['conductor']['raw_materials'][0]['type']
            );
        }

        // =========================
        // MATERIAL INSULATION (TDS - COVERING)
        // =========================
        $material_insulation = '';

        if (
            isset($firstData['section']['covering']['raw_materials']) &&
            is_array($firstData['section']['covering']['raw_materials'])
        ) {
            $types = [];

            foreach ($firstData['section']['covering']['raw_materials'] as $mat) {
                if (!empty($mat['type'])) {
                    $types[] = trim($mat['type']);
                }
            }

            // TANPA KOMA, PAKE SPASI
            $material_insulation = implode(' ', $types);
        }

        // =========================
        // CABLE MARKING (TDS)
        // =========================
        $cable_marking = '';

        if (
            isset($firstData['marking']['kalimat_marking']) &&
            !empty($firstData['marking']['kalimat_marking'])
        ) {
            $cable_marking = trim($firstData['marking']['kalimat_marking']);
        }


        // 🔥 Ambil filter dari session yang kita set pas import tadi
        $process = $CI->session->userdata('filter_spk_' . $id_excel);

        // Jika session kosong (buka data lama), baru ambil dari apa yang ada di DB
        if (!$process) {
           $process = [];

if ($CI->db->where('id_data', $id_excel)->get('spk_drawing')->num_rows() > 0) {
    $process[] = 'drawing';
}

if ($CI->db->where('id_data', $id_excel)->get('spk_stranding')->num_rows() > 0) {
    $process[] = 'stranding';
}

if ($CI->db->where('id_data', $id_excel)->get('spk_stranding2')->num_rows() > 0) {
    $process[] = 'stranding2';
}

if ($CI->db->where('id_data', $id_excel)->get('spk_insulation')->num_rows() > 0) {
    $process[] = 'insul';
}
        }

        $data = [
            'excel'          => $excel,
            'process'        => $process,
            'type'           => $type,
            'size'           => $size,
            'rate_voltage'   => $rateVoltage,
            'number_of_wire' => $numberOfWire,
            'min_diameter'   => $minDiameterDisplay,
            'max_diameter'   => $maxDiameterDisplay,
            'avg_diameter'   => $avgDiameterDisplay,
            'qty_drawing'    => number_format($qtyDrawing),
            // 'qty_stranding'    => number_format($qtyStranding),
            'material'       => $material,
            'construction'   => $construction,
            'dir_lay_1' => $dirLay1,
            'dir_lay_2' => $dirLay2,
            'dir_lay_3' => $dirLay3,
            'lp1_min' => $lp1_min,
            'lp1_max' => $lp1_max,
            'lp2_min' => $lp2_min,
            'lp2_max' => $lp2_max,
            'lp3_min' => $lp3_min,
            'lp3_max' => $lp3_max,
            'cond1_nom' => $cond1_nom,
            'cond2_nom' => $cond2_nom,
            'cond3_nom' => $cond3_nom,
            'cond1_nom' => $cond1_nom,
            'cond2_nom' => $cond2_nom,
            'cond3_nom' => $cond3_nom,
            'cond4_nom' => $cond4_nom,
            'cond5_nom' => $cond5_nom,

            'dir_lay_4' => $dirLay4,
            'dir_lay_5' => $dirLay5,

            'lp_range_4' => $lp_range_4,
            'lp_range_5' => $lp_range_5,
            'material_insulation' => $material_insulation,
            'cable_marking' => $cable_marking,
            'qty_insulation' => number_format($qtyInsulation),
            'thickness_spec_min'        => $thickness_spec_min,
            'diameter_insul_spec_min'  => $diameter_insul_spec_min,
            'lp_range_1' => $lp_range_1,
            'lp_range_2' => $lp_range_2,
            'lp_range_3' => $lp_range_3,
            'tds_not_found'  => $tds_not_found
        ];

        return $data;
    }
public function getProsesByType($item)
{
    $ci =& get_instance();

    $row = $ci->db
        ->where('item', trim($item))
        ->get('master_data')
        ->row();

    if (!$row) {
        return false;
    }

    // 🔥 pecah string
    $proses = explode(',', $row->tahapan_proses);

    // 🔥 bersihin spasi
    $proses = array_map('trim', $proses);

    // 🔥 hapus duplikat
    $proses = array_unique($proses);

    // 🔥 HARD FILTER (INI KUNCI)
    if (in_array('stranding2', $proses)) {
        $proses = array_diff($proses, ['stranding']);
    }

    return $proses;
}

public function getMasterByItem($item)
{
    $CI =& get_instance();

    return $CI->db
        ->where('item', trim($item))
        ->get('master_data')
        ->row();
}

    
}
