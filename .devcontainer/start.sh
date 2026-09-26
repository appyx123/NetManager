#!/bin/bash

# 1. Ensure MySQL and WhatsApp containers are up
if command -v docker &> /dev/null; then
  docker compose up -d mysql whatsapp 2>/dev/null || true

  # Connect devcontainer to compose network if needed
  DEVCONTAINER_ID=$(hostname)
  docker network connect netmanager_network "$DEVCONTAINER_ID" 2>/dev/null || true
fi

# 2. Ensure web server is listening on port 8000
if ! ss -tuln 2>/dev/null | grep -q ':8000 ' && ! lsof -i :8000 2>/dev/null | grep -q LISTEN; then
  echo "Starting NetManager Web Server on port 8000..."
  mkdir -p storage/logs
  nohup php artisan serve --host=0.0.0.0 --port=8000 > storage/logs/serve.log 2>&1 &
  sleep 2
fi

echo "=========================================================="
echo "   NetManager is LIVE & READY TO USE!                     "
echo "   URL: http://localhost:8000                             "
echo "=========================================================="
