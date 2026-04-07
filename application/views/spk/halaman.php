<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Kerja (SPK)</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        /* Header SPK */
        .spk-header {
            background: #fff;
            border-bottom: 3px solid #0d6efd;
        }

        .spk-title {
            font-weight: 800;
            font-size: 30px;
            letter-spacing: 2px;
        }

        /* Navigasi Tab */
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #495057;
            border: 1px solid transparent;
            padding: 12px 25px;
        }

        .nav-tabs .nav-link.active {
            background: #0d6efd !important;
            color: #fff !important;
            border-color: #0d6efd;
        }

        /* WADAH KONTEN - KUNCI AGAR TABEL TIDAK ACAK-ACAKAN */
        .spk-content {
            background: #fff;
            min-height: 500px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            /* Izinkan scroll horizontal jika tabel sangat lebar */
            overflow-x: auto !important;
            display: block !important;
        }

        /* PROTEKSI TABEL: Supaya Drawing/Stranding/Insul tetap pada ukuran aslinya */
        .tab-pane {
            width: 100%;
        }

        .wrapper-tabel-asli {
            display: inline-block;
            /* Paksa wadah mengikuti lebar asli tabel di dalamnya */
            min-width: 100%;
            padding: 20px;
        }

        /* Mencegah Bootstrap memaksa tabel menjadi kecil/sempit */
        .wrapper-tabel-asli table {
            width: auto !important;
            min-width: 100%;
            table-layout: auto !important;
            white-space: nowrap;
            /* Mencegah teks dalam sel turun ke bawah (wrap) */
        }

        /* Sembunyikan elemen yang tidak perlu saat print */
        @media print {

            .btn,
            .nav-tabs,
            .spk-header a {
                display: none !important;
            }

            .spk-header {
                border-bottom: none;
            }

            body {
                background: #fff;
            }

            .spk-content {
                box-shadow: none;
                border: none;
                overflow: visible !important;
            }

            .wrapper-tabel-asli {
                padding: 0;
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid spk-header py-3 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url('assets/images/logo_jembo.jpg'); ?>" style="width:120px">
                <div>
                    <div class="spk-title">SURAT PERINTAH KERJA</div>
                    <small class="text-muted">PT Jembo Cable Company Tbk</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= site_url('spk/next'); ?>" class="btn btn-outline-secondary btn-sm">Kembali ke List</a>

                <button onclick="window.print()" class="btn btn-primary">
                    🖨 Print
                </button>

                <button class="btn btn-success" onclick="openLabelModal()">
                    🏷 Print Label
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-3 px-4">

        <?php $process = isset($process) && is_array($process) ? $process : []; ?>

        <ul class="nav nav-tabs" id="spkTab" role="tablist">
            <?php if (in_array('drawing', $process)): ?>
                <li class="nav-item">
                   <button class="nav-link active" id="drawing-tab" data-bs-toggle="tab" data-bs-target="#drawing" type="button" role="tab">
                        SPK DRAWING
                    </button>
                </li>
            <?php endif; ?>

   <?php if (in_array('stranding', $process) && count($process) == 3): ?>
<li class="nav-item">
    <button class="nav-link" id="stranding-tab" data-bs-toggle="tab" data-bs-target="#stranding" type="button">
        SPK STRANDING
    </button>
</li>
<?php endif; ?>

<?php if (in_array('stranding2', $process)): ?>
<li class="nav-item">
    <button class="nav-link" id="stranding2-tab" data-bs-toggle="tab" data-bs-target="#stranding2" type="button">
        SPK STRANDING G2
    </button>
</li>
<?php endif; ?>
            <?php if (in_array('insul', $process)): ?>
                <li class="nav-item">
                    <button class="nav-link" id="insul-tab" data-bs-toggle="tab" data-bs-target="#insul" type="button" role="tab">SPK INSUL</button>
                </li>
            <?php endif; ?>
        </ul>

        <div class="tab-content spk-content border border-top-0" id="spkTabContent">

            <?php if (in_array('drawing', $process)): ?>
              <div class="tab-pane fade show active" id="drawing" role="tabpanel">
                    <div class="wrapper-tabel-asli">
                        <?php $this->load->view('spk/komponen/drawing'); ?>
                    </div>
                </div>
            <?php endif; ?>
<?php if (in_array('stranding', $process)): ?>
<div class="tab-pane fade" id="stranding" role="tabpanel">
    <div class="wrapper-tabel-asli">
        <?php $this->load->view('spk/komponen/stranding'); ?>
    </div>
</div>
<?php endif; ?>


<?php if (in_array('stranding2', $process)): ?>
<div class="tab-pane fade" id="stranding2">
    <div class="wrapper-tabel-asli">
        <?php $this->load->view('spk/komponen/stranding_grouping2'); ?>
    </div>
</div>
<?php endif; ?>

            <?php if (in_array('insul', $process)): ?>
                <div class="tab-pane fade" id="insul" role="tabpanel">
                    <div class="wrapper-tabel-asli">
                        <?php $this->load->view('spk/komponen/insul'); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($tds_not_found) && $tds_not_found): ?>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'TDS Tidak Ditemukan 😢',
                text: 'Data TDS untuk kabel ini belum tersedia. Silahkan Hubungi Tim IT',
                confirmButtonColor: '#0d6efd'
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('#spkTab button[data-bs-toggle="tab"]');

            // simpan tab aktif
            tabButtons.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function(e) {
                    localStorage.setItem('activeSpkTab', e.target.dataset.bsTarget);
                });
            });

            // ambil tab terakhir atau default ke drawing
            let activeTab = localStorage.getItem('activeSpkTab');

            if (!activeTab) {
                activeTab = tabButtons[0].dataset.bsTarget; // tab pertama
            }

            const triggerEl = document.querySelector(
                `#spkTab button[data-bs-target="${activeTab}"]`
            );

            if (triggerEl) {
                new bootstrap.Tab(triggerEl).show();
            }
        });
    </script>

    <script>
        let zebraExtensionDetected = false;

        window.addEventListener("message", function(event) {

            if (!event.data || !event.data.ZebraPrintingVersion) {
                return;
            }

            zebraExtensionDetected = true;

            let status = document.getElementById("zebraExtensionStatus");
            let btn = document.getElementById("btnPrintLabel");

            if (status) {
                status.innerHTML = "🟢 Extension Zebra sudah terinstall";
                status.style.color = "#198754";
            }

            if (btn) btn.disabled = false;

        });

        function openLabelModal() {

            // ==========================
            // VALIDASI TAB AKTIF
            // ==========================

            let activeTabCheck = document.querySelector('#spkTab .nav-link.active');

            if (!activeTabCheck) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tab belum dipilih',
                    text: 'Silahkan pilih SPK Drawing / Stranding / Insul terlebih dahulu'
                });
                return;
            }

            let type = "";

           if (activeTabCheck.id === "drawing-tab") type = "drawing";
