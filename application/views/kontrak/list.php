<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Data Nomor Kontrak KHS</h3>
        <div>
            <a href="<?= site_url('packinglist') ?>" class="btn btn-secondary me-2">🏠 Home</a>
            <a href="<?= base_url('index.php/kontrak/add') ?>" class="btn btn-primary">+ Tambah Nomor Kontrak</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Nomor Kontrak KHS</th>
                        <th style="width:20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($kontrak)) { 
                        $no = 1;
                        foreach($kontrak as $k): 
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $k->nomor_kontrak ?></td>
                            <td>
                                <a href="<?= base_url('index.php/kontrak/edit/'.$k->id) ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= $k->id ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    } else { 
                    ?>
                        <tr>
                            <td colspan="3" class="text-center py-3">Belum ada data</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Tombol hapus dengan SweetAlert
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;

        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data ini akan dihapus permanen ...",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch('<?= site_url("kontrak/delete/") ?>' + id, { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
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
