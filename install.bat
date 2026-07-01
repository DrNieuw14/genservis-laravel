@echo off
title GenServis Deployment Installer
color 0A

echo.
echo =====================================================
echo          GenServis Deployment Installer
echo =====================================================
echo.

REM ------------------------------------------------------
REM Check Composer
REM ------------------------------------------------------

where composer >nul 2>nul

if %errorlevel% neq 0 (
    color 0C
    echo [ERROR] Composer is NOT installed.
    echo.
    echo Download:
    echo https://getcomposer.org/
    pause
    exit
)

echo [OK] Composer detected.

echo.

REM ------------------------------------------------------
REM Check NodeJS
REM ------------------------------------------------------

where npm >nul 2>nul

if %errorlevel% neq 0 (
    color 0C
    echo [ERROR] NodeJS is NOT installed.
    echo.
    echo Download:
    echo https://nodejs.org/
    pause
    exit
)

echo [OK] NodeJS detected.

echo.

REM ------------------------------------------------------
REM Check PHP
REM ------------------------------------------------------

where php >nul 2>nul

if %errorlevel% neq 0 (
    color 0C
    echo [ERROR] PHP is NOT installed or not in PATH.
    pause
    exit
)

echo [OK] PHP detected.

echo.

REM ------------------------------------------------------
REM Check Artisan
REM ------------------------------------------------------

if not exist artisan (
    color 0C
    echo [ERROR] artisan file not found.
    echo Run this installer inside the Laravel project.
    pause
    exit
)

echo [OK] Laravel project detected.

echo.

REM ------------------------------------------------------
REM Create .env
REM ------------------------------------------------------

if not exist ".env" (
    copy .env.example .env
    echo [OK] .env created.
) else (
    echo [OK] .env already exists.
)

echo.

REM ------------------------------------------------------
REM Install Composer Packages
REM ------------------------------------------------------

echo Installing Composer packages...
composer install

echo.

REM ------------------------------------------------------
REM Install Node Packages
REM ------------------------------------------------------

echo Installing Node packages...
npm install

echo.

REM ------------------------------------------------------
REM Generate APP KEY
REM ------------------------------------------------------

php artisan key:generate

echo.

REM ------------------------------------------------------
REM Storage Link
REM ------------------------------------------------------

php artisan storage:link

echo.

REM ------------------------------------------------------
REM Build Vite
REM ------------------------------------------------------

echo Building frontend...
npm run build

echo.

REM ------------------------------------------------------
REM Clear Cache
REM ------------------------------------------------------

php artisan optimize:clear

echo.

REM ------------------------------------------------------
REM Optimize Laravel
REM ------------------------------------------------------

php artisan optimize

echo.

REM ------------------------------------------------------
REM Success
REM ------------------------------------------------------

color 0A

echo =====================================================
echo          INSTALLATION COMPLETED
echo =====================================================
echo.
echo Next Steps:
echo.
echo 1. Start Apache
echo 2. Start MySQL
echo 3. Import your SQL database
echo 4. Run:
echo.
echo    php artisan serve
echo.
echo Then open:
echo.
echo http://127.0.0.1:8000
echo.
pause