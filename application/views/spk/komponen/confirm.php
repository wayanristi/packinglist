<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi</title>
    <style>
        body {
            font-family: Arial;
            background:#f1f2f6;
            display:flex;
            align-items:center;
            justify-content:center;
            height:100vh;
        }
        .box {
            background:#fff;
            padding:25px;
            border-radius:10px;
            width:360px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,.1);
        }
        button {
            padding:10px 18px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            margin:8px;
        }
        .yes { background:#0984e3; color:white; }
        .no  { background:#dfe6e9; }
    </style>
</head>
<body>

<div class="box">
    <h3>⚠️ Data Sudah Ada</h3>
    <p>OP <b><?= $op ?></b> sudah ada.</p>
    <p>Apakah ingin <b>memproses & menimpa data lama</b>?</p>

    <!-- PROSES -->
    <form method="post" action="<?= site_url('spk/process_excel') ?>">
        <input type="hidden" name="excel_json" value='<?= htmlspecialchars($this->session->userdata("pending_excel_json")) ?>'>
        <?php foreach ($this->session->userdata('pending_process') as $p): ?>
            <input type="hidden" name="process[]" value="<?= $p ?>">
        <?php endforeach; ?>
        <input type="hidden" name="confirm" value="1">
        <button class="yes">PROSES</button>
    </form>

    <!-- TIDAK -->
    <a href="<?= site_url('spk') ?>">
        <button class="no">TIDAK</button>
    </a>
</div>

</body>
</html>
