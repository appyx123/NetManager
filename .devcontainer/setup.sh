#!/bin/bash
set -e

echo "=========================================================="
echo "   NetManager — 1-Click Codespaces & Docker Environment   "
echo "=========================================================="

# 1. Environment file setup
if [ ! -f .env ]; then
  cp .env.example .env
  echo "[1/7] Created .env from .env.example"
else
  echo "[1/7] .env file already exists"
fi

# 2. PHP dependencies
echo "[2/7] Installing Composer PHP dependencies..."
composer install --no-interaction --prefer-dist

# 3. Application Key
echo "[3/7] Generating application encryption key..."
php artisan key:generate --force

# 4. Frontend assets build
echo "[4/7] Installing npm packages and compiling assets with Vite..."
npm install
npm run build

# 5. WhatsApp Gateway dependencies
echo "[5/7] Installing WhatsApp Microservice dependencies..."
if [ -d whatsapp-service ]; then
  (cd whatsapp-service && npm install)
fi

# 6. Storage symlink & permissions
echo "[6/7] Setting up storage symlink & directories..."
php artisan storage:link || true
mkdir -p storage/app/public/uploads/teknisi/bukti \
         storage/app/public/uploads/teknisi/lokasi \
         storage/app/public/uploads/teknisi/speedtest \
         storage/app/public/uploads/customer-complaints \
         storage/app/private/documents/ktp \
         storage/logs

# 7. Start Docker background services & Run migrations
echo "[7/7] Starting MySQL & WhatsApp services..."
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

  # Connect devcontainer to compose network so hostnames 'mysql' and 'whatsapp' resolve
  DEVCONTAINER_ID=$(hostname)
  COMPOSE_NET=$(docker network ls --filter name=netmanager --format "{{.Name}}" | head -n 1)
  if [ -n "$COMPOSE_NET" ]; then
    docker network connect "$COMPOSE_NET" "$DEVCONTAINER_ID" 2>/dev/null || true
  fi

  echo "Running database migrations and seeders..."
  php artisan migrate:fresh --seed --force || {
    echo "Retrying migration with DB_HOST=127.0.0.1 fallback..."
    DB_HOST=127.0.0.1 php artisan migrate:fresh --seed --force
  }
fi

echo "=========================================================="
echo "   NetManager is 100% READY!                              "
echo "=========================================================="
echo "To start development server:"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo "To start WhatsApp Gateway:"
echo "  (cd whatsapp-service && node server.js)"
echo "Or full stack via Docker Compose:"
echo "  docker compose up -d"
echo "=========================================================="
