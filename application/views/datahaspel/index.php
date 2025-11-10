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
            margin-top: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn-add {
            background-color: #007bff;
            color: #fff;
            border-radius: 8px;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary">📦 <?= $title ?></h3>
    <div>
        <a href="<?= site_url('landing') ?>" class="btn btn-secondary me-2">🏠 Home</a>
        <a href="<?= site_url('datahaspel/create') ?>" class="btn btn-add">+ Tambah Data</a>
    </div>
</div>


        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Haspel</th>
                    <th>Panjang (ø hsp+tutup)</th>
                    <th>Lebar Luar</th>
                    <th>Tinggi (ø hsp+tutup)</th>
                    <th>M3 (kubik)</th>
                    <th>Berat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($datahaspel)): ?>
                    <tr><td colspan="8" class="text-muted">Belum ada data</td></tr>
                <?php else: ?>
                    <?php $no=1; foreach($datahaspel as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row->haspel ?></td>
                        <td><?= $row->panjang ?></td>
                        <td><?= $row->lebar ?></td>
                        <td><?= $row->tinggi ?></td>
                        <td><?= $row->m3 ?></td>
                        <td><?= $row->berat ?></td>
                        <td>
                            <a href="<?= site_url('datahaspel/edit/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('datahaspel/delete/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                        
                        
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
