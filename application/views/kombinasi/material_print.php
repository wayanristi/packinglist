<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Material</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #ddd; }
        h2 { text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <h2>Data Material PT Jembo</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi Material</th>
                <th>Identitas Jenis Material</th>
                <th>Kode Pabrik Jembo</th>
                <th>Tahun</th>
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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
