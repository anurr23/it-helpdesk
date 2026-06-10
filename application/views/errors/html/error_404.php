<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = config_item('base_url');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= $base_url ?>assets/css/inter.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/css/material-symbols.css" rel="stylesheet">
    <style>
        :root {
            --primary: #003d9b;
            --secondary: #00b4d8;
            --accent: #ff8fa3;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.05),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff8fa3 0%, #ff4d6d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 20px rgba(255, 77, 109, 0.3);
            color: white;
        }

        .icon-wrapper .material-symbols-outlined {
            font-size: 40px;
        }

        h1 {
            color: #102a43;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        p {
            color: #627d98;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="glass-card">
        <div class="icon-wrapper">
            <span class="material-symbols-outlined">explore_off</span>
        </div>
        <h1>404 Not Found</h1>
        <p>Maaf, halaman yang Anda tuju sepertinya tidak ada atau sudah dipindahkan.</p>
    </div>

</body>
</html>