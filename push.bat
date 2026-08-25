@echo off
chcp 65001 >nul
echo ========================================================
echo   ODPC10-LSS Git Auto-Push to GitHub
echo   Repository: https://github.com/chanachai-tar/law-system
echo ========================================================
echo.

powershell -ExecutionPolicy Bypass -File .\push.ps1 %*

echo.
timeout /t 5
