<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Packing List Standar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

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
.signature {
    margin-top:40px; 
    width:200px; 
    text-align:center; 
    float:right; 
}
.signature .name {
    margin-bottom:60px;
}
.pagination {
    justify-content: center;
    margin-top: 15px;
}
@media print {
    .no-print { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: none; }
    th.line-col, td.line-col { display: none !important; }
    .pagination { display: none; }
}

/* ———————— SPINNER LOADING ———————— */
#loading-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    display: none;
}

.spinner {
    width: 70px;
    height: 70px;
    border: 8px solid #cfcfcf;
    border-top: 8px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body>

<!-- SPINNER LOADING -->
<div id="loading-spinner">
    <div class="spinner"></div>
</div>

<div class="mt-4">
<div class="card p-4">
<h3>PACKING LIST</h3>
<div class="d-flex gap-3 mb-4">
<a href="<?= site_url('datahaspel'); ?>"
   class="btn btn-info shadow-sm"
   style="background: linear-gradient(135deg, #4fc3f7, #81d4fa); border:none; font-weight:600;">
   🗃️ Data Haspel
</a>
</div>

<hr>

<table class="table table-bordered header-table mb-4">
<tr>
<th>Type of Cable:</th>
<td><input type="text" id="type_of_cable"></td>
<th>Location:</th>
<td><input type="text" id="location"></td>
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
<th>Pilih</th>
<th class="line-col">Line</th>
<th>Type Size</th>
<th>Drum Number</th>
<th>Length (M)</th>
<th>Gross (KG)</th>
<th>Netto (KG)</th>
<th>Dimension of Drum (MM*MM*MM = M3)</th>
</tr>
</thead>
<tbody id="tableBody"></tbody>
</table>
</div>

<!-- Pagination -->
<nav class="no-print">
<ul class="pagination" id="pagination"></ul>
</nav>
</div>
</div>

<script>
let locationExcel = "-";
let beratHaspelData = {};
let salesOrderNo = "0000";
let currentPage = 1;
const rowsPerPage = 20;

/* SHOW + HIDE LOADING */
function showLoading() { document.getElementById("loading-spinner").style.display = "flex"; }
function hideLoading() { document.getElementById("loading-spinner").style.display = "none"; }

async function getBeratHaspelFromDB() {
    const response = await fetch("<?= site_url('PackingListStandar/get_berat_haspel_all') ?>");
    return await response.json();
}

async function importExcel() {
    showLoading();
    beratHaspelData = await getBeratHaspelFromDB();

    const fileInput = document.getElementById('excelFile');
    const file = fileInput.files[0];
    if (!file) { hideLoading(); alert("Pilih file Excel terlebih dahulu!"); return; }

    const reader = new FileReader();
    reader.onload = function(e){
        setTimeout(()=>hideLoading(),300);
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data,{type:'array'});
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(sheet,{defval:""});

        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = "";
        const grouped = {};

        jsonData.forEach((row,i)=>{
            const desc = row["Description"] || "";
            if(!desc.trim()) return;
            const match = desc.match(/^([A-Za-z\/\s\.\-\dV]+?)(?=\s+\d|$)/);
            const prefix = match?match[1].trim():desc.trim();
            const detail = desc.replace(prefix,"").trim();

            const stdDesc = row["Standard Description"] || "";
            const numMatch = stdDesc.match(/\b(\d{2,3})\b/);
            const drumNumber = numMatch?`${numMatch[1]} - ${row["Lot"] || ""}`:row["Lot"] || "";
            const haspelNumber = numMatch?numMatch[1]:null;

            let beratHaspel = 0;
            let grossDisplay = "";
            let keteranganBerat = "";
            let dimensiDisplay = "-";

            if(haspelNumber && beratHaspelData[haspelNumber]){
                const dataH = beratHaspelData[haspelNumber];
                beratHaspel = parseFloat(dataH.berat ||0);
                const p = parseFloat(dataH.panjang)||0;
                const l = parseFloat(dataH.lebar)||0;
                const t = parseFloat(dataH.tinggi)||0;
                const m3 = parseFloat(dataH.m3)||0;
                dimensiDisplay = `${p} × ${l} × ${t} = ${m3.toFixed(1)}`;
            }else{
                keteranganBerat="Data haspel belum ada";
                dimensiDisplay="Data belum ada";
            }

            const lineData = row["Sales Order Lines"] || "";
            const quantityLot = parseFloat(row["Quantity Lot"])||0;
            const weight = parseFloat(row["Weight"])||0;
            const netto = quantityLot*weight;
            const gross = beratHaspel>0? netto+beratHaspel: netto;
            grossDisplay = keteranganBerat? keteranganBerat: Math.round(gross);

            if(!grouped[prefix]) grouped[prefix] = [];
            grouped[prefix].push({
                line: lineData,
                detail: detail||"-",
                drumNumber: drumNumber,
                lengthM: Math.round(quantityLot),
                gross: grossDisplay,
                netto: Math.round(netto),
                dimension: dimensiDisplay
            });

            if(i===0){
                locationExcel=row["CustomerName"]||"-";
                document.getElementById("location").value = locationExcel;
                salesOrderNo=row["Sales Order"]||"0000";
            }
        });

        for(const prefix in grouped){
            const groupData = grouped[prefix];
            const prefixRow = document.createElement("tr");
            prefixRow.innerHTML = `<td></td><td class="line-col"></td>
            <td style="font-weight:bold; text-align:left;">${prefix}</td>
            <td colspan="5"></td>`;
            tableBody.appendChild(prefixRow);

            let lastLine=null;
            groupData.forEach((item)=>{
                if(lastLine!==null && item.line!==lastLine){
                    const emptyRow=document.createElement("tr");
                    emptyRow.innerHTML=`<td colspan="8" style="height:10px; background:#f8f9fa;"></td>`;
                    tableBody.appendChild(emptyRow);
                }
                const tr=document.createElement("tr");
                tr.innerHTML=`<td><input type="checkbox" class="row-check"></td>
                <td class="line-col">${item.line}</td>
                <td style="text-align:left;">${item.detail}</td>
                <td>${item.drumNumber}</td>
                <td>${item.lengthM}</td>
                <td>${item.gross}</td>
                <td>${item.netto}</td>
                <td>${item.dimension}</td>`;
                tableBody.appendChild(tr);
                lastLine=item.line;
            });
        }
        currentPage=1;
        displayPagination();
    };
    reader.readAsArrayBuffer(file);
}

