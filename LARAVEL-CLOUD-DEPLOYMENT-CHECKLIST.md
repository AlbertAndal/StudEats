# Laravel Cloud Deployment Checklist

## Pre-Deployment
- [ ] Code committed to Git repository (main branch)
- [ ] Database configuration updated (`config/database.php` default = mysql)
- [ ] Deployment script tested (`deploy-laravel-cloud.sh`)
- [ ] Environment variables template prepared (`.env.laravel-cloud`)

## Laravel Cloud Dashboard Setup

### 1. Database Configuration
- [ ] MySQL database provisioned in Laravel Cloud
- [ ] Copy database credentials from Laravel Cloud dashboard
- [ ] Test database connection locally if possible

### 2. Environment Variables
Navigate to: **Laravel Cloud Dashboard → Your App → Environment**

Copy and paste from `.env.laravel-cloud`, updating these values:

#### Critical Variables (MUST SET)
- [ ] `APP_KEY` - Generate new: `php artisan key:generate --show`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://studeats.laravel.cloud`

#### Database (from Laravel Cloud dashboard)
- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST=<from-dashboard>`
- [ ] `DB_PORT=3306`
- [ ] `DB_DATABASE=<from-dashboard>`
- [ ] `DB_USERNAME=<from-dashboard>`
- [ ] `DB_PASSWORD=<from-dashboard>`

#### Application Settings
- [ ] `SESSION_DRIVER=database`
- [ ] `CACHE_STORE=database`
- [ ] `QUEUE_CONNECTION=database`
- [ ] `LOG_CHANNEL=stack`
- [ ] `LOG_LEVEL=error`

#### Mail Configuration (Gmail SMTP)
- [ ] `MAIL_MAILER=smtp`
- [ ] `MAIL_HOST=smtp.gmail.com`
- [ ] `MAIL_PORT=587`
- [ ] `MAIL_USERNAME=johnalbertandal5@gmail.com`
- [ ] `MAIL_PASSWORD=nharujmmwoawzwgp`
- [ ] `MAIL_ENCRYPTION=tls`
- [ ] `MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com`
- [ ] `MAIL_FROM_NAME=StudEats`

#### API Configuration
- [ ] `NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1`
- [ ] `NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP`

#### Vite
- [ ] `VITE_APP_NAME=StudEats`

### 3. Deployment Configuration
- [ ] Build Command: `composer install --optimize-autoloader --no-dev && npm ci && npm run build`
- [ ] Deploy Command: `bash deploy-laravel-cloud.sh`
- [ ] PHP Version: 8.2 or higher
- [ ] Node Version: 18 or higher

### 4. Initial Deployment
- [ ] Push code to repository
- [ ] Trigger deployment from Laravel Cloud dashboard
- [ ] Monitor deployment logs for errors
- [ ] Wait for deployment to complete (usually 5-10 minutes)

## Post-Deployment Verification

### 1. Basic Functionality
- [ ] Visit: https://studeats.laravel.cloud/
- [ ] Homepage loads without errors
- [ ] No 500 errors
- [ ] Assets (CSS/JS) loading correctly

### 2. Health Check
- [ ] Visit: https://studeats.laravel.cloud/up
- [ ] Returns 200 OK status

### 3. Database Connection
SSH into Laravel Cloud or use Artisan Tinker:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::select('SELECT 1');
>>> User::count();
```
- [ ] Database connection successful
- [ ] Can query tables
- [ ] Migrations have run

### 4. Authentication Flow
- [ ] Registration page loads: `/register`
- [ ] Can create new account
- [ ] Email verification sends (check email)
- [ ] Can verify email with OTP
- [ ] Login page works: `/login`
- [ ] Can login with credentials
- [ ] Dashboard loads after login

### 5. Admin Panel
- [ ] Admin login: `/admin/login`
- [ ] Default credentials work:
  - Email: admin@studeats.com
  - Password: admin123
- [ ] Admin dashboard loads
- [ ] Can view users
- [ ] Can manage recipes/meals

### 6. Core Features
- [ ] User profile page loads
- [ ] BMI calculation works
- [ ] Meal planning interface accessible
- [ ] Recipe browsing works
- [ ] Search functionality works
- [ ] Bantay Presyo integration functional

## Troubleshooting

