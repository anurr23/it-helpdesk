<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Helpdesk - Terjadi Kesalahan</title>
    <link href="<?php echo config_item('base_url'); ?>assets/css/inter.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #334155;
        }
        .error-container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 64px;
            line-height: 1;
            margin-bottom: 20px;
            display: inline-block;
            background: #fee2e2;
            color: #ef4444;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }
        .error-icon svg {
            width: 50px;
            height: 50px;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 15px 0;
        }
        .message {
            font-size: 15px;
            line-height: 1.6;
            color: #64748b;
            margin: 0 0 30px 0;
        }
        .btn-back {
            display: inline-block;
            background: #3b82f6;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .btn-back:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <?php 
            // Jika pesan error-nya adalah standar CSRF
            if (trim(strip_tags($message)) == 'The action you have requested is not allowed.') {
                $heading = 'Akses Ditolak / Sesi Berakhir';
                $message = '<p>Anda tidak diizinkan melakukan tindakan ini, atau sesi formulir Anda telah kadaluarsa karena dibiarkan terlalu lama. Silakan kembali dan muat ulang (refresh) halaman.</p>';
            }
        ?>

        <h1><?php echo $heading; ?></h1>
        <div class="message">
            <?php echo $message; ?>
        </div>
        
        <a href="javascript:history.back()" class="btn-back">Kembali ke Halaman Sebelumnya</a>
    </div>
</body>
</html>