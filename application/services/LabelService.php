<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LabelService {

    public function build_zpl($excel,$type,$diameter,$drum,$qtyDrum,$totalDrum,$lengths)
    {
        $zpl = "";

        if ($type === "drawing") {
            $total = $totalDrum > 0 ? $totalDrum : 1;
        } else {
            $total = count($lengths);
            if ($total == 0) $total = 1;
        }

        for ($i = 1; $i <= $total; $i++) {

          $length = "-";

if ($type === "drawing") {
    $length = $qtyDrum;
}

if ($type === "stranding" && isset($lengths[$i - 1])) {
    $length = $lengths[$i - 1];
}

if (($type === "insul" || $type === "stranding2") && isset($lengths[$i - 1])) {
    $length = $lengths[$i - 1];
}
            $zpl .= $this->generate_zpl([
                'excel'=>$excel,
                'type'=>$type,
                'diameter'=>$diameter,
                'drum'=>$drum,
                'no'=>$i,
                'length'=>$length,
                'lengths'=>$lengths
            ]);
        }

        return $zpl;
    }

  private function generate_zpl($data)
{
    $excel    = $data['excel'];

    $type     = strtoupper($data['type'] ?? "-");
    $diameter = $data['diameter'] ?: "-";
    $drum     = $data['drum'] ?: "-";
    $no       = $data['no'];
    $length   = $data['length'] ?: "-";

    $order    = $excel->production_order ?? "-";
    $customer = $excel->customer_name ?? "-";
    $size     = str_replace(['^','~'], '', $excel->description ?? "-");

    $lengths  = $data['lengths'] ?? [];

        // =====================
        // FORMAT DRUM NO
        // =====================

        $drumText = "";

        if ($type === "insul" || $type === "stranding2") {
$drums = json_decode($excel->standard_description ?? '[]', true);
$lots  = json_decode($excel->lot ?? '[]', true);

if (!is_array($drums)) $drums = [];
if (!is_array($lots)) $lots = [];

            $sizeDrum = "";
            $lot  = "";

            if (isset($drums[$no - 1])) {
                $sizeDrum = $drums[$no - 1];
            }

            if (isset($lots[$no - 1])) {
                $lot = $lots[$no - 1];
            }

            $drumText = $no . "   " . $sizeDrum . "   " . $lot;

        } elseif ($type === "stranding") {

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

^FO10,10^GB700,540,2^FS

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

^FO480,40^GB2,35,2^FS
^FO360,345^GB2,115,2^FS
^FO200,460^GB2,90,2^FS
^FO360,460^GB2,90,2^FS
^FO535,490^GB2,60,2^FS

^FO370,15^A0N,20,20^FDJCC-MV-PS-002-F004-REV 1^FS
^FO20,48^A0N,26,26^FDPT. JEMBO CABLE COMPANY^FS
^FO495,48^A0N,24,24^FDPRODUCTION^FS

^FO20,82^A0N,20,20^FDMACHINE NO.^FS ^FO230,82^A0N,20,20^FD:^FS ^FO260,82^A0N,20,20^FD^FS
^FO20,112^A0N,20,20^FDPROCESS^FS ^FO230,112^A0N,20,20^FD:^FS ^FO260,112^A0N,20,20^FD$type^FS
^FO20,142^A0N,20,20^FDORDER NO.^FS ^FO230,142^A0N,20,20^FD:^FS ^FO260,142^A0N,20,20^FD$order^FS
^FO20,172^A0N,20,20^FDTYPE SIZE^FS ^FO230,172^A0N,20,20^FD:^FS ^FO260,172^A0N,20,20^FH^FD$size^FS
^FO20,202^A0N,20,20^FDCUSTOMER^FS ^FO230,202^A0N,20,20^FD:^FS ^FO260,202^A0N,20,20^FD$customer^FS
^FO20,232^A0N,20,20^FDDIAMETER^FS ^FO230,232^A0N,20,20^FD:^FS ^FO260,232^A0N,20,20^FD$diameter^FS
^FO20,262^A0N,20,20^FDDRUM NO.^FS ^FO230,262^A0N,20,20^FD:^FS ^FO260,262^A0N,20,20^FD$drumText^FS
^FO20,292^A0N,20,20^FDLENGTH^FS ^FO230,292^A0N,20,20^FD:^FS ^FO260,292^A0N,20,20^FD$length^FS

^FO20,355^A0N,24,24^FDREMARKS^FS
^FO375,355^A0N,24,24^FDTEST RESULT^FS

^FO75,465^A0N,20,20^FDDATE^FS
^FO255,465^A0N,20,20^FDSHIFT^FS
^FO490,465^A0N,20,20^FDOPERATOR^FS
^FO415,495^A0N,20,20^FDNAME^FS
^FO595,495^A0N,20,20^FDSIGN^FS

^FO40,525^A0N,18,18^FD^FS
^FO230,525^A0N,18,18^FD^FS
^FO400,525^A0N,18,18^FD^FS
^FO575,525^A0N,18,18^FD^FS
^XZ
";
    }
}