if (activeTabCheck.id === "stranding-tab") type = "stranding";
if (activeTabCheck.id === "stranding2-tab") type = "stranding2";
if (activeTabCheck.id === "insul-tab") type = "insul";


            // ==========================
            // VALIDASI DATA
            // ==========================

            if (type === "drawing") {

                let qtyInput = document.getElementById("qty_per_drum");

                if (!qtyInput || qtyInput.value.trim() === "") {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum lengkap',
                        text: 'Qty per Drum belum diisi'
                    });

                    return;
                }
            }

            if (type === "stranding") {

                const rows = document.querySelectorAll(".drum-row");
                let valid = false;

                rows.forEach(row => {

                    if (row.style.display !== "none") {

                        const lengthInput = row.querySelector(".length-std");

                        if (lengthInput && lengthInput.value.trim() !== "") {
                            valid = true;
                        }
                    }

                });

                if (!valid) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum lengkap',
                        text: 'Length Standard pada SPK Stranding belum diisi'
                    });

                    return;
                }
            }

            if (type === "stranding2") {

    const rows = document.querySelectorAll(".drum-row-g2");
    let valid = false;

    rows.forEach(row => {

        if (row.style.display !== "none") {

            const lengthInput = row.querySelector(".length-std");

            if (lengthInput && lengthInput.value.trim() !== "") {
                valid = true;
            }
        }

    });

    if (!valid) {

        Swal.fire({
            icon: 'warning',
            title: 'Data belum lengkap',
            text: 'Length Standard pada SPK Stranding G2 belum diisi'
        });

        return;
    }
}

            if (type === "insul") {

                const rows = document.querySelectorAll("#insul tbody tr");
                let valid = false;

                rows.forEach(row => {

                    const cells = row.querySelectorAll("td");

                    if (cells.length > 6) {

                        const input = cells[6].querySelector("input");

                        if (input && input.value.trim() !== "") {
                            valid = true;
                        }
                    }

                });

                if (!valid) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum lengkap',
                        text: 'Length (Standard) pada SPK Insul belum diisi'
                    });

                    return;
                }
            }

            // ==========================
            // AMBIL DATA SPK
            // ==========================

            let spk_id = "<?= $excel->id ?>";

            let diameter = "";

            if (type === "drawing") {
                diameter = "<?= $min_diameter ?>";
            }

            if (type === "stranding") {

                if ("<?= $cond5_nom ?>" !== "-") diameter = "<?= $cond5_nom ?>";
                else if ("<?= $cond4_nom ?>" !== "-") diameter = "<?= $cond4_nom ?>";
                else if ("<?= $cond3_nom ?>" !== "-") diameter = "<?= $cond3_nom ?>";
                else if ("<?= $cond2_nom ?>" !== "-") diameter = "<?= $cond2_nom ?>";
                else diameter = "<?= $cond1_nom ?>";
            }

            if (type === "insul") {
                diameter = "<?= number_format((float)$diameter_insul_spec_min, 2, '.', '') ?>";
            }

            let drumType = "";
            let qtyDrum = "";
            let lengths = [];

            // ==========================
            // STRANDING
            // ==========================

            if (type === "stranding") {

                const select = document.getElementById("kapDrumSelect");

                if (select) {
                    drumType = select.options[select.selectedIndex].text;
                }

                const rows = document.querySelectorAll(".drum-row");

                rows.forEach(row => {

                    if (row.style.display !== "none") {

                        const lengthInput = row.querySelector(".length-std");

                        if (lengthInput && lengthInput.value) {
                            lengths.push(lengthInput.value);
                        }

                    }

                });
            }


            if (type === "stranding2") {

    if ("<?= $cond4_nom ?>" !== "-") diameter = "<?= $cond4_nom ?>";
    else if ("<?= $cond3_nom ?>" !== "-") diameter = "<?= $cond3_nom ?>";
    else if ("<?= $cond2_nom ?>" !== "-") diameter = "<?= $cond2_nom ?>";
    else diameter = "<?= $cond1_nom ?>";

    const rows = document.querySelectorAll(".drum-row-g2");

    rows.forEach(row => {

        const lengthInput = row.querySelector(".length-std");

        if (lengthInput && lengthInput.value) {
            lengths.push(lengthInput.value);
        }

    });
}
            // ==========================
            // INSUL
            // ==========================

            if (type === "insul") {

                const rows = document.querySelectorAll("#insul tbody tr");

                rows.forEach(row => {

                    const cells = row.querySelectorAll("td");

                    if (cells.length > 6) {

                        const input = cells[6].querySelector("input");

                        if (input && input.value) {

                        let val = input.value.replace(/,/g,"").trim();

if(val !== ""){
    lengths.push(val);
}

                        }

                    }

                });
            }

            // ==========================
            // DRAWING
            // ==========================

            if (type === "drawing") {

                const qtyInput = document.getElementById("qty_per_drum");

                if (qtyInput) {
                    qtyDrum = qtyInput.value;
                }

                const drumSelect = document.getElementById("drum_type");

                if (drumSelect) {
                    drumType = drumSelect.value;
                }
            }

            // ==========================
            // URL PREVIEW LABEL
            // ==========================

            let url = "<?= site_url('spk/label') ?>" +
            
                "?mode=preview" +
                "&id=" + spk_id +
                "&type=" + type +
                "&diameter=" + diameter +
                "&drum=" + drumType +
                "&qtydrum=" + qtyDrum +
                "&lengths=" + encodeURIComponent(JSON.stringify(lengths));

                // hitung total label
