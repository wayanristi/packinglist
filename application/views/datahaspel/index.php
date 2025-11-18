<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="text-center mb-4"><?= $title ?></h3>

    <div class="d-flex mb-3">
    <a href="<?= site_url('datahaspel/create') ?>" class="btn btn-success me-2">+ Tambah Data</a>
    <div class="ms-auto">
        <a href="<?= site_url('packinglist') ?>" class="btn btn-secondary me-2">🏠 PL-PLN</a>
        <a href="<?= site_url('packingliststandar') ?>" class="btn btn-secondary">🏠 PL-NONPLN</a>
    </div>
</div>


    <table class="table table-bordered table-striped table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Haspel</th>
                <th>Panjang</th>
                <th>Lebar</th>
                <th>Tinggi</th>
                <th>M3</th>
                <th>Berat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($datahaspel)): ?>
                <tr><td colspan="8" class="text-muted">Belum ada data</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($datahaspel as $row): ?>
                <tr id="row-<?= $row->id ?>">
                    <td><?= $no++ ?></td>
                    <td><?= $row->haspel ?></td>
                    <td><?= $row->panjang ?></td>
                    <td><?= $row->lebar ?></td>
                    <td><?= $row->tinggi ?></td>
                    <td><?= $row->m3 ?></td>
                    <td><?= $row->berat ?></td>
                    <td>
                        <a href="<?= site_url('datahaspel/edit/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $row->id ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
// Hapus data dengan SweetAlert + Ajax
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;

        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data ini akan dihapus permanen....",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch('<?= site_url("datahaspel/delete/") ?>' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire('Terhapus!', data.message, 'success');
                        // Hapus baris table tanpa reload
                        const row = document.getElementById('row-' + id);
                        if(row) row.remove();
                    } else {
                        Swal.fire('Oops...', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Oops...', 'Terjadi kesalahan server 😢', 'error');
                    console.error(err);
                });
            }
        });
    });
});
</script>

</body>
</html>
