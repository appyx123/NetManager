#!/bin/bash
set -e

echo "=========================================================="
echo "   NetManager — 1-Click Codespaces & Docker Environment   "
echo "=========================================================="

# 1. Environment file setup
if [ ! -f .env ]; then
  cp .env.example .env
  echo "[1/8] Created .env from .env.example"
else
  echo "[1/8] .env file already exists"
fi

# 2. PHP dependencies
echo "[2/8] Installing Composer PHP dependencies..."
composer install --no-interaction --prefer-dist

# 3. Application Key
echo "[3/8] Generating application encryption key..."
php artisan key:generate --force

# 4. Frontend assets build
echo "[4/8] Installing npm packages and compiling assets with Vite..."
npm install
npm run build

# 5. WhatsApp Gateway dependencies
echo "[5/8] Installing WhatsApp Microservice dependencies..."
if [ -d whatsapp-service ]; then
  (cd whatsapp-service && npm install)
fi

# 6. Storage symlink & permissions
echo "[6/8] Setting up storage symlink & directories..."
php artisan storage:link || true
mkdir -p storage/app/public/uploads/teknisi/bukti \
         storage/app/public/uploads/teknisi/lokasi \
         storage/app/public/uploads/teknisi/speedtest \
         storage/app/public/uploads/customer-complaints \
         storage/app/private/documents/ktp \
         storage/logs

# 7. Start Docker background services & Run migrations
echo "[7/8] Starting MySQL & WhatsApp services..."
if command -v docker &> /dev/null; then
  docker compose up -d mysql whatsapp || true

  echo "Waiting for MySQL container to become healthy..."
  for i in {1..40}; do
    if docker exec netmanager-mysql mysqladmin ping -h 127.0.0.1 -u root -prootsecret --silent &> /dev/null; then
      echo "MySQL is healthy and ready!"
      break
    fi
    sleep 1
  done

  # Connect devcontainer to named network 'netmanager_network'
  DEVCONTAINER_ID=$(hostname)
  docker network connect netmanager_network "$DEVCONTAINER_ID" 2>/dev/null || true

  # Check if 'mysql' hostname resolves; if not, configure 127.0.0.1 fallback in .env
  if ! ping -c 1 -W 1 mysql &> /dev/null && ! nc -z -w 1 mysql 3306 2>/dev/null; then
    echo "Configuring DB_HOST=127.0.0.1 and WA_API_URL=http://127.0.0.1:3000..."
    sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
    sed -i 's|^WA_API_URL=.*|WA_API_URL=http://127.0.0.1:3000|' .env
  fi

  echo "Running database migrations and seeders..."
  php artisan migrate:fresh --seed --force || {
    echo "Retrying migration with DB_HOST=127.0.0.1 fallback..."
    DB_HOST=127.0.0.1 php artisan migrate:fresh --seed --force
  }
fi

# 8. Start development server in background so app is 100% ready immediately
echo "[8/8] Starting NetManager Web Server on port 8000..."
nohup php artisan serve --host=0.0.0.0 --port=8000 > storage/logs/serve.log 2>&1 &
sleep 2

echo "=========================================================="
echo "   NetManager is 100% READY & LIVE!                       "
echo "   URL: http://localhost:8000                             "
echo "=========================================================="
echo "Default Accounts (Password: password):"
echo "  - Super Admin : owner@netmanager.local / superadmin@netmanager.local"
echo "  - Admin       : admin@netmanager.local"
echo "  - Marketing   : marketing@netmanager.local"
echo "  - Technician  : teknisi@netmanager.local / technician@netmanager.local"
echo "  - Customer    : budi@netmanager.local"
echo "=========================================================="
