# Database Error Handling & Recovery Documentation

## Error Categories and Solutions

### 1. Connection Errors

#### Error: "SQLSTATE[08006] Connection failure"

**Cause**: Cannot connect to PostgreSQL database

**Solutions**:
```php
// Automatic retry with exponential backoff
DB::connection('pgsql')->reconnect();

// Laravel config (config/database.php)
'pgsql' => [
    'options' => [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_PERSISTENT => false,
    ],
    'sticky' => true,  // Stick to master for writes
],
```

**Prevention**:
- Use connection pooling (PgBouncer)
- Implement health checks
- Monitor connection limits
- Set appropriate timeouts

**Recovery Steps**:
1. Check Render dashboard for database status
2. Verify environment variables
3. Test connection: `php artisan tinker` then `DB::connection()->getPdo()`
4. Check firewall rules and IP whitelist
5. Restart application if needed

---

#### Error: "SQLSTATE[08P01] Too many connections"

**Cause**: Connection pool exhausted

**Immediate Fix**:
```sql
-- Kill idle connections
SELECT pg_terminate_backend(pid) 
FROM pg_stat_activity 
WHERE state = 'idle' 
AND state_changed < NOW() - INTERVAL '5 minutes';
```

**Long-term Solution**:
```ini
# PgBouncer configuration
[pgbouncer]
max_client_conn = 100
default_pool_size = 20
```

**Prevention**:
- Implement connection pooling
- Close connections properly
- Use Laravel's built-in connection management
- Monitor active connections

---

### 2. Query Errors

#### Error: "SQLSTATE[42P01] Relation does not exist"

**Cause**: Table or column not found

**Diagnosis**:
```bash
php artisan migrate:status
php artisan schema:dump
```

**Solution**:
```bash
# Run missing migrations
php artisan migrate --force

# If schema is corrupted, restore from backup
php artisan db:restore --backup=latest
```

**Prevention**:
- Always run migrations in staging first
- Use version control for schema changes
- Test migrations with rollback
- Keep migration files in sync

---

#### Error: "SQLSTATE[23503] Foreign key violation"

**Cause**: Trying to delete/update record with dependent records

**Example**:
```php
// ❌ Will fail if user has meal plans
User::find(1)->delete();

// ✅ Better approach
$user = User::find(1);
$user->mealPlans()->delete(); // Delete dependents first
$user->delete();

// ✅ Best: Use cascade delete (already configured in migrations)
// Foreign key with ON DELETE CASCADE handles this automatically
```

**Debug**:
```php
// Find dependent records
$user = User::find(1);
$dependencies = [
    'meal_plans' => $user->mealPlans()->count(),
    'activity_logs' => $user->activityLogs()->count(),
    'admin_logs' => $user->adminLogs()->count(),
];
```

---

#### Error: "SQLSTATE[23505] Unique violation"

**Cause**: Duplicate value in unique column

**Solution**:
```php
// Check before insert
$existingUser = User::where('email', $email)->first();
if ($existingUser) {
    throw new \Exception('Email already exists');
}

// Or use firstOrCreate
$user = User::firstOrCreate(
    ['email' => $email],
    ['name' => $name, 'password' => $password]
);

// Or use updateOrCreate
$user = User::updateOrCreate(
    ['email' => $email],
    ['name' => $name, 'password' => $password]
);
```

---

#### Error: "SQLSTATE[22P02] Invalid text representation"

**Cause**: Type mismatch (e.g., string in integer field)

**Solution**:
```php
// ❌ Bad
MealPlan::create(['user_id' => 'abc']); // String in integer field

// ✅ Good
MealPlan::create(['user_id' => (int)$userId]);

// ✅ Best: Use type casting in model
class MealPlan extends Model
{
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'meal_id' => 'integer',
            'servings' => 'integer',
            'is_completed' => 'boolean',
        ];
    }
}
```

---

### 3. Performance Errors

#### Error: "SQLSTATE[57014] Query canceled due to statement timeout"

**Cause**: Query took too long

**Immediate Fix**:
```sql
-- Increase timeout temporarily
SET statement_timeout = '60s';
```

**Long-term Solution**:
```php
// Optimize query
// ❌ Bad - Full table scan
$users = User::all()->filter(function($user) {
    return $user->is_active;
});

// ✅ Good - Database-level filtering
$users = User::where('is_active', true)->get();

// ✅ Better - Add index
// CREATE INDEX idx_users_active ON users(is_active);
```

**Prevention**:
- Add appropriate indexes
- Use EXPLAIN ANALYZE to understand queries
- Implement pagination
- Use eager loading to prevent N+1
- Cache expensive queries

---

#### Error: "SQLSTATE[53200] Out of memory"

**Cause**: Query consuming too much memory

**Solution**:
```php
// ❌ Bad - Loads all data into memory
$allMeals = Meal::all();

// ✅ Good - Chunk processing
Meal::chunk(100, function ($meals) {
    foreach ($meals as $meal) {
        // Process meal
    }
});

// ✅ Better - Cursor for large datasets
foreach (Meal::cursor() as $meal) {
    // Process meal with minimal memory
}
```

---

### 4. Migration Errors

#### Error: "Migration failed to execute"

**Diagnosis**:
```bash
# Check migration status
php artisan migrate:status

# View last error
tail -n 100 storage/logs/laravel.log
```

