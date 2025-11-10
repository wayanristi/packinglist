<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Jembo - Sistem Packing List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: radial-gradient(circle at top left, #e0f7fa, #ffffff, #fce4ec);
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            padding: 40px 20px 20px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            width: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        header img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
            transition: transform 0.5s ease;
        }

        header img:hover {
            transform: scale(1.1);
        }

        header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #34495e;
        }

        header p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .content {
            text-align: center;
            margin-top: 50px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .content h2 {
            font-weight: 700;
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .content p {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        .nav-btn {
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            color: #fff;
            border-radius: 30px;
            font-weight: 600;
            padding: 14px 40px;
            text-transform: uppercase;
            transition: all 0.4s ease;
            border: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .nav-btn:hover {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            color: #fff;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        footer {
            padding: 15px;
            text-align: center;
            color: #777;
            font-size: 0.9rem;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(6px);
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .nav-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .nav-btn i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <header>
        <img src="<?= base_url('assets/images/logo_jembo.jpg'); ?>" alt="Logo PT Jembo">
        <h1>PT JEMBO CABLE COMPANY Tbk</h1>
        <p>Sistem Packing List </p>
    </header>

    <main class="content">
        <h2>Selamat Datang 👋</h2>
        <p>Kelola data packing list dengan mudah, cepat, dan efisien.</p>

        <div class="nav-container">
            <a href="<?= site_url('packinglist'); ?>" class="btn nav-btn">
                📦 Packing List
            </a>
            <a href="<?= site_url('material'); ?>" class="btn nav-btn">
                ⚙️ Data Material
            </a>
            <a href="<?= site_url('datahaspel'); ?>" class="btn nav-btn">
                🧩 Data Haspel
            </a>
        </div>
    </main>

    <footer>
        &copy; <?= date('Y'); ?> <strong>Ni Wayan Risti Aningsih</strong> — PT Jembo Cable Company Tbk.
    </footer>

</body>
</html>
