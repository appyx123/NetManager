#!/bin/bash
set -e

echo "=== Initializing NetManager in Codespaces ==="

# 1. Environment file
if [ ! -f .env ]; then
  cp .env.example .env
  echo "Created .env from .env.example"
fi

# 2. PHP dependencies
composer install --no-interaction --prefer-dist

# 3. Application Key
php artisan key:generate --force

# 4. Frontend dependencies & asset compilation
npm install
npm run build

# 5. WhatsApp Gateway dependencies
if [ -d whatsapp-service ]; then
  echo "Installing WhatsApp service dependencies..."
  (cd whatsapp-service && npm install)
fi

# 6. Storage symlink
php artisan storage:link || true

echo "=== Codespaces environment is ready! ==="
echo "To run the application:"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
echo "To run WhatsApp Gateway:"
echo "  (cd whatsapp-service && node server.js)"
echo "Or run with full Docker Compose stack:"
echo "  docker compose up -d"
