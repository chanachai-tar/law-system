param (
    [string]$Message = ""
)

[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "  ODPC10-LSS Git Auto-Push to GitHub" -ForegroundColor Cyan
Write-Host "  Repository: https://github.com/chanachai-tar/law-system" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan

if ([string]::IsNullOrWhiteSpace($Message)) {
    $currentTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $inputMsg = Read-Host "ระบุข้อความอธิบายการแก้ไข (กด Enter เพื่อใช้ค่าเริ่มต้น)"
    if ([string]::IsNullOrWhiteSpace($inputMsg)) {
        $Message = "Update law-system: $currentTime"
    } else {
        $Message = $inputMsg
    }
}

Write-Host "`n[1/3] กำลังเตรียมไฟล์ (git add .)..." -ForegroundColor Yellow
git add .

Write-Host "[2/3] กำลังบันทึกการแก้ไข (git commit)..." -ForegroundColor Yellow
git commit -m "$Message"

Write-Host "[3/3] กำลังส่งขึ้น GitHub (git push)..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n========================================================" -ForegroundColor Green
    Write-Host "  [SUCCESS] อัปโหลดโปรเจกต์ขึ้น GitHub สำเร็จเรียบร้อยแล้ว!" -ForegroundColor Green
    Write-Host "========================================================`n" -ForegroundColor Green

    Write-Host "กำลังส่งการแจ้งเตือนไปยังกลุ่ม Telegram..." -ForegroundColor Magenta
    php artisan app:notify-update "$Message"
} else {
    Write-Host "`n========================================================" -ForegroundColor Red
    Write-Host "  [ERROR] เกิดข้อผิดพลาดในการอัปโหลด" -ForegroundColor Red
    Write-Host "========================================================`n" -ForegroundColor Red
}
