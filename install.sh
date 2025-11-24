#!/bin/bash
# Script de instalación para VeoVeo

echo "╔════════════════════════════════════════╗"
echo "║   VeoVeo - Script de Instalación      ║"
echo "╚════════════════════════════════════════╝"
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Paso 1: Verificar PHP
echo -e "${YELLOW}[1/5]${NC} Verificando PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | awk '{print $2}')
    echo -e "${GREEN}✓${NC} PHP $PHP_VERSION encontrado"
else
    echo -e "${RED}✗${NC} PHP no está instalado"
    echo "  Descarga desde: https://www.php.net/downloads"
    exit 1
fi

# Paso 2: Verificar Composer (para PHPMailer)
echo ""
echo -e "${YELLOW}[2/5]${NC} Verificando Composer..."
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓${NC} Composer está instalado"
    echo -e "  ${YELLOW}Instalando dependencias...${NC}"
    composer install
else
    echo -e "${RED}✗${NC} Composer no está instalado"
    echo "  Descarga desde: https://getcomposer.org/download/"
    echo ""
    read -p "¿Continuar sin PHPMailer? (s/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Ss]$ ]]; then
        exit 1
    fi
fi

# Paso 3: Crear carpetas necesarias
echo ""
echo -e "${YELLOW}[3/5]${NC} Creando estructura de carpetas..."
mkdir -p compras
mkdir -p logs
mkdir -p uploads
chmod 755 compras logs uploads
echo -e "${GREEN}✓${NC} Carpetas creadas"

# Paso 4: Configurar archivo .env
echo ""
echo -e "${YELLOW}[4/5]${NC} Configurando archivo .env..."
if [ -f .env ]; then
    echo -e "${YELLOW}⚠${NC} Archivo .env ya existe"
else
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}✓${NC} Archivo .env creado"
        echo -e "${YELLOW}⚠${NC} Por favor, edita .env con tus credenciales"
    else
        echo -e "${RED}✗${NC} Archivo .env.example no encontrado"
    fi
fi

# Paso 5: Resumen final
echo ""
echo -e "${YELLOW}[5/5]${NC} Verificación final..."
echo ""
echo -e "${GREEN}✓${NC} Instalación completada"
echo ""
echo "╔════════════════════════════════════════╗"
echo "║   Próximos Pasos                       ║"
echo "╚════════════════════════════════════════╝"
echo ""
echo "1. Edita el archivo ${YELLOW}.env${NC}"
echo "   Configura tus credenciales de email"
echo ""
echo "2. Lee ${YELLOW}CONFIGURACION_EMAILS.md${NC}"
echo "   Para instrucciones detalladas"
echo ""
echo "3. Inicia un servidor local:"
echo "   ${YELLOW}php -S localhost:8000${NC}"
echo ""
echo "4. Abre en el navegador:"
echo "   ${YELLOW}http://localhost:8000${NC}"
echo ""
echo "5. Prueba agregar productos al carrito"
echo ""
echo "6. Para enviar emails de prueba:"
echo "   ${YELLOW}php test_email.php${NC}"
echo ""
echo -e "${GREEN}¡Listo para empezar!${NC}"
