<<<<<<< HEAD
$log = "config-results.txt"
"" | Out-File $log

Write-Output "Running configuration checks..." | Tee-Object -FilePath $log -Append

$files = Get-ChildItem -Path . -Recurse -File | Where-Object { $_.Name -ne "deploy.ps1" }
$hardcodedFound = $false

foreach ($file in $files) {
    $matches = Select-String -Path $file.FullName -Pattern "password|admin|123456" -SimpleMatch
    if ($matches) {
        $hardcodedFound = $true
        Write-Output "Found hardcoded secret in: $($file.FullName)" | Tee-Object -FilePath $log -Append
        foreach ($match in $matches) {
            Write-Output "   Line $($match.LineNumber): $($match.Line.Trim())" | Tee-Object -FilePath $log -Append
        }
    }
}

if ($hardcodedFound) {
    Write-Output "Hardcoded secrets detected. Deployment aborted." | Tee-Object -FilePath $log -Append
    exit 1
} else {
    Write-Output "No hardcoded secrets found." | Tee-Object -FilePath $log -Append
}

$configFile = "config.php"

if (-not (Test-Path $configFile)) {
    Write-Output "config.php not found." | Tee-Object -FilePath $log -Append
} else {
    $content = Get-Content $configFile -Raw
    if ($content -notmatch "getenv|_ENV|_SERVER") {
        Write-Output "Environment variables not used in config.php" | Tee-Object -FilePath $log -Append
        exit 1
    } else {
        Write-Output "Environment variables detected." | Tee-Object -FilePath $log -Append
    }
}

php -l .\index.php | Tee-Object -FilePath $log -Append
if ($LASTEXITCODE -ne 0) {
    Write-Output "PHP syntax error. Deployment aborted." | Tee-Object -FilePath $log -Append
    exit 1
}

Write-Output "PHP syntax OK." | Tee-Object -FilePath $log -Append
Write-Output "All checks passed successfully." | Tee-Object -FilePath $log -Append
Write-Host "Done. Check config-results.txt"
=======
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
>>>>>>> c0d265b417f78de4d0b3ef7010d2726f5cc027f8
