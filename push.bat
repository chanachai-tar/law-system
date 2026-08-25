@echo off
chcp 65001 >nul
echo ========================================================
echo   ODPC10-LSS Git Auto-Push to GitHub
echo   Repository: https://github.com/chanachai-tar/law-system
echo ========================================================
echo.

set /p msg="ระบุข้อความอธิบายการแก้ไข (กด Enter เพื่อใช้ค่าเริ่มต้น): "

if "%msg%"=="" (
    set msg=Update law-system: %date% %time%
)

echo.
echo [1/3] กำลังจัดเตรียมไฟล์ (git add .)...
git add .

echo [2/3] กำลังบันทึกการแก้ไข (git commit)...
git commit -m "%msg%"

echo [3/3] กำลังส่งขึ้น GitHub (git push)...
git push origin main

echo.
if %ERRORLEVEL% EQU 0 (
    echo ========================================================
    echo   [SUCCESS] อัปโหลดโปรเจกต์ขึ้น GitHub สำเร็จเรียบร้อยแล้ว!
    echo ========================================================
    echo.
    echo กำลังส่งการแจ้งเตือนการอัปเดตไปยัง Telegram...
    php artisan app:notify-update "%msg%"
) else (
    echo ========================================================
    echo   [ERROR] เกิดข้อผิดพลาดในการอัปโหลด
    echo ========================================================
)
echo.
timeout /t 5
