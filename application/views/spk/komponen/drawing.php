<style>
    .spk {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    .spk td,
    .spk th {
        border: 1px solid #000;
        padding: 3px;
        vertical-align: middle;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .bold {
        font-weight: bold;
    }

    .gray {
        background: #e6e6e6;
    }

    .red {
        color: red;
    }

    .check-sheet td {
        text-align: center;
    }

    .check-sheet td[contenteditable="true"] {
        text-align: center;
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
        @page {
            size: A4;
            margin: 10mm;
        }

        body * {
            visibility: hidden;
        }

        #print-area,
        #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        button {
            display: none !important;
        }

        td[contenteditable="true"] {
            outline: none;
        }

        /* HILANGKAN KOTAK INPUT SAAT PRINT */
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

        .select-visual {
            width: 100%;
            border: none;
            background: transparent;
            font-size: 11px;
            text-align: center;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
    }
</style>

<div style="margin-bottom:10px;">
</div>

<div id="print-area">

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
            <td class="bold">
                WORK ORDER & CHECK SHEET DRAWING PROCESS
            </td>
        </tr>
    </table>



    <!-- ================= WORK ORDER ================= -->
    <table class="spk">
        <tr>
            <th colspan="6" class="center gray">WORK ORDER</th>
        </tr>

        <!-- ROW 1 -->
        <tr>
            <!-- KIRI -->
            <td width="18%">Machine No.</td>
            <td width="15%">: <input style="width:95%"></td>

            <!-- TENGAH -->
            <td width="18%">OP No.</td>
            <td width="15%">: <?= $excel->production_order ?></td>

            <!-- KANAN -->
            <td>Drum capacity</td>
            <td>
                :
                <select id="drum_type" onchange="hitungDrum()">
                    <option value="">-- Pilih --</option>
                    <option value="630S">630 S</option>
                    <option value="630H">630 H</option>
                </select>

                <input
                    id="drum_capacity"
                    style="width:80px; text-align:right"
                    readonly> m
            </td>

        </tr>

        <!-- ROW 2 -->
        <tr>
            <td>Type</td>
            <td>: <?= $type ?></td>

            <td>SOP No.</td>
            <td>: <?= $excel->sales_order ?></td>

            <td>Number of wire</td>
            <td>: <?= $number_of_wire ?></td>

        </tr>

        <!-- ROW 3 -->
        <tr>
            <td>Size</td>
            <td>: <?= $size ?></td>


            <td>Qty Order</td>
            <td>: <?= number_format($excel->ordered_quantity) ?> m</td>

            <td>Number of loading</td>
            <td>
                :
                <input
                    id="number_of_loading"
                    style="width:80px; text-align:left"
                    readonly>
            </td>
        </tr>
        <!-- ROW 4 -->
        <tr>
            <td>Wire Diameter</td>
            <td>: <?= $min_diameter ?></td>


            <td>
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <span>Qty Drawing</span>
                </div>
            </td>
            <td>: <span id="qty_drawing"><?= $qty_drawing ?></span> m</td>
            <td>Qty @ drum</td>
            <td>
                :
                <input
                    id="qty_per_drum"
                    style="width:80px; text-align:left"
                    readonly> m
            </td>

        </tr>

        <!-- ROW 5 -->
        <tr>
            <td>Item Code</td>
            <td>: <?= $excel->item ?></td>

            <td>Standard Length</td>
            <td>: <?= number_format($excel->quantity_lot) ?> m</td>


            <td>Total Drum</td>
            <td>
                :
                <input
                    id="total_drum"
                    style="width:80px; text-align:left"
                    readonly>
            </td>

        </tr>
        <!-- ROW 6 -->
        <tr>
            <td>Customer</td>
            <td>: <?= $excel->customer_name ?></td>

            <td>Standard NSL (If any)</td>
            <td>: <input style="width:80%"></td>

            <td>Material</td>
            <td>: <?= $material ?></td>
        </tr>

        <!-- ROW 7 (FIX 4 KOTAK) -->
        <!-- ROW 7 (FINAL FIX POSISI BENER) -->
        <tr>
            <td>Jumlah Core</td>
            <td colspan="3">
                :
                <input id="jumlah_core" style="width:29%; text-align:left;" oninput="hitungQtyDrawing()">
            </td>

            <td>Factor Scrap</td>
            <td>
                :
                <input id="factor_scrap"
                    style="width:80px; text-align:center;"
                    oninput="hitungQtyDrawing()">
            </td>
        </tr>
        </tr>
    </table>
    <!-- ================= MATERIAL ================= -->
    <table class="spk">
        <tr>
            <td width="15%" class="center bold">Remark</td>
            <td colspan="13" contenteditable="true" class="center"></td>
        </tr>
    </table>

    <!-- ================= CHECK SHEET ================= -->
    <table class="spk check-sheet print-repeat">
        <thead>
            <tr>
                <th colspan="13" class="center gray">CHECK SHEET</th>
            </tr>

            <tr class="center gray">
                <td rowspan="3">Date / Shift</td>
                <td colspan="2">Input</td>
                <td colspan="6">Output</td>
                <td rowspan="3">Visual<br>(OK / NOK)</td>
                <td rowspan="3">Operator</td>
                <td rowspan="3">Foremen</td>
            </tr>

            <tr class="center gray">
                <td rowspan="2">Coil No.</td>
                <td rowspan="2">Visual<br>(OK / NOK)</td>
                <td rowspan="2">Drum Size - No.</td>
                <td colspan="2">Length (M)</td>
                <td colspan="3">Wire Diameter (mm)</td>
            </tr>

            <tr class="center gray">
                <td>Standard</td>
                <td>Actual</td>
                <td>Min.</td>
                <td>Max.</td>
                <td>Avg.</td>
            </tr>

            <!-- SPECIFICATION (DIAM, TIDAK DISENTUH JS) -->

            <tr class="center gray bold">
                <td colspan="2">SPECIFICATION</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td><?= $min_diameter ?></td>
                <td><?= $max_diameter ?></td>
                <td><?= $avg_diameter ?></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </thead>

        <!-- DATA DRUM (DINAMIS DARI JS) -->
        <tbody id="drum-body"></tbody>


        <tfoot>
            <tr class="gray">
                <td></td> <!-- Date -->
                <td></td> <!-- Coil -->
                <td></td> <!-- Input Visual -->

                <td class="bold center">Total length</td> <!-- Drum Size No -->

                <td class="center"></td> <!-- Std -->
                <td class="center"></td> <!-- Actual -->

                <td class="center"></td> <!-- Min -->
                <td class="center"></td> <!-- Max -->
                <td class="center"></td> <!-- Avg -->

                <td></td> <!-- Output Visual -->
                <td></td> <!-- Operator -->
                <td></td> <!-- Foreman -->
            </tr>

            <tr>
                <td colspan="3" contenteditable="true" style="height:55px; vertical-align:top;">
                    Date :
                </td>

                <td colspan="6" contenteditable="true" style="vertical-align:top;">
                    Made Out and Approved by :
                </td>

                <td colspan="3" contenteditable="true" style="vertical-align:top;">
                    Checked by :
                </td>
            </tr>

        </tfoot>
    </table>

    <script>
        function hitungDrum() {
            const minDiameter = <?= json_encode((float)$min_diameter) ?>;
            const qtyOrder = <?= json_encode((float)$excel->ordered_quantity) ?>;
            const numberWire = <?= json_encode((int)$number_of_wire) ?>;

            const type = document.getElementById('drum_type').value;
            if (!minDiameter || !type) return;

            let drumCapacity =
                type === '630S' ?
                88181 / (minDiameter * minDiameter) :
                70297 / (minDiameter * minDiameter);

            drumCapacity = Math.round(drumCapacity);
            document.getElementById('drum_capacity').value = drumCapacity;

            // ambil qty drawing dari span
            let qtyDrawingText = document.getElementById('qty_drawing').innerText.replace(/,/g, '');
            let qtyDrawing = parseFloat(qtyDrawingText) || 0;

            // ✅ RUMUS BARU
            const numberLoading = Math.ceil(qtyDrawing / drumCapacity / numberWire);
            document.getElementById('number_of_loading').value = numberLoading;

            // total drum tetap
            const totalDrum = numberLoading * numberWire;
            document.getElementById('total_drum').value = totalDrum;

            // qty per drum berdasarkan qty drawing
            const qtyPerDrum = numberLoading > 0 ?
                Math.round(qtyDrawing / totalDrum) :
                0;

            document.getElementById('qty_per_drum').value = qtyPerDrum;

            // =============================
            // 🔥 BIKIN BARIS TANPA LIMIT
            // =============================
            const tbody = document.getElementById('drum-body');
            tbody.innerHTML = '';

            for (let i = 1; i <= totalDrum; i++) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>

    <td>
        <select class="select-visual">
            <option></option>
            <option>OK</option>
            <option>NOK</option>
        </select>
    </td>

    <td class="bold center">${type} - ${i}</td>

    <!-- 🔥 STANDARD (ISI DARI QTY @ DRUM) -->
    <td class="center bold">${qtyPerDrum}</td>

    <!-- ACTUAL -->
<td contenteditable="true">${qtyPerDrum}</td>

    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>

    <td>
        <select class="select-visual">
            <option></option>
            <option>OK</option>
            <option>NOK</option>
        </select>
    </td>

    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
`;

                tbody.appendChild(tr);

               

              // refresh drum calculation
            }
        }

        function hitungQtyDrawing() {
            const qtyOrder = <?= json_encode((float)$excel->ordered_quantity) ?>;
            const numberWire = <?= json_encode((int)$number_of_wire) ?>;

            let scrap = parseFloat(document.getElementById('factor_scrap').value) || 0;
            let jumlahCore = parseFloat(document.getElementById('jumlah_core').value) || 0;

            const hasil = Math.round(qtyOrder * numberWire * scrap * jumlahCore);

            document.getElementById('qty_drawing').innerText =
                hasil > 0 ? hasil.toLocaleString('en-US') : 0;

                hitungDrum();
        }
    </script>
</div>