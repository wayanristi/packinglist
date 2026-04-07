<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>WORK ORDER & CHECK SHEET INSULATION PROCESS</title>
<style>

    /* input manual yang HARUS pakai kotak */
input.boxed, textarea.boxed {
    border: 1px solid #000;
    background: #fff;
    padding: 2px;
}

    /* Menaruh semua isi tabel check-sheet ke tengah */
.check-sheet input.manual, 
.check-sheet select.manual, 
.check-sheet td {
    text-align: center;
}
@page{
    size:A4 landscape;
    margin:8mm;
}
body{
    font-family:Arial, Helvetica, sans-serif;
    font-size:10px;
}
table{
    width:100%;
    border-collapse:collapse;
}
td,th{
    border:1px solid #000;
    padding:2px;
    vertical-align:middle;
}
input.manual, textarea.manual {
    width: 100%;
    border: none;              /* default tetap tanpa kotak */
    outline: none;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    background: transparent;
}

/* 🔥 KHUSUS INPUT YANG MAU ADA KOTAK */
input.manual.boxed,
textarea.manual.boxed {
    border: 1px solid #000;
    background: #fff;
    padding: 2px;
    border-radius: 4px;   /* 🔥 INI YANG BIKIN ROUND */
}

textarea.manual {
    resize: none;
}
.no-border td{border:none;}
.center{text-align:center;}
.right{text-align:right;}
.bold{font-weight:bold;}
.gray{background:#e6e6e6;}
.red{color:red;}
.small{font-size:9px;}
.h20{height:20px;}
.h25{height:25px;}
.h40{height:40px;}
@media print{button{display:none;}}
@media print {
    body * {
        visibility: hidden;
    }
    #print-area, #print-area * {
        visibility: visible;
    }
    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    select.manual{
    width:100%;
    border:none;
    outline:none;
    font-family:Arial, Helvetica, sans-serif;
    font-size:10px;
    background:transparent;
}
@media print {
    /* Hilangkan tampilan select box */
    select.manual {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        border: none;
        background: transparent;
        pointer-events: none;
        padding: 0;
    }
    /* Hilangkan panah dropdown di browser tertentu */
    select.manual::-ms-expand {
        display: none;
    }
}
@media print {
    select.manual {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        border: none;
        background: transparent;
        pointer-events: none;
        padding: 0;
        text-align: center;   /* 🔥 RATA TENGAH */
    }
    select.manual option {
        text-align: center;
    }
}
@media print {
    thead {
        display: table-header-group;
    }
    tfoot {
        display: table-footer-group;
    }
    tr {
        page-break-inside: avoid;
    }
}
@media print {

    @page {
        size: A4;
        margin: 10mm;
    }

    @page :right {
        @top-right {
            content: "Page " counter(page) " / " counter(pages);
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }
    }

    @page :left {
        @top-right {
            content: "Page " counter(page) " / " counter(pages);
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }
    }
}
@media print {
    thead { display: table-header-group; }
    tfoot { display: table-footer-group; }
}
.bold { font-weight: bold; }

/* Sembunyikan input Cable Marking */
.cable-marking-input{
    display:none;
}

/* Tampilkan teksnya dan biarkan wrap */
.cable-marking-cell::after{
    content: attr(data-text);
    white-space: normal;
    word-break: break-word;
    display:block;
}


}
</style>
</head>
<body>
<div style="margin-bottom:10px;">
</div>
<div id="print-area">
    <?php
// ===== ambil 140 dari standard_description Excel =====
$kapDrumSize = '';

$stdDesc = json_decode($excel->standard_description ?? '[]', true);
if (!empty($stdDesc) && isset($stdDesc[0])) {
    // contoh: "1  140  B25000605200001"
    preg_match('/\b(\d{2,4})\b/', $stdDesc[0], $m);
    $kapDrumSize = $m[1] ?? '';
}
?>

<table class="spk">
    <tr>
        <td class="bold">
            <div style="display:flex; justify-content:space-between;">
                <span>PT JEMBO CABLE COMPANY Tbk</span>
                <span>Lampiran JCC-MV-PS-001-F001</span>
            </div>
        </td>
    </tr>
    <tr>
        <td class="bold left">
            WORK ORDER & CHECK SHEET INSULATION PROCESS
        </td>
    </tr>
</table>

<table class="spk">
    <tr>
        <th colspan="21" class="center gray">WORK ORDER</th>
    </tr>

<!-- ROW 1 -->
<tr>
    <td colspan="2">Machine No.</td>
    <td colspan="3"><input class="manual boxed"></td>

    <td colspan="6">OP No.</td>
    <td colspan="3"><input class="manual" value="<?= $excel->production_order ?>" readonly></td>

    <td colspan="4">Standard NSL (If any)</td>
    <td colspan="4"><input class="manual boxed"></td>
