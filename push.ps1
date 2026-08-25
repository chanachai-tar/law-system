param (
    [string]$Message = ""
)

[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "  ODPC10-LSS Git Auto-Push to GitHub" -ForegroundColor Cyan
Write-Host "  Repository: https://github.com/chanachai-tar/law-system" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan

if (-not $Message) {
    $currentTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $inputMsg = Read-Host "Commit Message (Press Enter for auto-date)"
    if (-not $inputMsg) {
        $Message = "Update law-system: $currentTime"
    } else {
        $Message = $inputMsg
    }
}

Write-Host "`n[1/3] Git Add..." -ForegroundColor Yellow
git add .

Write-Host "[2/3] Git Commit ($Message)..." -ForegroundColor Yellow
git commit -m "$Message"

Write-Host "[3/3] Git Push to GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n========================================================" -ForegroundColor Green
    Write-Host "  [SUCCESS] Uploaded to GitHub successfully!" -ForegroundColor Green
    Write-Host "========================================================`n" -ForegroundColor Green

    Write-Host "Sending Telegram update notification..." -ForegroundColor Magenta
    php artisan app:notify-update "$Message"
} else {
    Write-Host "`n========================================================" -ForegroundColor Red
    Write-Host "  [ERROR] Git push failed." -ForegroundColor Red
    Write-Host "========================================================`n" -ForegroundColor Red
}
