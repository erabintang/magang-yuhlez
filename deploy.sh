#!/bin/bash

# ============================================
# YUHLEZ Deployment Script for VPS
# Server: dev.keloladata.cloud
# ============================================

set -e

echo "🚀 Starting YUHLEZ deployment..."

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Step 1: Pull latest code
echo -e "${YELLOW}Step 1: Pulling latest code...${NC}"
git pull origin main

# Step 2: Build Docker images
echo -e "${YELLOW}Step 2: Building Docker images...${NC}"
docker compose build --no-cache

# Step 3: Stop existing containers
echo -e "${YELLOW}Step 3: Stopping existing containers...${NC}"
docker compose down

# Step 4: Start containers
echo -e "${YELLOW}Step 4: Starting containers...${NC}"
docker compose up -d

# Step 5: Wait for MySQL to be ready
echo -e "${YELLOW}Step 5: Waiting for MySQL to be ready...${NC}"
sleep 15

# Step 6: Run migrations
echo -e "${YELLOW}Step 6: Running migrations...${NC}"
docker compose exec app php artisan migrate --force

# Step 7: Run seeders (only if fresh)
echo -e "${YELLOW}Step 7: Running seeders...${NC}"
docker compose exec app php artisan db:seed --force

# Step 8: Clear and cache configs
echo -e "${YELLOW}Step 8: Optimizing application...${NC}"
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Step 9: Create storage link
echo -e "${YELLOW}Step 9: Creating storage link...${NC}"
docker compose exec app php artisan storage:link

# Step 10: Set permissions
echo -e "${YELLOW}Step 10: Setting permissions...${NC}"
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

# Step 11: Restart queue workers
echo -e "${YELLOW}Step 11: Restarting queue workers...${NC}"
docker compose exec app php artisan queue:restart

# Step 12: Verify deployment
echo -e "${YELLOW}Step 12: Verifying deployment...${NC}"
docker compose ps

echo ""
echo -e "${GREEN}✅ YUHLEZ deployed successfully!${NC}"
echo ""
echo -e "${GREEN}🌐 Application: https://dev.keloladata.cloud${NC}"
echo -e "${GREEN}📊 Database: MySQL/MariaDB${NC}"
echo -e "${GREEN}📁 Storage: Laravel local storage${NC}"
echo ""
echo -e "${YELLOW}📝 Default ROOT account:${NC}"
echo -e "${YELLOW}   Email: admin@yuhlez.com${NC}"
echo -e "${YELLOW}   Password: 12345678${NC}"
echo ""
echo -e "${YELLOW}🔧 Useful commands:${NC}"
echo -e "${YELLOW}   docker compose logs -f          # View logs${NC}"
echo -e "${YELLOW}   docker compose exec app bash    # Enter container${NC}"
echo -e "${YELLOW}   docker compose restart           # Restart all${NC}"