let totalLabel = 1;

if (type === "drawing") {
    totalLabel = document.getElementById("total_drum")?.value || 1;
}

if (type === "stranding" || type === "stranding2" || type === "insul") {
    totalLabel = lengths.length > 0 ? lengths.length : 1;
}

// tampilkan ke UI
document.getElementById("labelCount").innerText = "Label: 1 / " + totalLabel;

            // ==========================
            // LOAD PREVIEW
            // ==========================

            document.getElementById("frameLabel").src = url;

            // ==========================
            // OPEN MODAL
            // ==========================

            let modal = new bootstrap.Modal(document.getElementById('modalLabel'));
            modal.show();

            // reset tombol print

            let btn = document.getElementById("btnPrintLabel");

            if (btn) {
                btn.disabled = false;
                btn.innerText = "Print";
            }

            let status = document.getElementById("printStatus");

            if (status) {
                status.innerHTML = "";
            }

            // cek printer

            setTimeout(function() {
                cekPrinterZebra();
            }, 500);

            setTimeout(function() {
                cekZebraExtension();
            }, 500);
        }

     function printZPL() {

    let btn = document.getElementById("btnPrintLabel");

    if (btn) {
        btn.disabled = true;
        btn.innerText = "Print";
    }

    let activeTab = document.querySelector('#spkTab .nav-link.active');

    if (!activeTab) {
        alert("Tab tidak ditemukan");
        return;
    }

    let type = "";

   if (activeTab.id === "drawing-tab") type = "drawing";
if (activeTab.id === "stranding-tab") type = "stranding";
if (activeTab.id === "stranding2-tab") type = "stranding2";
if (activeTab.id === "insul-tab") type = "insul";

    // =============================
    // AMBIL TOTAL DRUM (KHUSUS DRAWING)
    // =============================

    let totalDrum = 1;

    if (type === "drawing") {
        const totalInput = document.getElementById("total_drum");

        if (totalInput && totalInput.value) {
            totalDrum = parseInt(totalInput.value);
        }
    }


    let spk_id = "<?= $excel->id ?>";

    let diameter = "";
    let qtyDrum = "";
    let drumType = "";
    let lengths = [];

            // =============================
            // DRAWING
            // =============================

            if (type === "drawing") {

                diameter = "<?= $min_diameter ?>";

                const qtyInput = document.getElementById("qty_per_drum");

                if (qtyInput) {
                    qtyDrum = qtyInput.value;
                }

                const drumSelect = document.getElementById("drum_type");

                if (drumSelect) {
                    drumType = drumSelect.value;
                }

            }

            // =============================
            // STRANDING
            // =============================

            if (type === "stranding") {

                if ("<?= $cond5_nom ?>" !== "-") diameter = "<?= $cond5_nom ?>";
                else if ("<?= $cond4_nom ?>" !== "-") diameter = "<?= $cond4_nom ?>";
                else if ("<?= $cond3_nom ?>" !== "-") diameter = "<?= $cond3_nom ?>";
                else if ("<?= $cond2_nom ?>" !== "-") diameter = "<?= $cond2_nom ?>";
                else diameter = "<?= $cond1_nom ?>";

                const select = document.getElementById("kapDrumSelect");

                if (select) {
                    drumType = select.options[select.selectedIndex].text;
                }

                const rows = document.querySelectorAll(".drum-row");

                rows.forEach(row => {

                    if (row.style.display !== "none") {

                        const lengthInput = row.querySelector(".length-std");

                        if (lengthInput && lengthInput.value) {
                            lengths.push(lengthInput.value);
                        }

                    }

                });

            }

            if (type === "stranding2") {

    if ("<?= $cond4_nom ?>" !== "-") diameter = "<?= $cond4_nom ?>";
    else if ("<?= $cond3_nom ?>" !== "-") diameter = "<?= $cond3_nom ?>";
    else if ("<?= $cond2_nom ?>" !== "-") diameter = "<?= $cond2_nom ?>";
    else diameter = "<?= $cond1_nom ?>";

    const rows = document.querySelectorAll(".drum-row-g2");

    rows.forEach(row => {

      const lengthInput = row.querySelector(".length-std");

if (lengthInput) {

    let val = lengthInput.value.replace(/,/g,"").trim();

    if(val !== ""){
        lengths.push(val);
    }

}

    });
}

            // =============================
            // INSULATION
            // =============================

            if (type === "insul") {

                diameter = "<?= number_format((float)$diameter_insul_spec_min, 2, '.', '') ?>";

                const rows = document.querySelectorAll("#insul tbody tr");

                rows.forEach(row => {

                    const cells = row.querySelectorAll("td");

                    if (cells.length > 6) {

                        const input = cells[6].querySelector("input");

                        if (input && input.value) {

                          let val = input.value.replace(/,/g,"").trim();

if(val !== ""){
    lengths.push(val);
}

                        }

                    }

                });

            }

            // =============================
            // URL GENERATE ZPL
            // =============================

            let url = "<?= site_url('spk/label') ?>" +
                "?mode=zpl" +
                "&id=" + spk_id +
                "&type=" + type +
                "&diameter=" + diameter +
                "&drum=" + drumType +
              "&qtydrum=" + qtyDrum +
"&total=" + totalDrum +
"&lengths=" + encodeURIComponent(JSON.stringify(lengths));

            // =============================
            // FETCH ZPL
            // =============================

            fetch(url)
                .then(res => res.text())
                .then(zpl => {

                    // =============================
                    // DEBUG RAW ZPL
                    // =============================

                    console.log("========== RAW ZPL ==========");
                    console.log(zpl);
                    console.log("========== END ZPL ==========");

                    // hitung jumlah label

                    let labelCount = (zpl.match(/\^XA/g) || []).length;

                    console.log("Jumlah label dalam ZPL:", labelCount);

                    // tampilkan juga di UI

                    let status = document.getElementById("printStatus");

                    if (status) {
                        status.innerHTML = "🖨 " + labelCount + " label dikirim ke printer";
                    }

             
                    window.postMessage({
                        type: "zebra_print_label",
                        zpl: zpl,
                        url: "http://192.168.15.212/pstprnt"
                    }, "*");

                })
                .catch(err => {

                    console.error("Gagal generate ZPL:", err);

                    alert("Gagal mengambil ZPL dari server");

                });

        }

        function cekPrinterZebra() {

            let status = document.getElementById("printerStatus");
            let btn = document.getElementById("btnPrintLabel");
            let controller = new AbortController();
            let timeout = setTimeout(() => controller.abort(), 1000);

            fetch("http://192.168.15.212", {
                    method: "HEAD",
                    mode: "no-cors",
                    signal: controller.signal
                })
                .then(() => {

                    clearTimeout(timeout);

                    status.innerHTML = "🟢 Printer Zebra terhubung";
                    status.style.color = "#198754";

                    btn.disabled = false;

                })
                .catch(() => {

                    status.innerHTML = "🔴 Printer Zebra tidak terhubung";
                    status.style.color = "#dc3545";

                    btn.disabled = true;

                });

        }

        function cekZebraExtension() {

            let status = document.getElementById("zebraExtensionStatus");
            let btn = document.getElementById("btnPrintLabel");
            setTimeout(function() {

                if (!zebraExtensionDetected) {

                    if (status) {
                        status.innerHTML = "🔴 Extension Zebra belum di install";
                        status.style.color = "#dc3545";
                    }

                    if (btn) btn.disabled = true;

                }

            }, 500);

        }
    </script>
    <div class="modal fade" id="modalLabel" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Preview Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

             <div class="modal-body d-flex justify-content-center align-items-center" style="height:600px; background:#f4f6f9;">

                 <iframe id="frameLabel"
    style="
    width:900px;
    height:650px;
    border:0;
    display:block;
    margin:auto;
    transform:scale(0.8);
    transform-origin:center center;
"></iframe>
                </div>

                <div class="modal-footer">

                    <div id="zebraExtensionStatus"
                        style="color:#dc3545;font-weight:bold;margin-right:auto;">
                        🔴 Extension Zebra belum di install
                    </div>

                    <div id="printerStatus"
                        style="color:#dc3545;font-weight:bold;margin-right:auto;">
                        🔴 Printer Zebra belum terhubung
                    </div>

                    <div id="labelCount" style="margin-right:auto; font-weight:bold; color:#0d6efd;">
    Label: -
</div>

                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button id="btnPrintLabel" class="btn btn-primary" onclick="printZPL()">
                        Print
                    </button>

                    <span id="printStatus" style="margin-left:10px;font-weight:bold;color:#0d6efd;"></span>
                </div>

            </div>
        </div>
    </div>
</body>

</html>