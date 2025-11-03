# StudEats Database Deployment Checklist

## Pre-Deployment (Local Environment)

### Phase 1: Preparation (Day -7)
- [ ] Review current database schema and data
- [ ] Backup all local data
- [ ] Install PostgreSQL locally for testing
- [ ] Test PostgreSQL connection from Laravel
- [ ] Review all SQL queries for PostgreSQL compatibility

### Phase 2: Local Testing (Day -5)
- [ ] Run migration scripts locally
- [ ] Execute all automated tests
- [ ] Perform manual testing of critical features
- [ ] Load test with sample data
- [ ] Document any issues found

### Phase 3: Staging Preparation (Day -3)
- [ ] Set up PostgreSQL instance on Render (Free tier for testing)
- [ ] Configure staging environment variables
- [ ] Test connection to Render PostgreSQL
- [ ] Migrate data to staging database
- [ ] Run full test suite against staging

## Deployment Day

### Pre-Deployment Checks (Morning)
- [ ] Verify backups are current and accessible
- [ ] Confirm rollback plan is documented
- [ ] Check Render service status
- [ ] Notify team of deployment window
- [ ] Verify monitoring tools are active

### Deployment Execution (Scheduled Window)

#### Step 1: Database Setup (15 minutes)
- [ ] Create production PostgreSQL instance on Render
  ```bash
  # Via Render Dashboard: 
  # 1. Navigate to Databases
  # 2. Click "New PostgreSQL"
  # 3. Name: studeats-production-db
  # 4. Plan: Starter ($7/month) or Free (for testing)
  # 5. Region: Oregon (or closest to users)
  # 6. PostgreSQL Version: 16
  ```
- [ ] Note down connection details
- [ ] Test connection from local machine
- [ ] Configure connection pooling if available

#### Step 2: Environment Configuration (10 minutes)
- [ ] Update `.env` on Render Dashboard:
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=[render-postgres-host]
  DB_PORT=5432
  DB_DATABASE=studeats
  DB_USERNAME=[render-username]
  DB_PASSWORD=[render-password]
  DATABASE_URL=[full-postgres-url]
  ```
- [ ] Keep MySQL credentials as backup
- [ ] Verify all other environment variables
- [ ] Save configuration

#### Step 3: Schema Migration (20 minutes)
- [ ] Deploy application to Render (without data)
- [ ] SSH into Render instance or use console
- [ ] Run migrations:
  ```bash
  php artisan migrate:fresh --database=pgsql --force
  ```
- [ ] Verify schema creation:
  ```bash
  php artisan tinker
  >>> Schema::connection('pgsql')->getTables();
  ```
- [ ] Check for migration errors

#### Step 4: Data Migration (1-2 hours)
- [ ] Export data from local MySQL:
  ```bash
  php artisan db:migrate-to-postgresql export
  ```
- [ ] Upload export files to Render (via SCP or Render disk)
- [ ] Import data to PostgreSQL:
  ```bash
  php artisan db:migrate-to-postgresql import --validate
  ```
- [ ] Monitor import progress
- [ ] Check for import errors

#### Step 5: Validation (30 minutes)
- [ ] Run validation command:
  ```bash
  php artisan db:migrate-to-postgresql validate
  ```
- [ ] Verify row counts match
- [ ] Check foreign key integrity
- [ ] Test critical queries
- [ ] Verify JSON data integrity

#### Step 6: Application Testing (30 minutes)
- [ ] Clear all caches:
  ```bash
  php artisan optimize:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- [ ] Test user authentication
- [ ] Test meal plan creation
- [ ] Test recipe browsing
- [ ] Test admin functions
- [ ] Verify email sending

#### Step 7: Performance Validation (15 minutes)
- [ ] Check response times (should be < 200ms p95)
- [ ] Monitor database connection pool
- [ ] Check query execution times
- [ ] Verify cache is working
- [ ] Test under concurrent load

#### Step 8: Go Live (10 minutes)
- [ ] Update primary database connection to PostgreSQL
- [ ] Restart all services:
  ```bash
  php artisan queue:restart
  ```
- [ ] Monitor logs in real-time:
  ```bash
  php artisan pail
  ```
- [ ] Monitor Render dashboard for errors
- [ ] Test production URL

### Post-Deployment Monitoring (First 2 Hours)

#### Immediate Checks (Every 5 minutes)
- [ ] Monitor error rates
- [ ] Check response times
- [ ] Verify queue processing
- [ ] Watch database connections
- [ ] Check memory usage

#### Critical Feature Testing
- [ ] User registration and login
- [ ] Email verification
- [ ] Meal plan generation
- [ ] Recipe browsing
- [ ] Admin dashboard access
- [ ] BMI calculations
- [ ] Price updates from Bantay Presyo

### Post-Deployment (Next 24 Hours)

#### Monitoring
- [ ] Set up automated health checks
- [ ] Configure alerts for errors
- [ ] Monitor database performance
- [ ] Track slow queries
- [ ] Check disk usage

#### Documentation
- [ ] Document any issues encountered
- [ ] Update deployment guide
- [ ] Note performance improvements/degradations
- [ ] Record lessons learned

#### Cleanup
- [ ] Archive migration export files
- [ ] Clean up temporary files
- [ ] Remove old backup files (keep recent ones)
- [ ] Update team documentation

## Rollback Procedure (If Needed)

### Immediate Rollback (< 5 minutes)
1. [ ] Update environment variables on Render:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql-host
   DB_PORT=3306
   ```
2. [ ] Clear config cache:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```
3. [ ] Restart services:
   ```bash
   php artisan queue:restart
   ```
4. [ ] Verify application is working
5. [ ] Notify team of rollback

### Post-Rollback
- [ ] Analyze what went wrong
- [ ] Fix identified issues
- [ ] Update deployment plan
- [ ] Schedule new deployment

## Success Criteria

### Must Have (Before Go-Live)
✅ All data migrated successfully (0% loss)  
✅ All tests passing (100% success rate)  
✅ Response time < 200ms (p95)  
✅ Zero critical errors  
✅ All features functional  

### Should Have (Within 24 hours)
✅ Response time < 150ms (p95)  
✅ Database connections stable  
✅ Monitoring active and alerting  
✅ Team trained on new system  
✅ Documentation updated  

### Nice to Have (Within 1 week)
✅ Query optimization completed  
✅ Performance improvements documented  
✅ Automated backup verification  
✅ Cost optimization review  

## Emergency Contacts

| Role | Name | Contact | Availability |
|------|------|---------|--------------|
| Database Admin | [Name] | [Email/Phone] | 24/7 during deployment |
| DevOps Lead | [Name] | [Email/Phone] | 24/7 during deployment |
| Backend Lead | [Name] | [Email/Phone] | 24/7 during deployment |
| Render Support | Support Team | support@render.com | 24/7 (paid plans) |

## Notes Section

### Deployment Date: _______________
### Deployment Lead: _______________
### Start Time: _______________
### End Time: _______________

### Issues Encountered:
1. 
2. 
3. 

### Resolutions:
1. 
2. 
3. 

### Performance Metrics:
- Pre-deployment avg response time: _______
- Post-deployment avg response time: _______
- Database query time improvement: _______
- User-facing improvements: _______

### Sign-off:
- [ ] Database Admin: _______________
- [ ] DevOps Lead: _______________
- [ ] Product Owner: _______________