// PAGINATION (PERBAIKAN SESUAI REQUEST)
function displayPagination(){
    const table=document.getElementById("tableBody");
    const rows=Array.from(table.querySelectorAll("tr"));
    const totalPages=Math.ceil(rows.length/rowsPerPage);
    const pagination=document.getElementById("pagination");
    pagination.innerHTML="";

    if(totalPages<=1) return;

    function createPageItem(num,text){
        const li=document.createElement("li");
        li.className="page-item"+(currentPage===num?" active":"");
        li.innerHTML=`<a class="page-link" href="#">${text}</a>`;
        li.onclick=e=>{
            e.preventDefault();
            currentPage=num;
            displayPage(rows);
            displayPagination();
        };
        return li;
    }

    // Previous button
    const prevLi=document.createElement("li");
    prevLi.className="page-item"+(currentPage===1?" disabled":"");
    prevLi.innerHTML=`<a class="page-link" href="#">Previous</a>`;
    prevLi.onclick=e=>{ e.preventDefault(); if(currentPage>1){currentPage--; displayPage(rows); displayPagination();} };
    pagination.appendChild(prevLi);

    // LOGIKA PAGINATION DENGAN TITIK-TITIK
    let pageList=[];
    if(totalPages<=7){
        for(let i=1;i<=totalPages;i++) pageList.push(i);
    }else{
        if(currentPage<=4){
            pageList=[1,2,3,4,5,'...',totalPages-1,totalPages];
        }else if(currentPage>=totalPages-3){
            pageList=[1,2,'...',totalPages-4,totalPages-3,totalPages-2,totalPages-1,totalPages];
        }else{
            pageList=[1,2,'...',currentPage-1,currentPage,currentPage+1,'...',totalPages-1,totalPages];
        }
    }

    pageList.forEach(p=>{
        if(p==='...'){
            const dots=document.createElement("li");
            dots.className="page-item disabled";
            dots.innerHTML=`<span class="page-link">...</span>`;
            pagination.appendChild(dots);
        }else{
            pagination.appendChild(createPageItem(p,p));
        }
    });

    // Next button
    const nextLi=document.createElement("li");
    nextLi.className="page-item"+(currentPage===totalPages?" disabled":"");
    nextLi.innerHTML=`<a class="page-link" href="#">Next</a>`;
    nextLi.onclick=e=>{ e.preventDefault(); if(currentPage<totalPages){currentPage++; displayPage(rows); displayPagination();} };
    pagination.appendChild(nextLi);

    displayPage(rows);
}

function displayPage(rows){
    rows.forEach((row,index)=>{ row.style.display="none"; });
    const start=(currentPage-1)*rowsPerPage;
    const end=start+rowsPerPage;
    for(let i=start;i<end&&i<rows.length;i++) rows[i].style.display="";
}

