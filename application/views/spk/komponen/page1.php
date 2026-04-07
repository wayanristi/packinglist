<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPK - Import & Process</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- XLSX JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen flex items-center justify-center">


<div class="w-[420px] bg-white rounded-2xl shadow-xl p-7">
    <h2 class="text-2xl font-bold text-center mb-6 text-slate-700">
        SPK PROCESS
    </h2>

    <!-- FORM START -->
    <form action="<?= site_url('spk/process_excel') ?>" method="post">

        <!-- IMPORT EXCEL -->
        <label class="block mb-2 font-semibold text-slate-600">Import Excel Infor</label>
        <input 
            type="file"
            id="excelFile"
            accept=".xls,.xlsx"
            onchange="importExcel()"
            class="w-full border border-slate-300 rounded-xl px-4 py-2 mb-4"
        >

        <!-- BADGES SELECTED -->
        <div id="selectedList" class="flex flex-wrap gap-2 mt-4"></div>

        <!-- HIDDEN INPUT PROCESS[] -->
        <div id="hiddenInputs"></div>

        <!-- 🔑 HIDDEN INPUT EXCEL JSON -->
        <input type="hidden" name="excel_json" id="excel_json">

        <!-- NEXT -->
        <button 
    type="submit"
    id="btnNext"
    disabled
    class="w-full bg-gray-300 text-white font-semibold py-3 rounded-xl mt-6
           cursor-not-allowed transition">
    NEXT
</button>


    </form>
    <!-- FORM END -->
</div>

<script>
/* =========================
   MULTI SELECT (TETAP)
========================= */
let available = ['drawing', 'stranding', 'insul'];
let selected  = [];

function toggleOptions() {
    document.getElementById('options').classList.toggle('hidden');
    renderOptions();
}

function selectProcess(value) {
    if (!selected.includes(value)) {
        selected.push(value);
        available = available.filter(v => v !== value);
        render();
        updateNextButton(); // ⬅️ TAMBAH INI
    }
}


function removeProcess(value) {
    selected = selected.filter(v => v !== value);
    available.push(value);
    render();
    updateNextButton(); // ⬅️ TAMBAH INI
}


function renderOptions() {
    let opt = document.getElementById('options');
    opt.innerHTML = '';
    available.forEach(v => {
        opt.innerHTML += `
            <div 
                onclick="selectProcess('${v}')"
                class="px-4 py-3 cursor-pointer hover:bg-slate-100
                       text-slate-700 font-medium">
                ${v.toUpperCase()}
            </div>`;
    });
}

function render() {
    renderOptions();

    // badge
    let list = document.getElementById('selectedList');
    list.innerHTML = '';
    selected.forEach(v => {
        list.innerHTML += `
            <span class="flex items-center gap-2 bg-blue-100 text-blue-700
                         px-3 py-1 rounded-full text-sm font-semibold">
                ${v.toUpperCase()}
                <button 
                    type="button"
                    onclick="removeProcess('${v}')"
                    class="text-blue-700 hover:text-red-500 font-bold">
                    ×
                </button>
            </span>`;
    });

    // hidden input process[]
    let hidden = document.getElementById('hiddenInputs');
    hidden.innerHTML = '';
    selected.forEach(v => {
        hidden.innerHTML += `<input type="hidden" name="process[]" value="${v}">`;
    });

    document.getElementById('options').classList.add('hidden');
}

/* =========================
   IMPORT EXCEL (AUTO)
========================= */
function importExcel() {
    const fileInput = document.getElementById('excelFile');
    if (!fileInput.files.length) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
       const rows = XLSX.utils.sheet_to_json(sheet, {
    defval: "",
    raw: false
});


        if (!rows.length) {
            alert("Excel kosong!");
            return;
        }

        // 🔥 SIMPAN KE HIDDEN INPUT (INI YANG DIKIRIM KE PHP)
     document.getElementById('excel_json').value = JSON.stringify(rows);

// AKTIFKAN BUTTON NEXT
const btn = document.getElementById('btnNext');
btn.disabled = false;
btn.classList.remove('bg-gray-300','cursor-not-allowed');
btn.classList.add('bg-emerald-500','hover:bg-emerald-600','cursor-pointer');

console.log('Excel siap dikirim', rows);
    };

    reader.readAsArrayBuffer(fileInput.files[0]);
}
</script>

<script>
function updateNextButton() {
    const btn = document.getElementById('btnNext');

    if (selected.length > 0) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
        btn.classList.add('bg-emerald-500', 'hover:bg-emerald-600', 'cursor-pointer');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-300', 'cursor-not-allowed');
        btn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600', 'cursor-pointer');
    }
}


</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($this->session->flashdata('error')): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'error',
        title: 'Data Tidak Ditemukan',
        text: '<?= $this->session->flashdata('error') ?>',
        confirmButtonColor: '#d33'
    });
});
</script>
<?php endif; ?>
</body>

<div class="fixed top-6 left-6 flex gap-2 z-50">
    <!-- LIHAT DATA -->
    <a href="<?= site_url('spk/next') ?>"
       class="px-4 py-2 rounded-xl bg-blue-500
              text-white font-semibold hover:bg-blue-600 transition shadow-md">
        📊 Lihat Data
    </a>

    <!-- HOME -->
    <a href="<?= site_url('landing') ?>"
       class="px-4 py-2 rounded-xl bg-slate-200
              text-slate-700 font-semibold hover:bg-slate-300 transition shadow-md">
        ⬅ Home
    </a>
</div>
</html>