**Recovery**:
```bash
# Rollback last batch
php artisan migrate:rollback --step=1

# Reset and retry
php artisan migrate:refresh --force

# If all else fails, restore from backup
php artisan db:restore --backup=pre_migration
```

**Prevention**:
- Test migrations in local environment
- Use transactions in migrations
- Keep migrations idempotent
- Backup before running migrations

---

### 5. Data Integrity Errors

#### Error: "SQLSTATE[23514] Check constraint violation"

**Cause**: Data doesn't meet check constraint

**Example**:
```php
// ❌ Bad - Invalid JSON
DB::table('users')->insert([
    'dietary_preferences' => 'not valid json',
]);

// ✅ Good - Valid JSON
User::create([
    'dietary_preferences' => ['vegetarian', 'gluten-free'],
]);
```

**Debug**:
```sql
-- Check constraint violations
SELECT * FROM users 
WHERE dietary_preferences IS NOT NULL 
AND NOT (dietary_preferences::text ~ '^[\[\{]');
```

---

### 6. Transaction Errors

#### Error: "SQLSTATE[25P02] In failed SQL transaction block"

**Cause**: Previous query failed in transaction

**Solution**:
```php
try {
    DB::beginTransaction();
    
    // Your queries here
    $user = User::create($userData);
    $mealPlan = MealPlan::create($planData);
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    
    // Log error
    Log::error('Transaction failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
    
    throw $e;
}
```

**Best Practices**:
```php
// Use Laravel's transaction helper
DB::transaction(function () use ($userData, $planData) {
    $user = User::create($userData);
    $mealPlan = MealPlan::create($planData);
});

// With retry logic
DB::transaction(function () use ($userData) {
    User::create($userData);
}, 3); // Retry 3 times on deadlock
```

---

### 7. Backup & Recovery

#### Creating Backups

```bash
# Automatic backup before deployment
php artisan db:backup --connection=pgsql

# Compressed backup
php artisan db:backup --compress

# Backup specific tables
pg_dump -h host -U user -d dbname -t users -t meals > backup.sql
```

#### Restoring from Backup

```bash
# Restore from SQL file
psql -h host -U user -d dbname < backup.sql

# Restore specific table
psql -h host -U user -d dbname -c "TRUNCATE users CASCADE"
psql -h host -U user -d dbname < users_backup.sql
```

#### Automated Backup Strategy

```php
// Schedule daily backups (app/Console/Kernel.php)
protected function schedule(Schedule $schedule)
{
    $schedule->command('db:backup')
        ->daily()
        ->at('02:00')
        ->timezone('Asia/Manila');
        
    // Keep only last 7 days
    $schedule->command('db:backup:clean --keep=7')
        ->daily()
        ->at('03:00');
}
```

---

## Monitoring & Alerting

### Health Check Implementation

```php
// app/Http/Controllers/HealthCheckController.php
class HealthCheckController extends Controller
{
    public function check()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];

        $healthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');

        return response()->json([
            'status' => $healthy ? 'ok' : 'error',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase()
    {
        try {
            DB::connection('pgsql')->select('SELECT 1');
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function checkCache()
    {
        try {
            Cache::put('health_check', true, 10);
            Cache::get('health_check');
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function checkQueue()
    {
        try {
            $pending = DB::table('jobs')->count();
            return [
                'status' => 'ok',
                'pending_jobs' => $pending,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
```

### Error Notification

```php
// app/Exceptions/Handler.php
public function report(Throwable $exception)
{
    if ($exception instanceof \PDOException) {
        // Database error
        Log::critical('Database error occurred', [
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Send notification (implement your notification service)
        // Notification::send(new DatabaseErrorNotification($exception));
    }

    parent::report($exception);
}
```

---

## Emergency Procedures

### Database Connection Lost

1. **Immediate Actions**:
   ```bash
   # Check database status
   php artisan db:ping
   
   # Test connection
   php artisan tinker
   >>> DB::connection()->getPdo()
   ```

2. **If database is down**:
   - Check Render dashboard
   - Verify environment variables
   - Check database plan limits
   - Contact Render support

3. **Temporary Workaround**:
   - Enable maintenance mode: `php artisan down`
   - Display status page
   - Switch to read-only mode if possible

### Data Corruption Detected

1. **Stop writes immediately**:
   ```bash
   php artisan down --message="Maintenance in progress"
   ```

2. **Assess damage**:
   ```bash
   php artisan db:validate-migration
   ```

3. **Restore from backup**:
   ```bash
   # Identify last good backup
   ls -lah storage/backups/
   
   # Restore
   php artisan db:restore --backup=backup_20251103_120000.sql
   ```

4. **Verify integrity**:
   ```bash
   php artisan test --filter=DatabaseMigrationTest
   ```

5. **Resume service**:
   ```bash
   php artisan up
   ```

---

## Contact Information

### Support Channels

**Render Support**:
- Email: support@render.com
- Dashboard: https://dashboard.render.com/support
- Status Page: https://status.render.com

**Internal Team**:
- Database Admin: [Contact]
- DevOps: [Contact]
- Backend Lead: [Contact]

### Escalation Path

1. **Level 1**: Check documentation and logs
2. **Level 2**: Database admin review
3. **Level 3**: Render support ticket
4. **Level 4**: Emergency rollback
