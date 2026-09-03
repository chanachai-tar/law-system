[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "========================================================" -ForegroundColor Cyan
Write-Host "  Export Database for Docker Initialization" -ForegroundColor Cyan
Write-Host "========================================================" -ForegroundColor Cyan

if (Test-Path ".env") {
    Get-Content ".env" | Where-Object { $_ -match '^(?!#)([^=]+)=(.*)$' } | ForEach-Object {
        [Environment]::SetEnvironmentVariable($matches[1].Trim(), $matches[2].Trim())
    }
}

$dbName = if ($env:DB_DATABASE) { $env:DB_DATABASE } else { "law_system_db" }
$dbUser = if ($env:DB_USERNAME) { $env:DB_USERNAME } else { "root" }
$dbPass = if ($env:DB_PASSWORD) { $env:DB_PASSWORD } else { "" }

$dumpFile = "$PSScriptRoot\database\docker_init_dump.sql"

try {
    # Backup including data
    if ($dbPass -eq "") {
        cmd.exe /c "mysqldump -u$dbUser $dbName > ""$dumpFile"""
    } else {
        cmd.exe /c "mysqldump -u$dbUser -p$dbPass $dbName > ""$dumpFile"""
    }
    Write-Host "[SUCCESS] Database exported to database/docker_init_dump.sql" -ForegroundColor Green
    Write-Host "This file will be automatically imported when Docker MySQL container starts for the first time." -ForegroundColor Yellow
} catch {
    Write-Host "[ERROR] Failed to export database. Is MySQL running in Laragon?" -ForegroundColor Red
}