### If Deployment Fails
1. **Check Deployment Logs**
   - Go to Laravel Cloud Dashboard → Deployments
   - Review build and deploy logs
   - Look for error messages

2. **Common Issues**
   - [ ] Build command fails → Check composer.json dependencies
   - [ ] Migration fails → Check database connection
   - [ ] Assets missing → Verify `npm run build` completed
   - [ ] Permission errors → Check file permissions in deployment script

### If 500 Error Persists
1. **Enable Debug Mode Temporarily**
   ```env
   APP_DEBUG=true
   APP_ENV=local
   ```
   ⚠️ **DISABLE IMMEDIATELY** after identifying issue

2. **Check Application Logs**
   - Laravel Cloud Dashboard → Logs
   - Look for stack traces
   - Identify exact error

3. **Common 500 Errors**
   - [ ] APP_KEY missing → Set in environment variables
   - [ ] Database connection failed → Verify DB credentials
   - [ ] Sessions table missing → Run migrations
   - [ ] Storage not writable → Check deployment script
   - [ ] Config cached → Run `php artisan config:clear`

### Database Issues
```bash
# Check migrations
php artisan migrate:status

# Check tables exist
php artisan tinker
>>> DB::select('SHOW TABLES');

# Test specific tables
>>> DB::table('users')->count();
>>> DB::table('sessions')->count();
>>> DB::table('recipes')->count();
```

### Clear Cache Issues
```bash
# SSH into Laravel Cloud
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Then recache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Post-Go-Live Tasks

### Security
- [ ] Change admin password from default
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `APP_ENV=production`
- [ ] Review and rotate API keys if needed
- [ ] Enable HTTPS redirect if not automatic
- [ ] Set up monitoring/alerts

### Performance
- [ ] Enable opcache (usually automatic on Laravel Cloud)
- [ ] Verify route/config/view caching active
- [ ] Test page load times
- [ ] Monitor database query performance

### Monitoring
- [ ] Set up Laravel Cloud alerts
- [ ] Monitor application logs daily
- [ ] Check error rates
- [ ] Monitor database usage
- [ ] Track email delivery success

### Backup
- [ ] Verify automatic database backups enabled
- [ ] Test database restore process
- [ ] Document backup retention policy

## Known Issues & Resolutions

### Issue: Config/Route Not Found
**Symptom**: 404 on all routes  
**Solution**:
```bash
php artisan route:clear
php artisan config:clear
php artisan route:cache
php artisan config:cache
```

### Issue: Assets Not Loading
**Symptom**: Styles/scripts missing  
**Solution**:
1. Verify `npm run build` ran successfully
2. Check `public/build/` directory exists
3. Verify `APP_URL` matches deployment URL

### Issue: Email Not Sending
**Symptom**: OTP emails not received  
**Solution**:
1. Check queue is running: `php artisan queue:work`
2. Verify MAIL_* environment variables
3. Check Gmail app password is correct
4. Review mail logs in Laravel Cloud

### Issue: Session Expired Immediately
**Symptom**: Users logged out constantly  
**Solution**:
1. Verify `sessions` table exists
2. Check `SESSION_DRIVER=database` set
3. Verify database connection stable
4. Check `SESSION_DOMAIN` matches app domain

## Rollback Plan

If critical issues occur:

1. **Revert to Previous Deployment**
   - Laravel Cloud Dashboard → Deployments
   - Select previous successful deployment
   - Click "Redeploy"

2. **Emergency Database Rollback**
   - Restore from latest backup
   - May lose recent data

3. **Quick Fix Deployment**
   - Make minimal code changes
   - Test locally first
   - Push and redeploy

## Support Contacts

- **Laravel Cloud Support**: support@laravel.com
- **Laravel Cloud Docs**: https://cloud.laravel.com/docs
- **Application Repository**: https://github.com/AlbertAndal/StudEats
- **Project Lead**: johnalbertandal5@gmail.com

## Deployment Sign-Off

**Deployed By**: ___________________________  
**Date**: ___________________________  
**Deployment ID**: ___________________________  
**Status**: [ ] Success  [ ] Failed  [ ] Rollback Required  

**Notes**:
________________________________________________________________
________________________________________________________________
________________________________________________________________

---

**Last Updated**: November 8, 2025  
**Version**: 1.0  
**Next Review**: Before each deployment
