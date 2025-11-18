<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3>Edit Nomor Kontrak KHS</h3>
    <form id="formKontrak" class="mt-3">
        <div class="mb-3">
            <label for="nomor_kontrak" class="form-label">Nomor Kontrak KHS</label>
            <input type="text" name="nomor_kontrak" id="nomor_kontrak" class="form-control" value="<?= $kontrak->nomor_kontrak ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= base_url('index.php/kontrak') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
document.getElementById('formKontrak').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Menyimpan...',
        text: 'Harap tunggu sebentar 😃',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('<?= site_url("kontrak/update/".$kontrak->id) ?>', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                window.location.href = '<?= site_url("kontrak") ?>';
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