</tr>
<!-- ROW 2 -->
<tr>
    <td colspan="2">Type</td>
   <td colspan="3">
    <input class="manual" value="<?= htmlspecialchars($type) ?>" readonly>
</td>
    <td colspan="6">SOP No.</td>
    <td colspan="3"><input class="manual" value="<?= $excel->sales_order ?>" readonly></td>

    <td colspan="4">
    <div style="display:flex; gap:8px; align-items:center;">
        <span>Kap. Drum</span>
        <span class="bold">
            <?php
            $std = json_decode($excel->standard_description ?? '[]', true);
            if (!empty($std)) {
                preg_match('/\b(\d{2,4})\b/', $std[0], $m);
                echo $m[1] ?? '';
            }
            ?>
        </span>
    </div>
</td>
<td colspan="4">
    <input class="manual"
           value="<?= number_format($excel->quantity_lot) ?>"
           readonly>
</td>
</tr>
<!-- ROW 3 -->
<tr>
    <td colspan="2">Size</td>
    <td colspan="3">
    <input class="manual" value="<?= htmlspecialchars($size) ?>" readonly>
</td>
    <td colspan="6">Customer</td>
    <td colspan="3"><input class="manual" value="<?= $excel->customer_name ?>" readonly></td>

  <td colspan="4">Qty @ Drum</td>
<td colspan="4">
    <input id="qty_per_drum_result"
           class="manual"
           readonly>
</td>
</td>
</tr>
<!-- ROW 4 -->
<tr>
    <td colspan="2">Diameter Insul</td>
    <td colspan="3">
  <input
    id="diameter_min"
    class="manual left"
    value="<?= number_format((float)$diameter_insul_spec_min, 2) ?>"
    readonly>
</td>
    <td colspan="6">Qty Order</td>
    <td colspan="3"><input class="manual" value="<?= number_format($excel->ordered_quantity) ?>" readonly></td>

    <?php
$qtyInsulNum = (float) str_replace(',', '', $qty_insulation);
$stdLength  = (float) $excel->quantity_lot;
$qtyPerDrum = $stdLength * 1.001;

$totalDrum = ($qtyPerDrum > 0)
    ? floor($qtyInsulNum / $qtyPerDrum)
    : '';
?>
   <td colspan="4">Total Drum</td>
<td colspan="4">
    <input id="total_drum_result"
           class="manual"
           readonly>
</td>
</tr>
<!-- ROW 5 -->
<tr>
    <td colspan="2">Material</td>
    <td colspan="3"><input class="manual" value="<?= htmlspecialchars($material_insulation) ?>" readonly>
<td colspan="6">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
        <span>Qty Insulation</span>

        <div>
            Factor Scrap
         <input id="factor_scrappp" oninput="hitungQtyInsul_Insul()">

        </div>
    </div>
</td>

<td colspan="3">
    <input id="qty_insulation_result"
           class="manual"
           value="<?= $qty_insulation ?>"
           readonly>
</td>

    <td colspan="4">Item Code</td>
    <td colspan="4"><input class="manual" value="<?= $excel->item ?>" readonly></td>
</tr>


<!-- ROW 6 -->
<tr>
    <td colspan="2">Cable Marking</td>
    <td colspan="3"
    class="cable-marking-cell"
    data-text="<?= htmlspecialchars($cable_marking) ?>">

    <input class="manual cable-marking-input"
           value="<?= htmlspecialchars($cable_marking) ?>"
           readonly>

</td>

    <td colspan="4">Standard Length</td>
    <td colspan="8">
        <input class="manual"
               value="<?= number_format($excel->quantity_lot) ?>"
               readonly>
    </td>

    <td colspan="1">Jumlah Core</td>
    <td colspan="1">
      <input id="jumlah_coreee" oninput="hitungQtyInsul_Insul()">
    </td>
</tr>
<table class="spk">
    <tr>
        <td width="11.4%" class="center bold">Remark</td>
        <td colspan="13" contenteditable="true" class="left"></td>
    </tr>
</table>
</table>
<!-- CHECK SHEET -->
<table class="check-sheet">
    <thead>
        <tr class="gray center bold">
    <td colspan="21">CHECK SHEET</td>
