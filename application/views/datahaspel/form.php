<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f9fafb; }
        .container { max-width: 600px; margin-top: 60px; }
        .card { border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .btn-save { background-color: #28a745; color: #fff; border-radius: 8px; }
        .btn-save:hover { background-color: #1e7e34; }
        .btn-back { border-radius: 8px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h4 class="fw-bold text-center text-primary mb-4"><?= $title ?></h4>

        <form method="post" id="formHaspel" action="<?= isset($data) ? site_url('datahaspel/update/'.$data->id) : site_url('datahaspel/store') ?>">
            <div class="mb-3">
                <label class="form-label">Haspel</label>
                <input type="text" name="haspel" class="form-control" value="<?= isset($data) ? $data->haspel : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Panjang (ø hsp+tutup)</label>
                <input type="number" name="panjang" class="form-control" value="<?= isset($data) ? $data->panjang : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lebar Luar</label>
                <input type="number" name="lebar" class="form-control" value="<?= isset($data) ? $data->lebar : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tinggi (ø hsp+tutup)</label>
                <input type="number" name="tinggi" class="form-control" value="<?= isset($data) ? $data->tinggi : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">M3 (kubik)</label>
                <input type="number" step="0.01" name="m3" class="form-control" value="<?= isset($data) ? $data->m3 : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Berat</label>
                <input type="number" name="berat" class="form-control" value="<?= isset($data) ? $data->berat : '' ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= site_url('datahaspel') ?>" class="btn btn-secondary btn-back">Kembali</a>
                <button type="submit" class="btn btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('formHaspel').addEventListener('submit', function(e) {
    e.preventDefault();

    // Tampilkan loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Harap tunggu sebentar 😃',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Tambahan header supaya CI bisa deteksi Ajax
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                window.location.href = '<?= site_url("datahaspel") ?>';
            });
        } else {
            Swal.fire('Oops...', data.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Oops...', 'Terjadi kesalahan server 😢', 'error');
        console.error(err);
    });
});
</script>

</body>
</html>
