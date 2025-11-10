<!DOCTYPE html>
<html>
<head>
    <title>Form Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3><?= isset($material) ? 'Edit Data Material' : 'Tambah Data Material' ?></h3>
    <form method="post">
        <div class="mb-3">
            <label>Deskripsi Material</label>
            <input type="text" name="deskripsi_material" class="form-control" value="<?= isset($material) ? $material->deskripsi_material : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Identitas Jenis Material</label>
            <input type="text" name="identitas_jenis_material" class="form-control" value="<?= isset($material) ? $material->identitas_jenis_material : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Kode Pabrik Jembo</label>
            <input type="text" name="kode_pabrik_jembo" class="form-control" value="<?= isset($material) ? $material->kode_pabrik_jembo : '' ?>" required>
        </div>
        <div class="mb-3">
    <label>Tahun</label>
    <input type="text" name="tahun" class="form-control"
        value="<?= isset($material) ? $material->tahun : date('Y') ?>" readonly>
</div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('material') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
