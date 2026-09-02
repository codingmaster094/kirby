@echo off
cd /d "%~dp0"
echo Starting Kirby at http://localhost:8000
echo Use Ctrl+C to stop.
php -S localhost:8000 router.php
