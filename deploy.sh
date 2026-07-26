#!/bin/bash
echo "==================================================="
echo "  Deploying juniorprojectnak to thonlay.store..."
echo "==================================================="
echo ""

echo "[1/3] Pushing changes to GitHub..."
git add .
git commit -m "auto-deploy commit"
git push origin master

echo ""
echo "[2/3] Connecting to server and updating app..."
ssh -o StrictHostKeyChecking=no root@68.183.183.174 "cd /var/www/juniorprojectnak && git pull origin master && export COMPOSER_ALLOW_SUPERUSER=1 && composer install --optimize-autoloader --no-dev && npm run build && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && systemctl restart php8.3-fpm && systemctl restart nginx"

echo ""
echo "==================================================="
echo "  SUCCESS! Deployment complete at https://thonlay.store"
echo "==================================================="
