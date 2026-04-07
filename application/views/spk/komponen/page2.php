<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPK - Page 2</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
        }
        .wrapper {
            width: 90%;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        th {
            background: #f1f2f6;
        }
        .btn-view {
            padding: 6px 14px;
            background: #0984e3;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-view:hover {
            background: #0767b0;
        }
        .info {
            margin-bottom: 10px;
            color: #2d3436;
            font-weight: bold;
        }

tbody td {
    text-align: center;
    vertical-align: middle;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;   /* TENGAH */
    margin-top: 15px;
}

.pagination-wrapper button {
    padding: 6px 12px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
}

.pagination-wrapper button:hover {
    background: #f1f2f6;
}

.pagination-wrapper button.active {
    background: #0984e3;
    color: white;
    border-color: #0984e3;
}

.pagination-wrapper button:disabled {
    color: #aaa;
    cursor: not-allowed;
}


    </style>
</head>
<body>

<div class="wrapper">

    <div class="header">
    <h3>SPK Data</h3>

    <a href="<?= site_url('spk') ?>" 
       style="
           padding:6px 14px;
           background:#636e72;
           color:#fff;
           border-radius:6px;
           text-decoration:none;
           font-size:12px;
       ">
        ⬅ Home
    </a>
</div>


    <div class="info">Data sudah disimpan</div>

    <div style="margin-bottom: 10px;">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search OP / Sales Order / Customer..."
        style="padding:6px; width:250px;"
    >
</div>


    <table>
        <thead>
            <tr>
                <th>OP</th>
<th>Sales Order</th>
<th>Created At</th>
<th>Cable Type</th>
<th>Customer</th>
<th>Action</th>

            </tr>
        </thead>
        <tbody>
<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r->production_order ?></td>
    <td><?= $r->sales_order ?></td>
   <td><?= date('Y-m-d', strtotime($r->created_at)) ?></td>
<td><?= $r->description ?? '-' ?></td>
<td><?= $r->customer_name ?></td>
    <td>
    <a href="<?= site_url('spk/view_spk/'.$r->id) ?>" class="btn-view">
        VIEW SPK
    </a>

    <button 
        class="btn-view"
        style="background:#6c5ce7"
        onclick="openProcessModal(<?= $r->id ?>)">
        EDIT PROCESS
    </button>
</td>


</tr>
<?php endforeach; ?>
</tbody>


    </table>
    <div id="pagination" class="pagination-wrapper"></div>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;

const tbody = document.querySelector("tbody");
const allRows = Array.from(tbody.querySelectorAll("tr"));
const searchInput = document.getElementById("searchInput");
const pagination = document.getElementById("pagination");

function displayRows(rows) {
    tbody.innerHTML = "";

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.slice(start, end).forEach(row => tbody.appendChild(row));
}

function setupPagination(rows) {
    pagination.innerHTML = "";

    // 🔥 PAKSA MINIMAL 1 HALAMAN
    const pageCount = Math.max(1, Math.ceil(rows.length / rowsPerPage));

    // PREVIOUS
    const prevBtn = document.createElement("button");
    prevBtn.innerText = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            update(rows);
        }
    };
    pagination.appendChild(prevBtn);

    // PAGE NUMBER
    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement("button");
        btn.innerText = i;
        if (i === currentPage) btn.classList.add("active");

        btn.onclick = () => {
            currentPage = i;
            update(rows);
        };

        pagination.appendChild(btn);
    }

    // NEXT
    const nextBtn = document.createElement("button");
    nextBtn.innerText = "Next";
    nextBtn.disabled = currentPage === pageCount;
    nextBtn.onclick = () => {
        if (currentPage < pageCount) {
            currentPage++;
            update(rows);
        }
    };
    pagination.appendChild(nextBtn);
}

function filterTable() {
    const keyword = searchInput.value.toLowerCase();

    return allRows.filter(row => {
        const op = row.cells[0].innerText.toLowerCase();
const so = row.cells[1].innerText.toLowerCase();
const cable = row.cells[3].innerText.toLowerCase();
const customer = row.cells[4].innerText.toLowerCase();

return (
    op.includes(keyword) ||
    so.includes(keyword) ||
    cable.includes(keyword) ||
    customer.includes(keyword)
);
    });
}

function update(rows) {
    displayRows(rows);
    setupPagination(rows);
}

// INIT (WAJIB)
update(allRows);

// SEARCH
searchInput.addEventListener("keyup", () => {
    currentPage = 1;
    update(filterTable());
});
</script>

<div id="processModal" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
    z-index:9999;
">
  <div style="
    background:#fff;
    padding:20px;
    border-radius:10px;
    width:300px;
    pointer-events: auto;
">

        <h4>Edit Process</h4>

        <input type="hidden" id="modal_id_excel">

        <label>
            <input type="checkbox" value="drawing" class="process-check"> Drawing
        </label><br>

        <label>
            <input type="checkbox" value="stranding" class="process-check"> Stranding
        </label><br>

        <label>
            <input type="checkbox" value="insul" class="process-check"> Insulation
        </label><br><br>
<button
    type="button"
    onclick="saveProcess()"
    style="background:#00b894;color:#fff;padding:6px 12px;border:none;border-radius:6px;">
    Simpan
</button>


        <button onclick="closeModal()" style="margin-left:10px;">
            Batal
        </button>
    </div>
</div>


<!-- 🔥 LOAD SWEET ALERT DULU -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function openProcessModal(id) {
    document.getElementById('modal_id_excel').value = id;

    document.querySelectorAll('.process-check')
        .forEach(c => c.checked = false);

    fetch('<?= site_url('spk/get_process/') ?>' + id)
        .then(res => res.json())
        .then(data => {
            data.forEach(p => {
                const cb = document.querySelector('.process-check[value="'+p+'"]');
                if (cb) cb.checked = true;
            });
        });

    document.getElementById('processModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('processModal').style.display = 'none';
}

function saveProcess() {
    console.log('SIMPAN DIKLIK 🔥'); // Cek di console (F12) apakah ini muncul
    
    const id = document.getElementById('modal_id_excel').value;
    const checks = document.querySelectorAll('.process-check:checked');

    let process = [];
    checks.forEach(c => process.push(c.value));

    // Tambahkan loading supaya user tahu sedang proses
    const btnSimpan = event.target;
    btnSimpan.disabled = true;
    btnSimpan.innerText = 'Loading...';

    fetch('<?= site_url("spk/update_process") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_excel: id,
            process: process
        })
    })
    .then(async res => {
        // Cek apakah response-nya OK (200)
        if (!res.ok) {
            const text = await res.text(); // Ambil pesan error mentah
            throw new Error(text);
        }
        return res.json();
    })
    .then(res => {
        if (res.status === 'ok') {
            closeModal();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil 💖',
                text: 'Process berhasil diupdate',
                confirmButtonColor: '#00b894'
            });
        } else {
            throw new Error(res.message || 'Gagal menyimpan');
        }
    })
    .catch(err => {
        console.error('Error detail:', err);
        Swal.fire({
            icon: 'error',
            title: 'Gagal 😢',
            text: 'Terjadi kesalahan: ' + err.message
        });
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerText = 'Simpan';
    });
}
</script>
</body>
</html>
