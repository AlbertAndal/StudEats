# StudEats Docker Setup

This directory contains Docker configuration files for the StudEats Laravel application.

## Quick Start

### Development Environment

1. **Build and start all services:**
   ```bash
   docker-compose up -d --build
   ```

2. **Access the application:**
   - Web application: http://localhost:8000
   - Database: localhost:3306
   - Redis: localhost:6379

3. **Initial setup (first time only):**
   ```bash
   # Copy environment file
   docker-compose exec app cp .env.example .env
   
   # Generate application key
   docker-compose exec app php artisan key:generate
   
   # Run migrations
   docker-compose exec app php artisan migrate
   
   # Seed database (optional)
   docker-compose exec app php artisan db:seed
   ```

### Production Environment

1. **Create production environment file:**
   ```bash
   cp .env.example .env.production
   ```

2. **Edit `.env.production` with your production settings:**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure your database credentials
   - Set up mail configuration
   - Configure other production settings

3. **Build and deploy:**
   ```bash
   docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
   ```

## Services

### App Service
- **Container**: studeats-app
- **Port**: 8000:80
- **Description**: Main Laravel application with Apache web server

### Database Service
- **Container**: studeats-db
- **Port**: 3306:3306
- **Description**: MySQL 8.0 database server
- **Credentials**: 
  - Root password: `root_password`
  - Database: `studeats`
  - User: `studeats_user`
  - Password: `studeats_password`

### Redis Service
- **Container**: studeats-redis
- **Port**: 6379:6379
- **Description**: Redis server for caching and sessions

### Queue Worker Service
- **Container**: studeats-queue
- **Description**: Laravel queue worker for background jobs (email verification, etc.)

### Scheduler Service
- **Container**: studeats-scheduler
- **Description**: Laravel task scheduler for cron jobs

## Useful Commands

### Application Management
```bash
# View logs
docker-compose logs -f app

# Access application shell
docker-compose exec app bash

# Run Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Database Management
```bash
# Access MySQL shell
docker-compose exec db mysql -u studeats_user -p studeats

# Backup database
docker-compose exec db mysqldump -u studeats_user -p studeats > backup.sql

# Import database
docker-compose exec -i db mysql -u studeats_user -p studeats < backup.sql
```

### Development
```bash
# Install Composer dependencies
docker-compose exec app composer install

# Install NPM dependencies and build assets
docker-compose exec app npm install
docker-compose exec app npm run build

# Run tests
docker-compose exec app php artisan test
```

## File Structure

```
docker/
├── apache.conf       # Apache virtual host configuration
├── entrypoint.sh     # Container startup script
└── README.md         # This file

Dockerfile            # Main application container
docker-compose.yml    # Docker Compose configuration
.dockerignore        # Files to ignore when building Docker image
```

## Environment Variables

Key environment variables for Docker deployment:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=studeats
DB_USERNAME=studeats_user
DB_PASSWORD=studeats_password

# Queue
QUEUE_CONNECTION=database

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (configure as needed)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## Troubleshooting

### Common Issues

1. **Permission errors:**
   ```bash
   docker-compose exec app chown -R www-data:www-data /var/www/html/storage
   docker-compose exec app chmod -R 775 /var/www/html/storage
   ```

2. **Database connection errors:**
   - Ensure database service is running: `docker-compose ps`
   - Check database logs: `docker-compose logs db`

3. **Queue jobs not processing:**
   - Check queue worker logs: `docker-compose logs queue`
   - Restart queue worker: `docker-compose restart queue`

4. **Assets not loading:**
   - Build assets: `docker-compose exec app npm run build`
   - Create storage link: `docker-compose exec app php artisan storage:link`

### Performance Optimization

1. **Enable OPcache in production:**
   - Add OPcache configuration to Dockerfile

2. **Use Redis for sessions and cache:**
   ```env
   SESSION_DRIVER=redis
   CACHE_DRIVER=redis
   ```

3. **Optimize Composer autoloader:**
   ```bash
   docker-compose exec app composer install --optimize-autoloader --no-dev
   ```

## Security Considerations

1. **Change default passwords** in production
2. **Use environment-specific .env files**
3. **Enable HTTPS** with reverse proxy (nginx, Traefik)
4. **Regularly update base images**
5. **Scan images for vulnerabilities**

## Scaling

For horizontal scaling, consider:
- Using external database service (AWS RDS, Google Cloud SQL)
- External Redis service
- Load balancer in front of multiple app containers
- Separate queue workers on different nodes