</tr>
    <!-- HEADER BARIS 1 -->
    <tr class="center bold gray">
        <td rowspan="3">Tgl /<br>Shift</td>
        <td colspan="4">Input</td>
        <td colspan="14">Output</td>
        <td rowspan="3">Operator</td>
        <td rowspan="3">Foreman</td>
    </tr>

    <!-- HEADER BARIS 2 -->
   <tr class="center bold small gray">
    <td rowspan="2">Drum<br>Size - No</td>

    <!-- Length (m) di baris atas -->
    <td class="len-head">Length (m)</td>

    <td rowspan="2">Material Code</td>
    <td rowspan="2">Lot Material</td>

    <td rowspan="2">Drum<br>Size - No.</td>
    <td rowspan="2" colspan="2">Length (m)</td>
  <td colspan="4" style="padding:0; border-bottom:0;">
    <table style="width:100%; border-collapse:collapse; border:0;">
        <tr>
            <td style="
                border-bottom:1px solid #000;
                border-top:0;
                border-left:0;
                border-right:0;
            ">
                Thickness
            </td>
        </tr>
        <tr>
            <td style="
                font-size:9px;
                border:0;
            ">
                mm
            </td>
        </tr>
    </table>
</td>


  <td colspan="3" style="padding:0; border-bottom:0;">
    <table style="width:100%; border-collapse:collapse; border:0;">
        <tr>
            <td style="
                border-bottom:1px solid #000;
                border-top:0;
                border-left:0;
                border-right:0;
            ">
                Diameter Insul
            </td>
        </tr>
        <tr>
            <td class="small" style="border:0;">
                mm
            </td>
        </tr>
    </table>
</td>

 <td rowspan="2" style="padding:0;">
    <table style="width:100%; height:100%; border-collapse:collapse; border:0;">
        <tr>
            <td style="
                border-bottom:1px solid #000;
                border-top:0;
                border-left:0;
                border-right:0;
                padding:2px;
            ">
                Spark test
            </td>
        </tr>
        <tr>
            <td style="font-size:9px; border:0; padding:2px;">
                (OK / NOK)
            </td>
        </tr>
    </table>
</td>

    <td colspan="3">Visual</td>
</tr>
    <!-- HEADER BARIS 3 -->
   <tr class="center bold small gray">
    <!-- mm tepat di bawah Length (m), MASIH SATU KOLOM -->
    <td class="len-unit">mm</td>

    <td>Min</td>
    <td>Max</td>
    <td>Avg</td>
    <td>Centring<br>(%)</td>

    <td>Min</td>
    <td>Max</td>
    <td>Avg</td>

    <td>Marking<br>(OK / NOK)</td>
    <td>Mudah kupas<br>(Y / N)</td>
    <td>Kedua Ujung tidak basah<br>(Y / N)</td>
</tr>


    <!-- SPEC -->
    <tr class="gray bold center small">
        <td>SPEC</td>
        <td></td><td></td><td></td><td></td>
        <td></td>
        <td>Standard</td><td>Actual</td>
        <!-- Thickness Min (AUTO dari TDS) -->
<td>
  <input
    id="thickness_min"
    class="manual center"
    value="<?= number_format((float)$thickness_spec_min, 2) ?>"
    readonly>

</td>

<!-- Thickness Max (INPUT MANUAL) -->
<td>
    <input
        id="thickness_max"
        class="manual center"
        oninput="hitungAvgThickness()">
</td>

<!-- Thickness Avg (AUTO) -->
<td>
    <input
        id="thickness_avg"
        class="manual center"
        readonly>
</td>

<!-- Centring -->
<!-- Centring (%) -->
<td>
    <input
        id="thickness_centring"
        class="manual center"
        readonly>
</td>


      <!-- Diameter Insul Min (AUTO dari TDS) -->
<td>
  <input
    id="diameter_min"
    class="manual center"
    value="<?= number_format((float)$diameter_insul_spec_min, 2) ?>"
    readonly>

</td>

<!-- Diameter Insul Max (INPUT MANUAL) -->
<td>
    <input
        id="diameter_max"
        class="manual center"
        oninput="hitungAvgDiameter()">
</td>

<!-- Diameter Insul Avg (AUTO) -->
<td>
    <input
        id="diameter_avg"
        class="manual center"
        readonly>
</td>

        <td></td>
        <td></td><td></td><td></td>
        <td></td><td></td>
    </tr>
    </thead>
<tbody>

    <?php
   $drums = json_decode($excel->standard_description ?? '[]', true);
$lots  = json_decode($excel->lot ?? '[]', true);

    if (!empty($drums)):
        $counter = [];
       foreach ($drums as $i => $drum):
            $counter[$drum] = ($counter[$drum] ?? 0) + 1;
            $label = $drum . '-' . $counter[$drum];

            $no   = $i + 1;
$size = preg_replace('/\D/', '', $drum); // ambil angka saja
$lot  = $lots[$i] ?? '';

    ?>
    <tr class="h25 center">
   <td><input class="manual"></td>
<td><input class="manual" placeholder=""></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
   <td class="center bold">
    <?= $no ?>&nbsp;&nbsp;<?= $size ?>&nbsp;&nbsp;<?= $lot ?>
</td>
  <td class="center">
    <input class="manual center bold"
           value="<?= number_format($qtyPerDrum) ?>"
           readonly>
