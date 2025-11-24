@echo off
REM Script de instalación para VeoVeo en Windows

echo.
echo ╔════════════════════════════════════════╗
echo ║   VeoVeo - Instalación Windows        ║
echo ╚════════════════════════════════════════╝
echo.

REM Paso 1: Verificar PHP
echo [1/5] Verificando PHP...
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: PHP no está instalado
    echo Descarga desde: https://www.php.net/downloads
    pause
    exit /b 1
)

for /f "tokens=*" %%i in ('php -v ^| find /v "" ^| findstr /r "^PHP"') do (
    echo [OK] %%i
)

REM Paso 2: Crear carpetas necesarias
echo.
echo [2/5] Creando estructura de carpetas...
if not exist "compras" mkdir compras
if not exist "logs" mkdir logs
if not exist "uploads" mkdir uploads
echo [OK] Carpetas creadas

REM Paso 3: Configurar .env
echo.
echo [3/5] Configurando archivo .env...
if exist ".env" (
    echo [AVISO] Archivo .env ya existe
) else (
    if exist ".env.example" (
        copy .env.example .env
        echo [OK] Archivo .env creado
        echo [AVISO] Por favor, edita .env con tus credenciales
    ) else (
        echo [ERROR] Archivo .env.example no encontrado
    )
)

REM Paso 4: Verificar Composer
echo.
echo [4/5] Verificando Composer...
where composer >nul 2>nul
if %errorlevel% equ 0 (
    echo [OK] Composer está instalado
    echo     Instalando dependencias...
    call composer install
) else (
    echo [AVISO] Composer no está instalado
    echo     Descarga desde: https://getcomposer.org/download/
    echo     Continuando sin PHPMailer...
)

REM Paso 5: Mensaje final
echo.
echo ╔════════════════════════════════════════╗
echo ║   Instalación Completada              ║
echo ╚════════════════════════════════════════╝
echo.
echo Próximos pasos:
echo.
echo 1. Edita el archivo .env
echo    Configura tus credenciales de email
echo.
echo 2. Lee CONFIGURACION_EMAILS.md
echo    Para instrucciones detalladas
echo.
echo 3. Inicia un servidor local:
echo    php -S localhost:8000
echo.
echo 4. Abre en el navegador:
echo    http://localhost:8000
echo.
echo 5. Prueba agregar productos al carrito
echo.
echo ¡Listo para empezar!
echo.
pause
