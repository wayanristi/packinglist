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
                        Kap. Drum
                        <select class="table-input boxed-select visual-select"
                            id="kapDrumSelect"
                            onchange="hitungKapDrum()">

                            <option value="">-- Pilih --</option>

                            <option value="547733">1250 T</option>
                            <option value="570789">1250 HT</option>
                            <option value="615732">1250 I</option>

                            <option value="978954">1600 I</option>
                            <option value="1138856">1600 S</option>
                            <option value="1275364">1600 H</option>
                            <option value="1237979">1600 B</option>

                            <option value="1566166">1650 H</option>

                            <option value="1832919">2000 C</option>
                            <option value="1738703">2000 A</option>
                            <option value="2354988">2000 K</option>
                            <option value="2403324">2000 B</option>
                            <option value="2042825">2000 ABU</option>

                            <option value="2929193">2250 OR</option>
                            <option value="3645041">2600 PB</option>
                            <option value="4510739">2600 PK</option>

                        </select>
                        <span class="visual-text"></span>
                    </td>
                    <td colspan="8">
                        <input
                            class="table-input boxed-input center"
                            id="kapDrumInput"
                            readonly>
                    </td>
                    <?php
                    $qtyOrder = (float) str_replace(',', '', $excel->ordered_quantity);


                    // kap drum nanti dari input user
                    $kapDrum = 8; // contoh dulu (nanti bisa diganti dari input)

                    $mainDrum = intdiv($qtyOrder, $kapDrum);
                    $sisaDrum = $qtyOrder % $kapDrum;
                    ?>

                <tr class="padHeader">
                    <td colspan="2">Size</td>
                    <td colspan="3"><?= $size ?></td>
                    <td colspan="4">Customer</td>
                    <td colspan="5"><?= htmlspecialchars($excel->customer_name) ?></td>
                    <td colspan="4">Total length @ drum</td>
                    <td class="center" id="totalLengthDrum"></td>
                    <td colspan="8" class="left">
                        Qty @ drum
                        <span id="qtyDrumHeader" style="float:right; font-weight:bold;"></span>
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
                    <td class="center" id="totalDrumMain"></td>
                    <td colspan="8" class="right" id="qtyPerDrum"></td>
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

                        <span id="qty_stranding">0</span> m
                    </td>


                    <td colspan="4"></td>
                    <td class="center" id="totalDrumSisa"></td>
                    <td colspan="8" class="right" id="qtySisa"></td>

                </tr>

                <tr class="padHeader">
                    <td colspan="2">Item Code</td>
                    <td colspan="3"><?= htmlspecialchars($excel->item) ?></td>
                    <td colspan="4">Standard Length</td>
                    <td colspan="5" id="standardLength">
                        <?= number_format($excel->quantity_lot) ?>
                    </td>
                    <td colspan="4">Factor Scrap</td>
                    <td colspan="8">
                        <input type="number"
                            id="factor_scrapp"
                            class="table-input boxed-input center"
                            oninput="hitungQtyStranding()">
                    </td>
                </tr>
                <tr class="padHeader">
                    <td colspan="2">Jumlah Core</td>
                    <td colspan="3">
                        <input id="jumlah_coree"
                            type="number"
                            class="table-input boxed-input table-input-left"
                            oninput="hitungQtyStranding()">
                    </td>

                    <td colspan="4"></td>
                    <td colspan="5"></td>

                    <td colspan="4">Remark</td>
                    <td colspan="8">
                        <input class="table-input boxed-input">
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
                        $counter = [];

                        foreach ($drums as $drum):

                            if (!isset($counter[$drum])) {
                                $counter[$drum] = 1;
                            } else {
                                $counter[$drum]++;
                            }

                            $label = $drum . '-' . $counter[$drum];
                    ?>
                            <tr class="drum-row">
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

                                <td>
                                    <input class="table-input drum-no locked drum-size-no" readonly>
                                </td>

                                <td> <input class="table-input length-std center"></td>
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
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.drum-row').forEach(row => {
                row.style.display = 'none';
            });
        });

        // mengambil nilai core dan scrap untuk QTY STRANDING
        function hitungQtyStranding() {

            const qtyOrder = <?= (float) str_replace(',', '', $excel->ordered_quantity) ?>;

            const jumlahCore = Number(document.getElementById('jumlah_coree').value) || 0;
            const scrap = Number(document.getElementById('factor_scrapp').value) || 0;
            const hasil = jumlahCore * qtyOrder * scrap;

            // 🔥 Bulatkan biar nggak ada 999999
            const hasilBulat = Math.round(hasil);

            document.getElementById('qty_stranding').innerText =
                hasilBulat.toLocaleString('en-US');
            hitungTotalDrum();
        }

        function hitungKapDrum() {

            const konstanta = parseFloat(
                document.getElementById('kapDrumSelect').value
            );

            const condDiameter =
                <?= json_encode((float)$lastCond) ?>;

            if (!konstanta || !condDiameter) return;

            // rumus utama
            const hasil =
                Math.round(
                    konstanta /
                    (condDiameter * condDiameter)
                );

            document.getElementById('kapDrumInput').value = hasil;

            // lanjut hitung drum otomatis
            hitungTotalDrum();
        }

        function hitungTotalDrum() {
            const qtyOrder = <?= (float) str_replace(',', '', $excel->ordered_quantity) ?>;
            const qtyStranding =
                parseFloat(
                    document.getElementById('qty_stranding')
                    .innerText.replace(/,/g, '')
                ) || 0;
            const kapDrum = parseFloat(document.getElementById('kapDrumInput').value);

            // ambil standard length
            const standardLength =
                parseFloat(
                    document.getElementById('standardLength')
                    .innerText.replace(/,/g, '')
                );

            // factor scrap manual
            const scrap =
                parseFloat(document.getElementById('factor_scrapp').value) || 1;

            let totalLengthDrum = 0;

            if (kapDrum && standardLength) {
                totalLengthDrum =
                    Math.floor(
                        (kapDrum / standardLength) * scrap
                    );
            }

            document.getElementById('totalLengthDrum').innerText =
                totalLengthDrum > 0 ?
                totalLengthDrum.toLocaleString('en-US') :
                '';

            const scrapInput =
                parseFloat(document.getElementById('factor_scrapp').value);

            if (!isNaN(scrapInput) && totalLengthDrum > 0 && standardLength > 0) {
                const qtyHeader =
                    totalLengthDrum *
                    standardLength *
                    scrapInput;

                document.getElementById('qtyDrumHeader').innerText =
                    Math.round(qtyHeader).toLocaleString('en-US');
            } else {
                document.getElementById('qtyDrumHeader').innerText = '';
            }

            if (totalLengthDrum && standardLength && scrap) {
                const qtyHeader =
                    totalLengthDrum *
                    scrap *
                    standardLength;

                document.getElementById('qtyDrumHeader').innerText =
                    Math.round(qtyHeader).toLocaleString('en-US');
            }

            const cellMain = document.getElementById('totalDrumMain');
            const cellSisa = document.getElementById('totalDrumSisa');
            const cellQty = document.getElementById('qtyPerDrum');
            const cellCuk = document.getElementById('qtySisa');

            // reset
            cellMain.innerText = '';
            cellSisa.innerText = '';
            cellQty.innerText = '';
            cellCuk.innerText = '';

            if (!kapDrum || kapDrum <= 0) {
                document.querySelectorAll('.drum-row').forEach(row => {
                    row.style.display = 'none';
                });
                return;
            }

            // ===== TOTAL DRUM UTAMA =====
            const drumUtama = Math.floor(qtyStranding / kapDrum);
            cellMain.innerText = `: ${drumUtama}`;

            let qtyPerDrum = 0;

            const qtyHeaderText =
                document.getElementById('qtyDrumHeader').innerText.replace(/,/g, '');

            const qtyHeader = parseFloat(qtyHeaderText) || 0;

            // 🔥 TAMPILKAN SELAMA ADA ANGKA DI ATAS
            if (qtyHeader > 0) {
                qtyPerDrum = qtyHeader;
                cellQty.innerText = qtyHeader.toLocaleString('en-US');
            } else {
                cellQty.innerText = '';
            }

            // ===== TOTAL DRUM SISA =====
            // ===== TOTAL DRUM SISA (FIX) =====
          // ===== TOTAL DRUM SISA (BENAR) =====

// qty drum utama = angka Qty @ Drum (kanan atas)
const qtyDrumUtama = qtyHeader;

// rumus sesuai teori kamu
const sisaMeter =
    qtyStranding - (drumUtama * qtyDrumUtama);
            let drumSisa = 0;
            if (sisaMeter > 0) {
                drumSisa = 1; // kalau ada sisa sekecil apapun tetap 1 drum
            }

            cellSisa.innerText = `: ${drumSisa}`;

           let qtyCuk = 0;

if (drumSisa > 0) {
    qtyCuk = sisaMeter / drumSisa;
}
            cellCuk.innerText = drumSisa > 0 ?
                Math.round(qtyCuk).toLocaleString('en-US') :
                '';
            cellCuk.innerText = Math.round(qtyCuk).toLocaleString('en-US');

            const totalDrumReal = drumUtama + drumSisa;

            const drumRows = document.querySelectorAll('.drum-row');
            const container = drumRows[0].parentElement;
            const select = document.getElementById('kapDrumSelect');
            const drumLabel = select.options[select.selectedIndex].text;

            // Auto-clone biar baris ga mentok
            if (totalDrumReal > drumRows.length) {
                for (let i = drumRows.length; i < totalDrumReal; i++) {
                    let newRow = drumRows[0].cloneNode(true);
                    container.appendChild(newRow);
                }
            }

            const allRows = document.querySelectorAll('.drum-row');
            let hitungSisa = 1; // Counter khusus buat drum sisa

            allRows.forEach((row, index) => {
                const inputNo = row.querySelector('.drum-size-no');
                const inputLength = row.querySelector('.length-std');

                if (index < totalDrumReal) {
                    row.style.display = '';

                    if (index < drumUtama) {
                        // Penomoran Drum Utama (1, 2, 3...)
                        inputNo.value = (drumLabel && drumLabel !== "-- Pilih --") ? `${drumLabel} - ${index + 1}` : '';
                        inputLength.value = Math.round(qtyPerDrum);
                    } else {
                        // Penomoran Drum Sisa (Ngulang dari 1, 2, 3...)
                        inputNo.value = (drumLabel && drumLabel !== "-- Pilih --") ? `${drumLabel} - ${hitungSisa}` : '';
                        inputLength.value = Math.round(qtyCuk);
                        hitungSisa++; // Tambah angka sisa buat baris berikutnya
                    }
                } else {
                    row.style.display = 'none';
                    inputNo.value = '';
                    inputLength.value = '';
                }
            });
        }
    </script>
</body>

</html>