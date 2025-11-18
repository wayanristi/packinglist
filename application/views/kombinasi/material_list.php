<!DOCTYPE html>
<html>
<head>
    <title>Data Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="text-center mb-4">Data Material PT Jembo Cable Company Tbk</h3>

    <div class="d-flex justify-content-between mb-3">
        <a href="<?= site_url('material/tambah') ?>" class="btn btn-success">+ Tambah MIMS</a>
        <button class="btn btn-secondary" onclick="goBack()">🏠 Home</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Deskripsi Material</th>
                <th>Identitas Jenis Material</th>
                <th>Kode Pabrik Jembo</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($materials as $m): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $m->deskripsi_material ?></td>
                <td><?= $m->identitas_jenis_material ?></td>
                <td><?= $m->kode_pabrik_jembo ?></td>
                <td><?= $m->tahun ?></td>
                <td>
                    <a href="<?= site_url('material/edit/'.$m->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $m->id ?>">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function goBack() {
    window.location.href = "<?= site_url('packinglist'); ?>";
}

// Tombol hapus dengan SweetAlert + Ajax
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;

        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data ini akan dihapus permanen?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch('<?= site_url("material/hapus/") ?>' + id, {
                    method: 'POST'
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire('Terhapus!', data.message, 'success')
                        .then(() => location.reload());
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
