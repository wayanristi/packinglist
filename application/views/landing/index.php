<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PT Jembo - Sistem Packing List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="relative min-h-screen text-white">

  <!-- Background Image -->
  <div class="absolute inset-0 z-0">
    <img src="<?= base_url('assets/images/pj.jpg'); ?>" alt="Background Jembo" class="w-full h-full object-cover"/>
    <div class="absolute inset-0 bg-black/40"></div>
  </div>

  <!-- Content Overlay -->
 <div class="relative z-10 flex items-center justify-center min-h-screen px-6">
  <div class="text-center max-w-xl w-full 
              bg-white/10 backdrop-blur-md 
              border border-white/20 
              rounded-2xl p-8 shadow-2xl">

    <!-- Logo + Title -->
    <div class="flex flex-col items-center mb-6">
      <h1 class="text-xl font-semibold tracking-wide uppercase">PT JEMBO CABLE COMPANY Tbk</h1>
      <p class="text-sm text-white/80 mt-1">Sistem Packing List</p>
    </div>

    <!-- Welcome + Buttons -->
    <h2 class="text-4xl font-bold mb-2">Selamat Datang 👋</h2>
    <p class="text-lg mb-8">Pilih jenis daftar yang ingin Anda buat</p>

    <div class="space-y-4">
      <a href="<?= site_url('packinglist'); ?>" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow transition">
        📦 Packing List PLN
      </a>
      <a href="<?= site_url('packingliststandar'); ?>" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-xl shadow transition">
        📦 Packing List Non PLN
      </a>
    </div>

  </div>
</div>


</body>
</html>
