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
        body {
            background: #f4f6f8;
            font-family: "Segoe UI", sans-serif;
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
            <hr>
            <table class="table table-bordered header-table mb-4">
                <tr>
                    <th>Packing List No:</th>
                    <td><input type="text" id="packing_no"></td>
                    <th>Tanggal UST:</th>
                    <td><input type="date" id="tgl_ust"></td>
                </tr>
                <tr>
                    <th>Jumlah:</th>
                    <td><input type="text" id="jumlah"></td>
                    <th>No. Kontrak KHS:</th>
                    <td><input type="text" id="kontrak"></td>
                </tr>
                <tr>
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
                <button class="btn btn-secondary" onclick="goBack()">🏠 Home</button>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center" id="dataTable">
                    <thead>
                        <tr>
                            <th rowspan="2">Pilih</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Deskripsi Barang</th>
                            <th rowspan="2">Kode Serial Material PLN</th>
                            <th rowspan="2">QR Barcode</th>
                            <th rowspan="2">Quantity/Volume (meter)</th>
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
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // fungsi buat format bulan singkat
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
                    const item = row["Item"] || "";
const desc = row["Description"] || "";
const stdDesc = row["Standard Description"] || "";
const qty = row["Quantity Lot"] || "";
const prodOrder = row["Production Order"] || "";
const lot = row["Lot"] || "";

const tr = document.createElement('tr');
tr.innerHTML = `
    <td><input type="checkbox" class="row-check"></td>
    <td>${item}</td>
    <td>${desc}</td>
    
    <!-- simpan standard description secara tersembunyi -->
    <td id="kode_${index}" data-std="${stdDesc}">Loading...</td>
    
    <td id="qr_${index}">Loading...</td>
    <td>${qty}</td>
    <td><input type="month" class="form-control" id="bulan_${index}"></td>
    <td><input type="number" step="0.01" class="form-control net-input" id="net_${index}" placeholder="Isi Net (Kg)"></td>
    <td id="gross_${index}"></td>
`;
tableBody.appendChild(tr);

                    const netInput = document.getElementById(`net_${index}`);
netInput.addEventListener("input", async () => {

    const netVal = parseFloat(netInput.value) || 0;

    // Ambil standard description dari hidden attribute (data-std)
    const stdDescHidden = document.getElementById(`kode_${index}`).getAttribute("data-std");

    // Ekstrak angka dari stdDesc (contoh: "HASPEL KAYU 160" -> "160")
    const angkaStd = stdDescHidden.match(/\d+/);
    const angka = angkaStd ? angkaStd[0] : "";

    try {
        // Panggil controller
        const response = await fetch(
            `<?= site_url('packinglist/get_berat_haspel'); ?>?standard_desc=${angka}`
        );
        const data = await response.json();

        const beratHaspel = parseFloat(data.berat_haspel || 0);
        const gross = netVal + beratHaspel;

        document.getElementById(`gross_${index}`).textContent = gross.toFixed(2);

    } catch (err) {
        document.getElementById(`gross_${index}`).textContent = "ERR";
    }
});


                    // Ambil kode serial
                    fetch(`<?= site_url('packinglist/get_serial_code'); ?>?desc=${encodeURIComponent(desc)}`)
                        .then(response => response.json())
                        .then(data => {
                            let kodeSerial = data.kode_serial || "-";
                            const prodOrderLast3 = prodOrder.slice(-3);
                            const lotLast4 = lot.slice(-4);
                            kodeSerial += `${prodOrderLast3}${lotLast4}`;
                            // kodeSerial 

                            document.getElementById(`kode_${index}`).textContent = kodeSerial;

                            const qrCell = document.getElementById(`qr_${index}`);
                            qrCell.innerHTML = '';
                            if (kodeSerial !== "-") {
                                new QRCode(qrCell, {
                                    text: kodeSerial,
                                    width: 80,
                                    height: 80
                                });
                            } else {
                                qrCell.textContent = '-';
                            }
                        })
                        .catch(() => {
                            document.getElementById(`kode_${index}`).textContent = '-';
                            document.getElementById(`qr_${index}`).textContent = '-';
                        });
                });

                hideLoading();
            };
            reader.readAsArrayBuffer(file);
        }

        function printSelected() {
            const allRows = document.querySelectorAll("#tableBody tr");
            const selectedRows = [];
            let noUrut = 1;
            let totalQty = 0;
            let totalNet = 0;
            let totalGross = 0;

            allRows.forEach(row => {
                if (row.querySelector(".row-check").checked) {
                    const newRow = row.cloneNode(true);
                    newRow.children[0].innerHTML = noUrut;

                    // Ambil value input bulan produksi & ubah jadi format singkat
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
                    newRow.children[6].textContent = bulanProduksi;

                    // Ambil nilai Net & Gross dari tabel
                    const qty = parseFloat(newRow.children[5].textContent) || 0;
                    const net = parseFloat(row.querySelector('.net-input')?.value || 0);
                    const gross = parseFloat(newRow.children[8].textContent) || (net * qty);

                    newRow.children[7].textContent = net.toFixed(2);
                    newRow.children[8].textContent = gross.toFixed(2);

                    totalQty += qty;
                    totalNet += net;
                    totalGross += gross;

                    selectedRows.push(newRow.outerHTML);
                    noUrut++;
                }
            });

            if (selectedRows.length === 0) {
                alert("Pilih minimal satu data untuk dicetak!");
                return;
            }

            const packing_no = document.getElementById('packing_no').value || '-';
            const tgl_ust = document.getElementById('tgl_ust').value || '-';
            const jumlah = document.getElementById('jumlah').value || '-';
            const kontrak = document.getElementById('kontrak').value || '-';
            const tanggal = document.getElementById('tanggal').value || '-';
            const stiker = document.getElementById('stiker').value || '-';

            const printWindow = window.open('', '', 'width=1000,height=800');
            printWindow.document.write(`
<html>
<head>
<title>Packing List</title>
<style>
@media print {
  @page {
    size: A4 portrait;
    margin: 15mm 10mm 15mm 10mm; /* atas, kanan, bawah, kiri */
  }

  body {
    margin: 0;
    padding: 0;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }
}

.container { width: 95%; margin: 0 auto; }
.kop { display: flex; justify-content: center; align-items: center; margin-bottom: 10px; }
.kop img { width: 110px; height: auto; margin-right: 20px; }
.kop .info { text-align: center; line-height: 1.4; }
.kop .info h2 { margin: 0; font-size: 20px; font-weight: bold; }
.kop .info p { margin: 2px 0; font-size: 13px; }
.line { border-top: 2px solid #000; margin: 10px 0 20px 0; }
h3 { text-align: center; font-size: 18px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px; }
th, td { border: 1px solid #000; padding: 6px 8px; text-align: center; vertical-align: middle; }
.header-table th { background: #f2f2f2; text-align: left; width: 20%; }
.header-table td { text-align: left; }
.total-row td { font-weight: bold; }
</style>
</head>
<body>
<div class="container">
<div class="kop">
<img src="<?= base_url('assets/images/logo_jembo.jpg'); ?>" alt="Logo PT Jembo">
<div class="info">
<h2>PT JEMBO CABLE COMPANY TBK</h2>
<p>Jl. Pajajaran, Kel. Gandasari, Kec. Jatiuwung, Kota Tangerang, Banten 15137</p>
<p>Telp: (021) 65701511 | Email: info@jembo.com</p>
</div>
</div>
<div class="line"></div>

<h3>PACKING LIST</h3>
<table class="header-table">
<tr><th>Packing List No:</th><td>${packing_no}</td><th>Tanggal UST:</th><td>${tgl_ust}</td></tr>
<tr><th>Jumlah:</th><td>${jumlah}</td><th>No. Kontrak KHS:</th><td>${kontrak}</td></tr>
<tr><th>Tanggal:</th><td>${tanggal}</td><th>Nomor Stiker:</th><td>${stiker}</td></tr>
</table>

<table>
<thead>
<tr>
<th>No</th>
<th>Item</th>
<th>Deskripsi Barang</th>
<th>Kode Serial Material PLN</th>
<th>QR Barcode</th>
<th>Quantity/Volume (meter)</th>
<th>Bulan Produksi</th>
<th>Net (Kg)</th>
<th>Gross (Kg)</th>
</tr>
</thead>
<tbody>
${selectedRows.join("")}
<tr class="total-row">
<td colspan="5">TOTAL</td>
<td>${totalQty.toFixed(2)}</td>
<td></td>
<td>${totalNet.toFixed(2)}</td>
<td>${totalGross.toFixed(2)}</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
`);
            printWindow.document.close();
            // printWindow.print(); // kalau mau langsung print aktifkan ini
        }

        function goBack() {
            window.location.href = "<?= site_url('landing'); ?>";
        }
    </script>


    <!-- Tampilan loading -->
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
        <div style="display: inline-block; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
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

</body>

</html>