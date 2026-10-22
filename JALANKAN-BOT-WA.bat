@echo off
title BOT WHATSAPP RESMI SMECK HOOLIGAN (PSMS MEDAN)
color 0A
cls
echo ===================================================================
echo     BOT WHATSAPP MULTI-DEVICE RESMI SMECK HOOLIGAN (PSMS MEDAN)
echo                 POWERED BY BAILEYS - 100%% BEBAS BIAYA
echo ===================================================================
echo.
echo [1/3] Menyiapkan environment Node.js & Git...
set "PATH=C:\Program Files\Git\cmd;C:\Program Files\nodejs;%PATH%"

cd /d "%~dp0whatsapp-bot"

if not exist node_modules (
    echo [2/3] Mengunduh modul dependensi (hanya pertama kali)...
    call npm install
)

echo.
echo [3/3] Memulai Layanan Bot WhatsApp...
echo.
echo -------------------------------------------------------------------
echo  PANDUAN MENGHUBUNGKAN:
echo  1. Tunggu Kode QR muncul di bawah atau di Dashboard Super Admin.
echo  2. Buka WhatsApp di HP Anda -> Titik Tiga (atau Pengaturan)
echo  3. Pilih "Perangkat Tertaut" -> "Tautkan Perangkat"
echo  4. Arahkan kamera HP ke Kode QR di bawah ini!
echo -------------------------------------------------------------------
echo.

node server.js

pause
