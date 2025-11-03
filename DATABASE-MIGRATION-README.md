# Database Migration Quick Reference

## 🚀 Quick Commands

### Using Helper Script
```bash
# Test connection
php migrate.php test-connection

# Full migration
php migrate.php full

# Individual steps
php migrate.php export
php migrate.php import --validate
php migrate.php validate

# Testing
php migrate.php test
php migrate.php performance-test

# Health check
php migrate.php health

# Backup
php migrate.php backup
```

### Using Artisan Commands
```bash
# Full migration
php artisan db:migrate-to-postgresql full --validate

# Individual operations
php artisan db:migrate-to-postgresql export
php artisan db:migrate-to-postgresql import --validate
php artisan db:migrate-to-postgresql validate

# Backup
php artisan db:backup --connection=mysql --compress

# Test connection
php artisan db:ping
```

### Testing
```bash
# All database tests
php artisan test --filter=Database

# Specific tests
php artisan test --filter=DatabaseMigrationTest
php artisan test --filter=DatabasePerformanceTest
```

## 📚 Documentation

| Document | Description |
|----------|-------------|
| `database-migration-complete.md` | **START HERE** - Overview and quick start |
| `database-migration-guide.md` | Complete migration strategy |
| `database-deployment-checklist.md` | Step-by-step deployment |
| `database-performance-optimization.md` | Performance tuning |
| `database-error-handling.md` | Troubleshooting guide |
| `environment-configuration-guide.md` | Environment setup |

## 🎯 Migration Steps

1. **Local Setup** (1 hour)
   - Install PostgreSQL locally
   - Update `.env` with PostgreSQL connection
   - Run migrations: `php artisan migrate --database=pgsql`
   - Test locally: `php migrate.php test`

2. **Data Migration** (1 hour)
   - Export data: `php migrate.php export`
   - Import data: `php migrate.php import --validate`
   - Validate: `php migrate.php validate`

3. **Production Deploy** (1 hour)
   - Create Render PostgreSQL database
   - Update environment variables on Render
   - Deploy application
   - Monitor health: `php migrate.php health`

## 🔧 Environment Configuration

### Local (.env)
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=studeats_postgres
DB_USERNAME=postgres
DB_PASSWORD=password
```

### Production (Render Dashboard)
```env
DB_CONNECTION=pgsql
DATABASE_URL=[from Render Dashboard]
DB_SSLMODE=require
```

## 📊 Performance Expectations

| Metric | Target | Acceptable |
|--------|--------|------------|
| User login | < 50ms | < 100ms |
| Meal plan load | < 100ms | < 200ms |
| Recipe search | < 150ms | < 300ms |

## 🛟 Rollback

If issues occur:

```bash
# 1. Update .env
DB_CONNECTION=mysql

# 2. Clear and cache
php artisan config:clear
php artisan config:cache

# 3. Restart services
php artisan queue:restart
```

## 📞 Support

- Documentation: `docs/` directory
- Render Support: support@render.com
- Status: https://status.render.com

---

**For complete documentation, see `docs/database-migration-complete.md`**
