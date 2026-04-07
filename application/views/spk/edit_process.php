<!DOCTYPE html>
<html>
<head>
    <title>Edit Process SPK</title>
</head>
<body>

<h3>Edit Process untuk OP: <?= $excel->production_order ?></h3>

<form method="post" action="<?= site_url('spk/update_process/'.$excel->id) ?>">

    <label>
        <input type="checkbox" name="process[]" value="drawing"
            <?= in_array('drawing', $process) ? 'checked' : '' ?>>
        Drawing
    </label><br>

    <label>
        <input type="checkbox" name="process[]" value="stranding"
            <?= in_array('stranding', $process) ? 'checked' : '' ?>>
        Stranding
    </label><br>

    <label>
        <input type="checkbox" name="process[]" value="insul"
            <?= in_array('insul', $process) ? 'checked' : '' ?>>
        Insulation
    </label><br><br>

    <button type="submit">💾 Simpan</button>
    <a href="<?= site_url('spk/next') ?>">Batal</a>
</form>

</body>
</html>
