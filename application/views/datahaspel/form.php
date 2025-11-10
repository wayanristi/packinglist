<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin-top: 60px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-save {
            background-color: #28a745;
            color: #fff;
            border-radius: 8px;
        }
        .btn-save:hover {
            background-color: #1e7e34;
        }
        .btn-back {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h4 class="fw-bold text-center text-primary mb-4"><?= $title ?></h4>

        <form method="post" action="<?= isset($data) ? site_url('datahaspel/update/'.$data->id) : site_url('datahaspel/store') ?>">
            <div class="mb-3">
                <label class="form-label">Haspel</label>
                <input type="number" name="haspel" class="form-control" value="<?= isset($data) ? $data->haspel : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Panjang (ø hsp+tutup)</label>
                <input type="number" name="panjang" class="form-control" value="<?= isset($data) ? $data->panjang : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lebar Luar</label>
                <input type="number"name="lebar" class="form-control" value="<?= isset($data) ? $data->lebar : '' ?>" required>
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
                <input type="number"name="berat" class="form-control" value="<?= isset($data) ? $data->berat : '' ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= site_url('datahaspel') ?>" class="btn btn-secondary btn-back">Kembali</a>
                <button type="submit" class="btn btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
