<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>WORK ORDER & CHECK SHEET STRANDING</title>
    <style>
        @page {
            size: A4 landscape;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #000;
        }

        .padHeader>td {
            padding: 3px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 0;
            height: 22px;
            line-height: 22px;
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .gray {
            background: #e6e6e6;
            font-weight: bold;
        }

        .table-input {
            width: 100%;
            height: 22px;
            border: none;
            outline: none;
            padding: 0;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            background: transparent;
        }

        .table-input-left {
            text-align: left;
        }

        .locked {
            pointer-events: none;
            cursor: not-allowed;
            background: transparent;
        }

        .spec td {
            background: #e6e6e6;
            font-weight: bold;
        }

        .checksheet-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .visual-text {
            display: none;
        }

        @media print {

            input,
            select,
            textarea {
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;

                appearance: none !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
            }

            body * {
                visibility: hidden !important;
            }

            #print-area,
            #print-area * {
                visibility: visible !important;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button,
            input[type="file"] {
                display: none !important;
            }

            .checksheet-wrapper {
                overflow: visible !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            .freeze-1,
            .freeze-2,
            .freeze-3,
            .freeze-4 {
                position: static !important;
            }

            .visual-select {
                display: none !important;
            }

            .visual-text {
                display: inline-block !important;
                width: 100%;
                text-align: center;
                font-weight: bold;
            }
        }

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

        .drum-size-no {
            text-align: center;
        }

        .boxed-input {
            border: 1px solid #000;
            box-sizing: border-box;
            border-radius: 4px;
            /* ← INI YANG BIKIN BULAT */
        }

        .boxed-select {
            border: 1px solid #000;
            box-sizing: border-box;
        }

        /* 🔥 BOLD DRUM SIZE - NO */
        .drum-size-no {
            font-weight: bold;
        }

        /* 🔥 BOLD LENGTH STANDARD */
        .length-std {
            font-weight: bold;
        }

        /* 🔥 PADDING KHUSUS INPUT DI BARIS SPEC */
        .spec .table-input {
            padding: 0 4px;
            /* kiri-kanan ada jarak */
            line-height: 20px;
            /* biar teks center vertikal */
        }

        /* 🔥 PADDING INPUT KOTAK (Machine No, NSL, Remark) */
        .boxed-input {
            padding: 0 6px;
            /* jarak kiri-kanan */
            line-height: 20px;
            /* biar teks gak nempel atas */
        }
    </style>
    <div style="margin-bottom:10px;">
    </div>
</head>

<body>
    <div id="print-area">
        <div class="checksheet-wrapper">
            <table class="spk-table">
                <colgroup>
                    <col style="width:80px">
                    <col style="width:50px">
                    <col style="width:60px">
                    <col style="width:60px">
                    <col style="width:90px">
                    <col style="width:60px">
                    <col style="width:60px">
                    <col style="width:80px">
                    <col style="width:80px">
                    <col style="width:35px">
                    <col style="width:35px">
                    <col style="width:35px">
                    <col style="width:35px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:45px">
                    <col style="width:70px">
                    <col style="width:70px">
                </colgroup>
                <tr class="padHeader">
                    <td colspan="28" class="bold">
                        <div style="display:flex; justify-content:space-between;">
                            <span>PT JEMBO CABLE COMPANY Tbk</span>
                            <span>Lampiran JCC-MV-PS-001-F001</span>
                        </div>
                    </td>
                </tr>
                <tr class="padHeader">
                    <td colspan="28" class="bold">
                        WORK ORDER & CHECK SHEET STRANDING PROCESS
                    </td>
                </tr>
                <tr>
                    <td colspan="28" class="center gray">WORK ORDER</td>
                </tr>

                <!-- BARIS 1 -->
                <tr class="padHeader">
                    <td colspan="2">Machine No.</td>
                    <td colspan="3"><input class="table-input boxed-input"></td>
                    <td colspan="4">OP No.</td>
                    <td colspan="5"><?= htmlspecialchars($excel->production_order) ?></td>
                    <td colspan="4">Standard NSL (If any)</td>
                    <td colspan="8"><input class="table-input boxed-input"></td>
                </tr>

                <tr class="padHeader">
                    <td colspan="2">Type</td>
                    <td colspan="3"><?= $type ?></td>
                    <td colspan="4">SOP No.</td>
                    <td colspan="5"><?= htmlspecialchars($excel->sales_order) ?></td>
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

                    <td colspan="8">
                        <input
                            class="table-input boxed-input center"
                            value="<?= number_format($excel->quantity_lot) ?>"
                            readonly>
                    </td>

                <tr class="padHeader">
                    <td colspan="2">Size</td>
                    <td colspan="3"><?= $size ?></td>
                    <td colspan="4">Customer</td>
                    <td colspan="5"><?= htmlspecialchars($excel->customer_name) ?></td>
                    <td colspan="4">Qty @ drum</td>
                    <td colspan="8">
                        <input id="qty_per_drum_g2"
                            class="table-input boxed-input center"
                            readonly>
                    </td>
                </tr>

                <tr class="padHeader">
                    <td colspan="2">Konduktor Diameter</td>
                    <td colspan="3">
                        <?php
                        $lastCond = '-';

                        if (!empty($cond4_nom) && $cond4_nom != '-') {
                            $lastCond = $cond4_nom;
                        } elseif (!empty($cond3_nom) && $cond3_nom != '-') {
                            $lastCond = $cond3_nom;
                        } elseif (!empty($cond2_nom) && $cond2_nom != '-') {
                            $lastCond = $cond2_nom;
                        } elseif (!empty($cond1_nom) && $cond1_nom != '-') {
                            $lastCond = $cond1_nom;
                        }

                        echo $lastCond;
                        ?>
                    </td>
                    <td colspan="4">Qty Order</td>
                    <td colspan="5"><?= number_format($excel->ordered_quantity) ?></td>
                    <td colspan="4">Total Drum</td>
                    <td colspan="8">
                        <input id="total_drum_g2"
                            class="table-input boxed-input center"
                            readonly>
                    </td>

                </tr>
                <?php
                $qtyOrder = (float) str_replace(',', '', $excel->ordered_quantity);
                $qtyStranding = $qtyOrder; // default sebelum scrap

                ?>

                <tr class="padHeader">
                    <td colspan="2">Material</td>
                    <td colspan="3"><?= $material ?></td>

                    <td colspan="4">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span>Qty Stranding</span>
                        </div>
                    </td>
                    <td colspan="5" class="left bold">

                       <span class="qty_stranding">0 m</span>
                    </td>


                    <td colspan="4">Factor Scrap</td>
                    <td colspan="8">
                        <input
                            id="factor_scrapppp"
                            type="number"
                            class="table-input boxed-input center"
                            oninput="hitungQtyStrandingG2()">
                    </td>

                </tr>

                <tr class="padHeader">
                    <td colspan="2">Item Code</td>
                    <td colspan="3"><?= htmlspecialchars($excel->item) ?></td>
                    <td colspan="4">Standard Length</td>
                    <td colspan="16" id="standardLength_g2">
                        <?= number_format($excel->quantity_lot) ?>
                    </td>

                </tr>

                <tr class="padHeader">
                    <td colspan="2">Jumlah Core</td>
                    <td colspan="3">
                        <input
                            id="jumlah_coreeee"
                            type="number"
                            class="table-input boxed-input table-input-left"
                            oninput="hitungQtyStrandingG2()">
                    </td>

                    <td colspan="4">Remark</td>
                    <td colspan="16">
                        <input class="table-input boxed-input table-input-left">
                    </td>
                </tr>
            </table>
            <!-- CHECK SHEET TITLE -->
            <table>
                <thead>
                    <tr class="gray">
                        <td colspan="28" class="center bold">CHECK SHEET</td>
                    </tr>

                    <!-- BARIS 1 -->
                    <tr class="center bold gray">
                        <td rowspan="4">Date /<br>Shift</td>
                        <td colspan="3">Input</td>
                        <td colspan="24">Output</td>

                    </tr>

                    <!-- BARIS 2 -->
                    <tr class="center bold gray">
                        <td rowspan="3">Drum<br>No.</td>
                        <td rowspan="2">Length (M)</td>
                        <td rowspan="2">Diameter Wire<br>(mm)</td>
                        <td rowspan="3">Drum Size - No</td>
                        <td colspan="2">Length (M)</td>
                        <td rowspan="3">Construction</td>
                        <td rowspan="3">Shape of<br>conductor</td>
                        <td colspan="4">Dir of lay</td>
                        <td colspan="4">Lay pitch (mm)</td>
                        <td colspan="4">Cond. Diameter (mm)</td>
                        <td rowspan="3">Visual<br>(OK/<br>NOK)</td>
                        <td rowspan="3">Operator</td>
                        <td rowspan="3">Foreman</td>
                    </tr>

                    <!-- BARIS 3 -->
                    <tr class="center bold gray">
                        <td rowspan="2">Standard</td>
                        <td rowspan="2">Actual</td>

                        <td rowspan="2">Layer 1</td>
                        <td rowspan="2">Layer 2</td>
                        <td rowspan="2">Layer 3</td>
                        <td rowspan="2">Layer 4</td>


                        <td>Layer 1</td>
                        <td>Layer 2</td>
                        <td>Layer 3</td>
                        <td>Layer 4</td>

                        <td rowspan="2">Layer 1<br>Nom.</td>
                        <td rowspan="2">Layer 2<br>Nom.</td>
                        <td rowspan="2">Layer 3<br>Nom.</td>
                        <td rowspan="2">Layer 4<br>Nom.</td>
                    </tr>

                    <!-- BARIS 4 -->
                    <tr class="center bold gray">
                        <td>Actual</td>
                        <td>Nom.</td>
                        <td>Range</td>
                        <td>Range</td>
                        <td>Range</td>
                        <td>Range</td>

                    <tr class="spec center">
                        <td>SPEC</td>
                        <td></td>
                        <td></td>
                        <td><?= $min_diameter ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?= htmlspecialchars($construction) ?></td>
                        <td>RM - CM - SM</td>
                        <td><?= $dir_lay_1 ?></td>
                        <td><?= $dir_lay_2 ?></td>
                        <td><?= $dir_lay_3 ?></td>
                        <td><?= $dir_lay_4 ?? '-' ?></td>
                        <td><?= $lp_range_1 ?></td>
                        <td><?= $lp_range_2 ?></td>
                        <td><?= $lp_range_3 ?></td>
                        <td><?= $lp_range_4 ?? '-' ?></td>
                        <td><?= $cond1_nom ?></td>
                        <td><?= $cond2_nom ?></td>
                        <td><?= $cond3_nom ?></td>
                        <td><?= $cond4_nom ?? '-' ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>

                <tbody>

                    </tr>


                    <?php
                    $drums = json_decode($excel->standard_description ?? '[]', true);

                    if (!empty($drums)):

                        $counter = [];   // 🔥 TAMBAHKAN INI


                        foreach ($drums as $i => $drum):

                            $no = $i + 1;
                            $size = preg_replace('/\D/', '', $drum);
                            $lots  = json_decode($excel->lot ?? '[]', true);
                            $lot = $lots[$i] ?? '';

                            if (!isset($counter[$drum])) {
                                $counter[$drum] = 1;
                            } else {
                                $counter[$drum]++;
                            }


                    ?>
                            <tr class="drum-row-g2">
                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td>
                                <td class="center visual-cell">
                                    <select class="table-input center visual-select"
                                        onchange="this.nextElementSibling.innerText = this.value">
                                        <option value=""></option>
                                        <option value="OK">OK</option>
                                        <option value="NOK">NOK</option>
                                    </select>
                                    <span class="visual-text"></span>
                                </td>

                                <td class="center drum-size-no bold">
                                    <?= $no ?>&nbsp;&nbsp;<?= $size ?>&nbsp;&nbsp;<?= $lot ?>
                                </td>
                                <td class="center">
                                   <input class="table-input length-std center bold length_standard_drum"
readonly>
                                </td>
                                <td><input class="table-input"></td>
                                <td class="center visual-cell">
                                    <select class="table-input center visual-select"
                                        onchange="this.nextElementSibling.innerText = this.value">
                                        <option value=""></option>
                                        <option value="OK">OK</option>
                                        <option value="NOK">NOK</option>
                                    </select>
                                    <span class="visual-text"></span>
                                </td>

                                <td class="center visual-cell">
                                    <select class="table-input center visual-select"
                                        onchange="this.nextElementSibling.innerText = this.value">
                                        <option value=""></option>
                                        <option value="RM">RM</option>
                                        <option value="CM">CM</option>
                                        <option value="SM">SM</option>
                                    </select>
                                    <span class="visual-text"></span>
                                </td>

                                <!-- DIR OF LAY -->
                                <?php for ($i = 0; $i < 4; $i++): ?>
                                    <td class="center visual-cell">
                                        <select class="table-input center visual-select"
                                            onchange="this.nextElementSibling.innerText = this.value">
                                            <option value=""></option>
                                            <option value="S">S</option>
                                            <option value="Z">Z</option>
                                        </select>
                                        <span class="visual-text"></span>
                                    </td>
                                <?php endfor; ?>

                                <!-- SISANYA TETAP INPUT -->
                                <?php for ($i = 0; $i < 4; $i++): ?>
                                    <td><input class="table-input"></td>
                                <?php endfor; ?>


                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td> <!-- ini layer 4 -->
                                <td class="center visual-cell">
                                    <select class="table-input center visual-select"
                                        onchange="this.nextElementSibling.innerText = this.value">
                                        <option value=""></option>
                                        <option value="OK">OK</option>
                                        <option value="NOK">NOK</option>
                                    </select>
                                    <span class="visual-text"></span>
                                </td>

                                <td><input class="table-input"></td>
                                <td><input class="table-input"></td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="28" class="center">Tidak ada data Drum Size</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10" style="height:70px; vertical-align:top; padding: 5px;">Date :</td>
                        <td colspan="9" style="vertical-align:top; padding: 5px;">Made Out and Approved by :</td>
                        <td colspan="9" style="vertical-align:top; padding: 5px;">Checked by :</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
   <script>

document.addEventListener("DOMContentLoaded", function(){

const scrapInput = document.getElementById("factor_scrapppp");
const coreInput  = document.getElementById("jumlah_coreeee");

if(scrapInput){
scrapInput.addEventListener("input", hitungQtyStrandingG2);
}

if(coreInput){
coreInput.addEventListener("input", hitungQtyStrandingG2);
}

/* jalankan sekali saat halaman buka */
hitungQtyStrandingG2();

});


function hitungQtyStrandingG2(){

const qtyOrder = <?= (float)$excel->ordered_quantity ?>;

const scrap = parseFloat(document.getElementById("factor_scrapppp").value) || 1;
const core  = parseFloat(document.getElementById("jumlah_coreeee").value) || 1;

const hasil = qtyOrder * scrap * core;

/* update span qty stranding */
document.querySelectorAll(".qty_stranding").forEach(el=>{
el.textContent = Math.round(hasil).toLocaleString('en-US') + " m";
});

hitungQtyPerDrumG2();
hitungTotalDrumG2();

}


function hitungQtyPerDrumG2(){

const standardLength = <?= (float)$excel->quantity_lot ?>;

const scrap = parseFloat(document.getElementById("factor_scrapppp").value) || 1;

let hasil = 0;

if(standardLength > 0){
hasil = standardLength * scrap;
}

document.getElementById("qty_per_drum_g2").value =
hasil ? Math.round(hasil).toLocaleString('en-US') : "";

document.querySelectorAll(".length_standard_drum").forEach(el=>{
el.value = hasil ? Math.round(hasil).toLocaleString('en-US') : "";
});

}


function hitungTotalDrumG2(){

const qtyStrandingText =
document.querySelector(".qty_stranding")
.innerText.replace(/,/g,'')
.replace(' m','');

const qtyStranding = parseFloat(qtyStrandingText) || 0;

const qtyPerDrumText =
document.getElementById("qty_per_drum_g2").value
.replace(/,/g,'');

const qtyPerDrum = parseFloat(qtyPerDrumText) || 0;

let total = 0;

if(qtyStranding > 0 && qtyPerDrum > 0){
total = qtyStranding / qtyPerDrum;
}

document.getElementById("total_drum_g2").value =
total ? Math.floor(total) : "";

}

</script>
</body>

</html>