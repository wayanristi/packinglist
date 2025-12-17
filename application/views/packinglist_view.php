<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Packing List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <style>

        .qr-cell {
    display: flex;
    justify-content: center;
    align-items: center;
}

        @font-face {
            font-family: 'Myriad Pro Regular';
            font-style: normal;
            font-weight: normal;
            src: local('Myriad Pro Regular'), url('<?= base_url('assets/font/Myriad-Pro-Regular.ttf'); ?>') format('woff');
        }

        body {
            background: #f4f6f8;
            font-family: "Myriad Pro Regular", sans-serif;
            margin: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h3 {
            text-align: center;
            font-weight: bold;
        }

        table th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        table td {
            text-align: center;
            vertical-align: middle;
        }

        .header-table input {
            width: 100%;
            border: none;
            background: #eef3f7;
            padding: 5px;
            border-radius: 5px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="mt-4">
        <div class="card p-4">
            <h3>PACKING LIST</h3>
            <div class="d-flex gap-3 mb-4">
                <a href="<?= site_url('material'); ?>"
                    class="btn btn-primary shadow-sm"
                    style="background: linear-gradient(135deg, #5a9bff, #7ebfff); border:none; font-weight:600;">
                    ⚙️ Data Material
                </a>
                <a href="<?= site_url('datahaspel'); ?>"
                    class="btn btn-info shadow-sm"
                    style="background: linear-gradient(135deg, #4fc3f7, #81d4fa); border:none; font-weight:600;">
                    🗃️ Data Haspel
                </a>

                <a href="<?= site_url('kontrak'); ?>"
                    class="btn btn-success shadow-sm"
                    style="background: linear-gradient(135deg, #66bb6a, #81c784); border:none; font-weight:600;">
                    📑 Data Kontrak KHS
                </a>
            </div>

            <hr>
            <table class="table table-bordered header-table mb-4">
                <tr>
                    <th>Packing List No:</th>
                    <td><input type="text" id="packing_no"></td>
                    <th>Tanggal UST:</th>
                    <td><input type="date" id="tgl_ust"></td>
                    <th>Jumlah:</th>
                  <td><input type="text" id="jumlah" readonly></td>
                </tr>
                <tr>
                    <th>No. Kontrak KHS:</th>
                    <td>
                        <select id="kontrak" class="form-control">
                            <option value="">-- Pilih Kontrak KHS --</option>
                        </select>
                    </td>
                    <th>Tanggal:</th>
                    <td><input type="date" id="tanggal"></td>
                    <th>Nomor Stiker:</th>
                    <td><input type="text" id="stiker"></td>
                </tr>
            </table>
            <div class="no-print mb-3">
                <label for="excelFile" class="form-label fw-bold">Impor Data Excel:</label>
                <input type="file" id="excelFile" accept=".xlsx, .xls" class="form-control mb-2">
                <button class="btn btn-primary" onclick="importExcel()">📁 Impor</button>
                <button class="btn btn-success" onclick="printSelected()">🖨️ Cetak</button>
                <a href="<?= site_url('') ?>" class="btn btn-secondary me-2">🏠 Home</a>
            </div>


            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center" id="dataTable">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <small>Select All</small><br>
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                <br>
                            </th>
                            <th rowspan="2">Line</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Deskripsi Barang</th>
                            <th rowspan="2">Kode Serial Material PLN</th>
                            <th rowspan="2">QR Barcode</th>
                            <th rowspan="2" style="width:50px; white-space:normal; line-height:1.1;">
                                Quantity<br>Volume<br>(meter)
                            </th>

                            <th rowspan="2">Bulan Produksi</th>
                            <th colspan="2">Berat (Approx)</th>
                        </tr>
                        <tr>
                            <th>Net (Kg)</th>
                            <th>Gross (Kg)</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav class="no-print">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>

        </div>
    </div>

    <script>
        function formatTanggalIndonesia(tgl) {
    if (!tgl) return '-';
    const months = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    const d = new Date(tgl);
    if (isNaN(d)) return '-';
    const day = d.getDate();
    const month = months[d.getMonth()];
    const year = d.getFullYear();
    return `${day} ${month} ${year}`;
}

        // ✅ Load data kontrak dari database ke dropdown
        window.onload = function() {
            fetch("<?= site_url('packinglist/get_kontrak_khs'); ?>")
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById("kontrak");

                    data.forEach(row => {
                        const opt = document.createElement("option");
                        opt.value = row.nomor_kontrak;
                        opt.textContent = row.nomor_kontrak;
                        select.appendChild(opt);
                    });
                })
                .catch(err => console.error("Gagal load kontrak:", err));
        };

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function formatBulanSingkat(value) {
            if (!value) return '-';
            const date = new Date(value);
            if (isNaN(date)) return '-';
            const bulan = date.toLocaleString('en-US', {
                month: 'short'
            });
            const tahun = date.getFullYear().toString().slice(-2);
            return `${bulan}-${tahun}`;
        }

        function importExcel() {
            const fileInput = document.getElementById('excelFile');
            const file = fileInput.files[0];
            if (!file) {
                alert("Pilih file Excel terlebih dahulu!");
                return;
            }

            showLoading();

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(sheet, {
                    defval: ""
                });

                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = "";

                jsonData.forEach((row, index) => {
                    const lines = row["Sales Order Lines"] || "";
                    const item = row["Item"] || "";
                    const desc = row["Description"] || "";
                    const stdDesc = row["Standard Description"] || "";
                    const qty = row["Quantity Lot"] || "";
                    const prodOrder = row["Production Order"] || "";
                    const lot = row["Lot"] || "";
                    const itemNet = row["Weight"] || "";

                    const sumnet = Number(itemNet) * Number(qty);

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><input type="checkbox" class="row-check"></td>
                        <td>${lines}</td>
                        <td>${item}</td>
                        <td>${desc}</td>
                        <td id="kode_${index}" data-std="${stdDesc}">Loading...</td>
                        <td id="qr_${index}" class="qr-cell">Loading...</td>
                        <td>${qty}</td>
                        <td><input type="month" class="form-control" id="bulan_${index}"></td>
                        <td class="net-input">${sumnet}</td>
                        <td id="gross_${index}"></td>
                    `;
                    tableBody.appendChild(tr);
                    // update jumlah otomatis setiap row dicentang
tr.querySelector(".row-check").addEventListener("change", handleRowCheck);


                    const stdDescHidden = document.getElementById(`kode_${index}`).getAttribute("data-std");
                    const angkaStd = stdDescHidden.match(/\d+/);
                    const angka = angkaStd ? angkaStd[0] : "";
                    try {
                        fetch(`<?= site_url('packinglist/get_berat_haspel'); ?>?standard_desc=${angka}`)
                            .then(response => response.json())
                            .then(data => {

                                const beratHaspel = parseFloat(data.berat_haspel || 0);
                                const gross = sumnet + beratHaspel;
                                document.getElementById(`gross_${index}`).textContent = gross.toFixed(0);
                            })
                            .catch(() => {
                                document.getElementById(`gross_${index}`).textContent = "ERR";
                            });
                    } catch (err) {
                        document.getElementById(`gross_${index}`).textContent = "ERR";
                    }

                    fetch(`<?= site_url('packinglist/get_serial_code'); ?>?desc=${encodeURIComponent(desc)}`)
                        .then(response => response.json())
                        .then(data => {
                            let kodeSerial = data.kode_serial || "-";
                            const prodOrderLast3 = prodOrder.slice(-3);
                            const lotLast4 = lot.slice(-4);
                            kodeSerial += `${prodOrderLast3}${lotLast4}`;
                            document.getElementById(`kode_${index}`).textContent = kodeSerial;
                            const qrCell = document.getElementById(`qr_${index}`);
                            qrCell.innerHTML = '';
                            if (kodeSerial !== "-") {
                                new QRCode(qrCell, {
                                    text: kodeSerial,
                                    width: 80,
                                    height: 80
                                });
                            } else qrCell.textContent = '-';
                        })
                        .catch(() => {
                            document.getElementById(`kode_${index}`).textContent = '-';
                            document.getElementById(`qr_${index}`).textContent = '-';
                        });
                });

                hideLoading();
                updatePagination(); // Panggil pagination setelah import
            };
            reader.readAsArrayBuffer(file);
        }

        function printSelected() {
            // kode print sama persis seperti versi asli kamu
            const allRows = document.querySelectorAll("#tableBody tr");
            const selectedRows = [];
            let noUrut = 1;
            let totalQty = 0;
            let totalNet = 0;
            let totalGross = 0;

            allRows.forEach((row) => {
                if (row.querySelector(".row-check").checked) {
                    const qty = parseFloat(row.children[6].textContent) || 0;
                    const net = Math.round(parseFloat(row.children[8].textContent) || 0);
                    const gross = Math.round(parseFloat(row.children[9].textContent) || 0);

                    const bulanInput = row.querySelector('input[type="month"]');
                    let bulanProduksi = "-";
                    if (bulanInput && bulanInput.value) {
                        const [year, month] = bulanInput.value.split("-");
                        const dateObj = new Date(year, month - 1);
                        const shortMonth = dateObj.toLocaleString('en-US', {
                            month: 'short'
                        });
                        bulanProduksi = `${shortMonth}-${year.slice(-2)}`;
                    }

                    const kodeSerial = row.children[4].textContent;
                    const qrHtml = row.children[5].innerHTML;
                    const deskripsi = row.children[3].textContent;

                    const trHtml = `
<tr>
    <td>${noUrut}</td>
    <td style="text-align:left;">${deskripsi}</td>
    <td style="text-align:left;">${kodeSerial}</td>
    <td style="text-align:center; vertical-align:middle;">
    <div style="display:flex; justify-content:center; align-items:center;">
        ${qrHtml}
    </div>
</td>
    <td style="text-align:right;">${Math.round(qty)}</td>
    <td>${bulanProduksi}</td>
    <td style="text-align:right;">${net}</td>
    <td style="text-align:right;">${gross}</td>
</tr>
`;

                    selectedRows.push(trHtml);
                    totalQty += qty;
                    totalNet += net;
                    totalGross += gross;
                    noUrut++;
                }
            });

            if (selectedRows.length === 0) {
                alert("Pilih minimal satu data untuk dicetak!");
                return;
            }

            const packing_no = document.getElementById('packing_no').value || '-';
            const tgl_ust = formatTanggalIndonesia(document.getElementById('tgl_ust').value);
            const jumlah = document.getElementById('jumlah').value || '-';
            const kontrak = document.getElementById('kontrak').value || '-';
            const tanggal = formatTanggalIndonesia(document.getElementById('tanggal').value);
            const stiker = document.getElementById('stiker').value || '-';

            const printWindow = window.open('', '', 'width=1000,height=800');
            printWindow.document.write(`
            <html>

<head>
    <title>Packing List</title>
    <style>
        body {
                font-family: "Myriad Pro Regular", sans-serif;
            }
        .container {
            width: 95%;
            margin: 0 auto;
        }

        .kop {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .kop img {
            width: 110px;
            height: auto;
            margin-right: 20px;
        }

        .kop .info {
            text-align: center;
            line-height: 1.4;
        }

        .kop .info h2 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .kop .info p {
            margin: 2px 0;
            font-size: 13px;
        }

        .line {
            border-top: 2px solid #000;
            margin: 10px 0 20px 0;
        }

        h3 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }


        .header-table th {
            background: #f2f2f2;
            text-align: left;
            width: 20%;
        }

        .header-table td {
            text-align: left;
        }

        .total-row td {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature div {
            margin-bottom: 5px;
        }

        tr>th {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        tr>td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

    </style>
</head>

<body>
    <div class="container">
        <table class="report-container">
            <thead class="report-header">
                <tr>
                    <th class="report-header-cell" style="border: none;">
                        <div class="header-info">
                            <div class="kop" style="justify-content:flex-start; margin-bottom:5px;">
    <img src="<?= base_url('assets/images/logo_jembo.jpg'); ?>" 
         alt="Logo PT Jembo"
         style="width:120px; height:auto;">
</div>

<h3 style="
    text-align:center; 
    margin-top:5px; 
    margin-bottom:10px; 
    font-size:26px; 
    font-weight:700;
    letter-spacing:1px;
">
    PACKING LIST
</h3>


<div class="line"></div>

                <table class="header-table" 
                    style="width:100%; border-collapse:collapse; table-layout:fixed;">
                    <tr>
                        <th style="width:16%; background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            Packing List No:
                        </th>
                        <td style="width:17%; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${packing_no}
                        </td>

                        <th style="width:16%; background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            Tanggal UST:
                        </th>
                        <td style="width:17%; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${tgl_ust}
                        </td>

                        <th style="width:16%; background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            Jumlah:
                        </th>
                        <td style="width:17%; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${jumlah}
                        </td>
                    </tr>

                    <tr>
                        <th style="background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            No. Kontrak KHS:
                        </th>
                        <td style="padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${kontrak}
                        </td>

                        <th style="background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            Tanggal:
                        </th>
                        <td style="padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${tanggal}
                        </td>

                        <th style="background:#f2f2f2; padding:6px; overflow-wrap:break-word; white-space:normal;">
                            Nomor Stiker:
                        </th>
                        <td style="padding:6px; overflow-wrap:break-word; white-space:normal;">
                            ${stiker}
                        </td>
                    </tr>
                </table>

                        </div>
                    </th>
                </tr>
            </thead>
           <tfoot class="report-footer">
    <tr>
        <td class="report-footer-cell" style="border:none;">
            <div class="footer-info">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                       <td colspan="6" style="border:none;"></td>
<td class="footer-weight-cell" style="
    width:55px;
    height:30px;
    text-align:center;
    vertical-align:middle;
">
    <img src="<?= base_url('assets/images/ttd.png'); ?>"
         style="
            max-width:100%;
            max-height:100%;
            object-fit:contain;
         ">
</td>

<td class="footer-weight-cell" style="
    width:55px;
    height:30px;
">
</td>


                    </tr>
                </table>
            </div>
        </td>
    </tr>
</tfoot>

   <tbody class="report-content">
                <tr>
                    <td class="report-content-cell" style="border: none;">
                        <div class=" main">
                            
                            <table>
                                <thead>
    <tr>
        <th rowspan="2">No Item</th>
        <th rowspan="2">Deskripsi Barang</th>
        <th rowspan="2">Kode Serial Material PLN</th>
        <th rowspan="2">QR Barcode</th>

        <th rowspan="2" style="width:55px; white-space:normal; line-height:1.1;">
            Quantity<br>Volume<br>(meter)
        </th>

        <th rowspan="2">Bulan Produksi</th>

        <!-- Parent header for Net & Gross -->
        <th colspan="2" style="text-align:center;">Berat (Approx)</th>
    </tr>

    <tr>
        <th style="width:55px;">Net (Kg)</th>
        <th style="width:55px;">Gross (Kg)</th>
    </tr>
</thead>

                          <tbody>
                                    ${selectedRows.join("")}
                                   <tr class="total-row">
                                        <td></td>
                                        <!-- TOTAL DESKRIPSI BARANG -->
                                        <td style="text-align:middle; font-weight:bold;">
                                            TOTAL
                                        </td>

                                        <!-- TOTAL DRUMS (di bawah kolom kode serial material PLN) -->
                                        <td style="text-align:right; font-weight:bold;">
                                            ${noUrut - 1} Drums
                                        </td>

                                        <!-- Kosong (QR Barcode) -->
                                        <td></td>

                                        <!-- Total Quantity -->
                                        <td style="text-align:right; font-weight:bold;">
                                            ${Math.round(totalQty)}
                                        </td>

                                        <!-- Bulan produksi kosong -->
                                        <td></td>

                                        <!-- Total Net -->
                                        <td style="text-align:right; font-weight:bold;">
                                            ${Math.round(totalNet)}
                                        </td>

                                        <!-- Total Gross -->
                                        <td style="text-align:right; font-weight:bold;">
                                            ${Math.round(totalGross)}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
    `);
            printWindow.document.close();
        }

        function goBack() {
            window.location.href = "<?= site_url('landing'); ?>";
        }

        /* ================= Pagination ================= */
        let current_page = 1;
        const rows_per_page = 10;

        function displayPagination() {
            const table = document.getElementById("tableBody");
            const rows = Array.from(table.querySelectorAll("tr"));
            const total_pages = Math.ceil(rows.length / rows_per_page);
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (total_pages <= 1) return;

            // Previous
            const prevBtn = document.createElement("li");
            prevBtn.className = "page-item" + (current_page === 1 ? " disabled" : "");
            prevBtn.innerHTML = `<a class="page-link" href="#">Previous</a>`;
            prevBtn.onclick = function(e) {
                e.preventDefault();
                if (current_page > 1) {
                    current_page--;
                    displayPage(rows);
                    displayPagination();
                }
            };
            pagination.appendChild(prevBtn);

            // Page numbers (max 5)
            let start_page = Math.max(1, current_page - 2);
            let end_page = Math.min(total_pages, start_page + 4);
            if (end_page - start_page < 4) start_page = Math.max(1, end_page - 4);

            for (let i = start_page; i <= end_page; i++) {
                const li = document.createElement("li");
                li.className = "page-item" + (i === current_page ? " active" : "");
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.onclick = function(e) {
                    e.preventDefault();
                    current_page = i;
                    displayPage(rows);
                    displayPagination();
                };
                pagination.appendChild(li);
            }

            // Next
            const nextBtn = document.createElement("li");
            nextBtn.className = "page-item" + (current_page === total_pages ? " disabled" : "");
            nextBtn.innerHTML = `<a class="page-link" href="#">Next</a>`;
            nextBtn.onclick = function(e) {
                e.preventDefault();
                if (current_page < total_pages) {
                    current_page++;
                    displayPage(rows);
                    displayPagination();
                }
            };
            pagination.appendChild(nextBtn);

            displayPage(rows);
        }

        function displayPage(rows) {
            rows.forEach((row, index) => {
                row.style.display = "none";
                const start = (current_page - 1) * rows_per_page;
                const end = start + rows_per_page;
                if (index >= start && index < end) row.style.display = "";
            });
        }

        function updatePagination() {
            current_page = 1;
            displayPagination();
        }
    </script>
    

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(3px);
    text-align: center;
    padding-top: 20%;
    font-family: Arial, sans-serif;
    color: #333;
">
        <div style="display: inline-block; padding: 20px; background: white; border-radius: 10px; 
        box-shadow: 0 0 10px rgba(0,0,0,0.2);">
            <div class="spinner" style="
                width: 40px; height: 40px;
                border: 4px solid #ccc;
                border-top-color: #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 10px;
            "></div>
            <div>Memproses data, mohon tunggu...</div>
        </div>
    </div>

    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>

    <script>
        const rowsPerPage = 10;
        let currentPage = 1;

        function displayPage(rows) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.forEach((row, index) => row.style.display = (index >= start && index < end) ? '' : 'none');
        }

        function displayPagination() {
            const rows = Array.from(document.querySelectorAll('#tableBody tr'));
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous
            const prev = document.createElement('li');
            prev.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
            prev.innerHTML = `<a class="page-link" href="#">Previous</a>`;
            prev.onclick = e => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    update();
                }
            };
            pagination.appendChild(prev);

            // Generate page numbers with dots
            let pages = [];
            if (currentPage <= 3) {
                pages = [1, 2, 3, '...', totalPages - 1, totalPages];
            } else if (currentPage >= totalPages - 2) {
                pages = [1, 2, '...', totalPages - 2, totalPages - 1, totalPages];
            } else {
                pages = [1, 2, '...', currentPage, '...', totalPages - 1, totalPages];
            }

            pages.forEach(p => {
                const li = document.createElement('li');
                if (p === '...') {
                    li.className = 'page-item disabled';
                    li.innerHTML = `<a class="page-link" href="#">...</a>`;
                } else {
                    li.className = 'page-item' + (p === currentPage ? ' active' : '');
                    li.innerHTML = `<a class="page-link" href="#">${p}</a>`;
                    li.onclick = e => {
                        e.preventDefault();
                        currentPage = p;
                        update();
                    };
                }
                pagination.appendChild(li);
            });

            // Next
            const next = document.createElement('li');
            next.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
            next.innerHTML = `<a class="page-link" href="#">Next</a>`;
            next.onclick = e => {
                e.preventDefault();
                if (currentPage < totalPages) {
                    currentPage++;
                    update();
                }
            };
            pagination.appendChild(next);

            displayPage(rows);
        }

        function update() {
            displayPagination();
        }

        update();
    </script>
    <script>
        function toggleSelectAll() {
            const master = document.getElementById("selectAll");
            const checks = document.querySelectorAll(".row-check");

            checks.forEach(ch => ch.checked = master.checked);
        }
        function toggleSelectAll() {
    const master = document.getElementById("selectAll");
    const checks = document.querySelectorAll(".row-check");

    checks.forEach(ch => ch.checked = master.checked);

    updateHeaderJumlah(); // ini penting biar total Jumlah ikut berubah
}


        function updateHeaderJumlah() {
    const allRows = document.querySelectorAll("#tableBody tr");
    let totalQty = 0;
    let totalDrum = 0;

    allRows.forEach(row => {
        if (row.querySelector(".row-check").checked) {
            const qty = parseFloat(row.children[6].textContent) || 0;
            totalQty += qty;
            totalDrum += 1; // setiap row yang dicentang = 1 drum
        }
    });

    document.getElementById('jumlah').value = `${Math.round(totalQty)} m / ${totalDrum} Drums`;
}

// --- AUTO ISI BULAN PRODUKSI --- //
function fillBulanProduksiForChecked() {
    const allRows = document.querySelectorAll("#tableBody tr");
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const currentValue = `${year}-${month}`;

    allRows.forEach((row) => {
        const checkbox = row.querySelector(".row-check");
        const bulanInput = row.querySelector('input[type="month"]');

        if (checkbox.checked) {
            bulanInput.value = currentValue;  // Auto set
        }
    });
}

// --- SELECT ALL --- //
function toggleSelectAll() {
    const checkAll = document.getElementById("selectAll");
    const rows = document.querySelectorAll(".row-check");

    rows.forEach(ch => ch.checked = checkAll.checked);

    // Setelah centang → isi bulan otomatis
    fillBulanProduksiForChecked();

    // Update jumlah di header (punya kamu)
    updateHeaderJumlah();
}

// --- KETIKA USER CENTANG SATU-SATU --- //
function handleRowCheck() {
    // Kalau user centang manual → isi bulan otomatis hanya baris itu
    fillBulanProduksiForChecked();

    // Update jumlah (punya kamu)
    updateHeaderJumlah();
}


    </script>

</body>

</html>