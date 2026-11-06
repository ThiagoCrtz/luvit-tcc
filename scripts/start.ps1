$ErrorActionPreference = 'Stop'
Set-Location -LiteralPath (Split-Path -Parent $PSScriptRoot)
$localPhp = Join-Path (Get-Location) '.tools\php\php.exe'
$phpCommand = if (Test-Path -LiteralPath $localPhp) { $localPhp } else { 'php' }
if (!(Test-Path -LiteralPath 'vendor\autoload.php')) {
    throw 'Dependencias ausentes. Siga as instrucoes do README antes de iniciar.'
}
if (!(Test-Path -LiteralPath '.env')) {
    Copy-Item -LiteralPath '.env.example' -Destination '.env'
}
foreach ($folder in @('storage\framework\sessions', 'storage\framework\views', 'storage\framework\cache\data', 'storage\logs', 'storage\app\public', 'bootstrap\cache')) {
    New-Item -ItemType Directory -Path $folder -Force | Out-Null
}
$environmentText = Get-Content -LiteralPath '.env' -Raw
if ($environmentText -match '(?m)^APP_KEY=\s*$') {
    & $phpCommand artisan key:generate --no-interaction
    if ($LASTEXITCODE -ne 0) { throw 'Nao foi possivel gerar a chave da aplicacao.' }
}
if (!(Test-Path -LiteralPath 'public\build\manifest.json')) {
    & npm.cmd run build
    if ($LASTEXITCODE -ne 0) { throw 'Nao foi possivel compilar a interface.' }
}
& $phpCommand artisan migrate --force --no-interaction
if ($LASTEXITCODE -ne 0) { throw 'Nao foi possivel preparar o banco de dados.' }
if (!(Test-Path -LiteralPath 'public\storage')) {
    if ($IsLinux -or $IsMacOS) {
        & $phpCommand artisan storage:link
    } else {
        New-Item -ItemType Junction -Path 'public\storage' -Target (Resolve-Path 'storage\app\public') | Out-Null
    }
}
Write-Host ''
Write-Host 'Luvit: http://127.0.0.1:8000' -ForegroundColor Green
Write-Host 'Abra esse endereco no navegador. Para encerrar, pressione Ctrl+C.'
& $phpCommand artisan serve --host=127.0.0.1 --port=8000 --no-reload