// PRINT FUNCTION
function printSelected(){
    const allRows=document.querySelectorAll("#tableBody tr");
    const selectedRows=[];
    let noUrut=1;
    let totalGross=0;
    let totalNetto=0;
    let totalLength=0;
    let totalM3=0;
    let totalDrums=0;
    let currentPrefix="";
    let lastPrefix=null;
    let lastLine=null;

    allRows.forEach(row=>{
        const prefixCell=row.querySelector("td[style*='font-weight:bold']");
        if(prefixCell) currentPrefix=prefixCell.textContent.trim();

        const check=row.querySelector(".row-check");
        if(check && check.checked){
            const tds=row.querySelectorAll("td");
            totalDrums++;

            const grossText=tds[5].textContent.trim();
            const grossVal=parseFloat(grossText)||0;
            const nettoVal=parseFloat(tds[6].textContent)||0;
            const lengthVal=parseFloat(tds[4].textContent)||0;
            let m3Val=0;
            const dimText=tds[7].textContent.trim();
            const match=dimText.match(/=\s*([\d,.]+)/);
            if(match){ m3Val=parseFloat(match[1].replace(/,/g,''))||0; }

            if(!grossText.includes("Data haspel")) totalGross+=Math.round(grossVal);
            totalNetto+=Math.round(nettoVal);
            totalLength+=Math.round(lengthVal);
            totalM3+=m3Val;

            if(lastLine!==null && tds[1].textContent.trim()!==lastLine){
                selectedRows.push(`<tr><td colspan="8" style="height:8px; background:#f8f9fa;"></td></tr>`);
            }
            if(currentPrefix!==lastPrefix){
                selectedRows.push(`<tr><td></td><td style="text-align:left; font-weight:bold;">${currentPrefix}</td><td colspan="5"></td></tr>`);
                lastPrefix=currentPrefix;
            }
            selectedRows.push(`<tr>
                <td>${noUrut}</td>
                <td style="text-align:left;">${tds[2].textContent}</td>
                <td>${tds[3].textContent}</td>
                <td>${tds[4].textContent}</td>
                <td>${tds[5].textContent}</td>
                <td>${tds[6].textContent}</td>
                <td>${tds[7].textContent}</td>
            </tr>`);
            noUrut++;
            lastLine=tds[1].textContent.trim();
        }
    });

    if(selectedRows.length===0){ alert("Pilih minimal satu data untuk dicetak!"); return; }

    const today=new Date();
    const type_of_cable=document.getElementById('type_of_cable').value||'-';
    const manualNo=prompt("Masukkan nomor SOP:","")||"----";
    const year=today.getFullYear();
    const fullNo=`No. ${manualNo}/PP/${salesOrderNo}/${year}/P`;

    const options={ day:'2-digit', month:'short', year:'numeric' };
    const todayStr=today.toLocaleDateString('en-GB',options).replace(/ /g,'-');

    const printWindow=window.open('','', 'width=1000,height=800');
    printWindow.document.write(`
<html>
<head>
<title>Packing List</title>
<style>
body { font-family: Arial, sans-serif; margin:20px; }
h3 { text-align: center; font-size:18px; font-weight:bold; margin-bottom:5px; }
.header-info { width:100%; margin-bottom:10px; }
.header-info table { width:100%; border-collapse:collapse; }
.header-info th, .header-info td { text-align:left; padding:4px 6px; font-size:13px; }
.date { text-align:right; font-size:12px; margin-bottom:5px; }
table { width:100%; border-collapse: collapse; font-size:13px; margin-top:5px; }
th, td { border: 1px solid #000; padding: 6px 8px; text-align:center; vertical-align:middle; }
.total-row td { font-weight:bold; }
.signature { margin-top:40px; width:200px; text-align:center; float:right; }
.signature .name { margin-bottom:60px; }
</style>
</head>
<body>
<div class="date">Date: ${todayStr}</div>
<h3>PACKING LIST</h3>
<p style="text-align:center; margin-top:-5px; margin-bottom:15px;">${fullNo}</p>

<div class="header-info">
<table>
<tr>
<th>Type of Cable:</th><td>${type_of_cable}</td>
<th>Location:</th><td>${locationExcel}</td>
</tr>
</table>
</div>

<table>
<thead>
<tr>
<th>No</th>
<th>Type Size</th>
<th>Drum Number</th>
<th>Length (M)</th>
<th>Gross (KG)</th>
<th>Netto (KG)</th>
<th>Dimension of Drum</th>
</tr>
</thead>
<tbody>
${selectedRows.join("")}
<tr class="total-row">
<td colspan="2">TOTAL</td>
<td>${totalDrums} Drums</td>
<td>${totalLength}</td>
<td>${totalGross}</td>
<td>${totalNetto}</td>
<td>${totalM3.toFixed(1)}</td>
</tr>
</tbody>
</table>

<div class="signature">
<div>PREPARED BY</div>
<div class="name"></div>
<div>PRODUCTION PLANNING CONTROL</div>
</div>

</body>
</html>
    `);
    printWindow.document.close();
}
</script>

</body>
</html>