</td>

    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
    <!-- Spark test -->
    <td>
        <select class="manual">
            <option value=""></option>
            <option value="OK">OK</option>
            <option value="NOK">NOK</option>
        </select>
    </td>
    <!-- Marking -->
    <td>
        <select class="manual">
            <option value=""></option>
            <option value="OK">OK</option>
            <option value="NOK">NOK</option>
        </select>
    </td>
    <!-- Mudah kupas -->
    <td>
        <select class="manual">
            <option value=""></option>
            <option value="Y">Y</option>
            <option value="N">N</option>
        </select>
    </td>

    <!-- Kedua ujung tidak basah -->
    <td>
        <select class="manual">
            <option value=""></option>
            <option value="Y">Y</option>
            <option value="N">N</option>
        </select>
    </td>
    <td><input class="manual"></td>
    <td><input class="manual"></td>
</tr>
    <?php endforeach; else: ?>
    <tr>
        <td colspan="22" class="center">Tidak ada data Drum</td>
    </tr>
    <?php endif; ?>
<tfoot>
    <tr class="h40">
    <td colspan="7" contenteditable="true" style="height:80px; vertical-align:top; padding: 5px;">
        Date :
    </td>
    <td colspan="7" contenteditable="true" style="vertical-align:top; padding: 5px;">
        Made Out and Approved by :
    </td>
    <td colspan="7" contenteditable="true" style="vertical-align:top; padding: 5px;">
        Checked by :
    </td>
</tr>
</tr>
</tfoot>
</table>
    </div>
 <script>
function hitungAvgThickness() {
    const minEl = document.getElementById('thickness_min');
    const maxEl = document.getElementById('thickness_max');
    const avgEl = document.getElementById('thickness_avg');
    const cenEl = document.getElementById('thickness_centring');

    if (!minEl || !maxEl || !avgEl || !cenEl) return;

    const min = parseFloat(minEl.value);
    const max = parseFloat(maxEl.value);

    if (!isNaN(min) && !isNaN(max) && max !== 0) {
        // ✅ AVG BOLEH DESIMAL
        avgEl.value = ((min + max) / 2).toFixed(2);

        // ✅ CENTRING BULAT (FIXED 0)
        cenEl.value = Math.round((min / max) * 100) + ' %';
    } else {
        avgEl.value = '';
        cenEl.value = '';
    }
}
</script>
<script>
function hitungAvgDiameter() {
    const minEl = document.getElementById('diameter_min');
    const maxEl = document.getElementById('diameter_max');
    const avgEl = document.getElementById('diameter_avg');

    if (!minEl || !maxEl || !avgEl) return;

    const min = parseFloat(minEl.value);
    const max = parseFloat(maxEl.value);

    if (!isNaN(min) && !isNaN(max)) {
        avgEl.value = ((min + max) / 2).toFixed(2);
    } else {
        avgEl.value = '';
    }
}
</script>
<script>
function hitungQtyInsul_Insul(){

    var qtyOrder =
    <?= (float)$excel->ordered_quantity ?>;

    var scrap =
    parseFloat(
        document.getElementById("factor_scrappp").value
    ) || 0;

    var core =
    parseFloat(
        document.getElementById("jumlah_coreee").value
    ) || 0;

    var hasil = 0;

    if(scrap > 0 && core > 0){

        hasil =
        Math.round(
            qtyOrder *
            scrap *
            core
        );

    }

    document.getElementById(
        "qty_insulation_result"
    ).value =
    hasil.toLocaleString('en-US');

  hitungQtyPerDrum_Insul();
hitungTotalDrum_Insul();

}

function hitungQtyPerDrum_Insul(){

    var standardLength =
        <?= (float)$excel->quantity_lot ?>;

    var scrap =
        parseFloat(
            document.getElementById("factor_scrappp").value
        ) || 0;

    var hasil = 0;

    if(standardLength > 0 && scrap > 0){
        hasil = standardLength * scrap;
    }

    document.getElementById("qty_per_drum_result").value =
        hasil ? Math.round(hasil).toLocaleString('en-US') : '';
        
}

function hitungTotalDrum_Insul(){

    var qtyInsulText =
        document.getElementById("qty_insulation_result").value
        .replace(/,/g,'');

    var qtyInsul = parseFloat(qtyInsulText) || 0;

    var qtyPerDrumText =
        document.getElementById("qty_per_drum_result").value
        .replace(/,/g,'');

    var qtyPerDrum = parseFloat(qtyPerDrumText) || 0;

    var total = 0;

    if(qtyInsul > 0 && qtyPerDrum > 0){
    total = qtyInsul / qtyPerDrum;
}

document.getElementById("total_drum_result").value =
    total ? parseInt(total) : '';
}

</script>


</body>
</html>
