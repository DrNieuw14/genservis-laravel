@echo off
title GenServis Launcher
color 0A

cd /d "%~dp0"

echo ==========================================
echo        Starting GenServis...
echo ==========================================
echo.

echo Starting Laravel Server...
start "Laravel Server" cmd /k "php artisan serve"

timeout /t 3 >nul

echo Starting Laravel Reverb...
start "Laravel Reverb" cmd /k "php artisan reverb:start"

timeout /t 3 >nul

echo Starting Vite...
start "Vite Development Server" cmd /k "npm run dev"

timeout /t 3 >nul

echo Opening Browser...
start http://127.0.0.1:8000

echo.
echo ==========================================
echo GenServis Started Successfully!
echo ==========================================
echo.
pause