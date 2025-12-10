Write-Host "🔍 Running configuration checks..."


$hardcoded = Select-String -Path .\* -Pattern "password|admin|123456" -SimpleMatch -Recurse
if ($hardcoded) {
    Write-Host "❌ Hardcoded secret found. Deployment aborted."
    exit 1
}


$config = Get-Content .\config.php
if (-not ($config -match "getenv")) {
    Write-Host "❌ Environment variables not used. Deployment aborted."
    exit 1
}

Write-Host "✅ Configuration checks passed."

# -----------------------------
# 3) اختبار صحة الكود PHP (linter)
# -----------------------------
php -l .\index.php
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ PHP syntax error. Deployment aborted."
    exit 1
}

Write-Host "✅ All tests passed. Starting local deployment..."


Start-Process -NoNewWindow -FilePath php -ArgumentList "-S 0.0.0.0:8080 -t ."
Start-Sleep -Seconds 5


try {
    $response = Invoke-WebRequest -Uri http://localhost:8080 -UseBasicParsing
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Application is running at http://localhost:8080"
    } else {
        Write-Host "❌ Deployment simulation failed."
        exit 1
    }
} catch {
    Write-Host "❌ Deployment simulation failed."
    exit 1
}
