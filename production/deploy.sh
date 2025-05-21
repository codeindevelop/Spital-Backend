#!/bin/bash

# مطمئن شو که توی کانتینر app-backend اجرا بشه
docker compose -f docker-compose.prod.yml exec -T app-backend php artisan migrate --force
docker compose -f docker-compose.prod.yml exec -T app-backend php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app-backend php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app-backend php artisan view:cache

echo "Post-deployment tasks completed!"
