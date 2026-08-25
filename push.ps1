param (
    [string]$Message = "",
    [string]$Version = ""
)

[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "  ODPC10-LSS Git Auto-Push to GitHub" -ForegroundColor Cyan
Write-Host "  Repository: https://github.com/chanachai-tar/law-system" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan

# 1. ตรวจสอบการเปลี่ยนแปลงไฟล์ในระบบ
$status = git status --porcelain
if (-not $status) {
    Write-Host "`n[INFO] No changes detected. All files are up to date with GitHub." -ForegroundColor Green
    exit 0
}

# 2. คำนวณและอัปเดต Version อัตโนมัติ (เช่น v1.0.1 -> v1.0.2 -> v1.0.3...)
$versionFile = "$PSScriptRoot\version.json"
$currentVer = "1.0.1"

if (Test-Path $versionFile) {
    try {
        $json = Get-Content $versionFile -Raw | ConvertFrom-Json
        $major = [int]$json.major
        $minor = [int]$json.minor
        $patch = [int]$json.patch + 1
        $currentVer = "$major.$minor.$patch"
        $newJson = @{
            major = $major
            minor = $minor
            patch = $patch
            version = $currentVer
        } | ConvertTo-Json
        [System.IO.File]::WriteAllText($versionFile, $newJson, [System.Text.Encoding]::UTF8)
    } catch {
        $commitCount = git rev-list --count HEAD 2>$null
        if ($commitCount) {
            $currentVer = "1.0.$commitCount"
        }
    }
} else {
    $commitCount = git rev-list --count HEAD 2>$null
    $patch = if ($commitCount) { [int]$commitCount } else { 1 }
    $currentVer = "1.0.$patch"
    $newJson = @{
        major = 1
        minor = 0
        patch = $patch
        version = $currentVer
    } | ConvertTo-Json
    [System.IO.File]::WriteAllText($versionFile, $newJson, [System.Text.Encoding]::UTF8)
}

if ($Version) {
    $currentVer = $Version.TrimStart("v")
}

# 3. วิเคราะห์รายการไฟล์ที่ถูกแก้ไขอัตโนมัติ (ไม่ต้องพิมพ์เพิ่ม)
if (-not $Message) {
    $currentTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    
    $changes = @()
    foreach ($line in ($status -split "`n")) {
        $trimmed = $line.Trim()
        if ($trimmed.Length -ge 3) {
            $file = $trimmed.Substring(2).Trim()
            $fileName = [System.IO.Path]::GetFileName($file)
            if ($fileName) {
                $changes += $fileName
            }
        }
    }

    $changeCount = $changes.Count
    $sampleFiles = ($changes | Select-Object -Unique -First 5) -join ", "
    if ($changes.Count -gt 5) {
        $sampleFiles += "..."
    }

    $Message = "Auto-update ($currentTime): $changeCount files ($sampleFiles)"
}

Write-Host "`n[Auto-Version] v$currentVer" -ForegroundColor Green
Write-Host "[Auto-Detected] $Message" -ForegroundColor Cyan

Write-Host "`n[1/3] Git Add..." -ForegroundColor Yellow
git add .

Write-Host "[2/3] Git Commit..." -ForegroundColor Yellow
git commit -m "[$currentVer] $Message"

Write-Host "[3/3] Git Push to GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n========================================================" -ForegroundColor Green
    Write-Host "  [SUCCESS] Uploaded v$currentVer to GitHub successfully!" -ForegroundColor Green
    Write-Host "========================================================`n" -ForegroundColor Green

    Write-Host "Sending Telegram update notification..." -ForegroundColor Magenta
    php artisan app:notify-update "$Message" --ver="$currentVer"
} else {
    Write-Host "`n========================================================" -ForegroundColor Red
    Write-Host "  [ERROR] Git push failed." -ForegroundColor Red
    Write-Host "========================================================`n" -ForegroundColor Red
}
