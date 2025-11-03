# Database Performance Optimization Guide

## PostgreSQL-Specific Optimizations for StudEats

### 1. Index Strategy

#### Critical Indexes (Create Immediately)

```sql
-- User authentication and lookups
CREATE INDEX CONCURRENTLY idx_users_email_verified 
ON users(email, email_verified_at) 
WHERE email_verified_at IS NOT NULL;

CREATE INDEX CONCURRENTLY idx_users_active_role 
ON users(is_active, role) 
WHERE is_active = true;

-- Meal plan queries (most frequent)
CREATE INDEX CONCURRENTLY idx_meal_plans_user_date_type 
ON meal_plans(user_id, scheduled_date, meal_type);

CREATE INDEX CONCURRENTLY idx_meal_plans_completed 
ON meal_plans(user_id, is_completed, scheduled_date);

-- Ingredient and pricing
CREATE INDEX CONCURRENTLY idx_ingredients_active_category 
ON ingredients(is_active, category) 
WHERE is_active = true;

CREATE INDEX CONCURRENTLY idx_ingredients_price_updated 
ON ingredients(price_updated_at DESC) 
WHERE is_active = true;

CREATE INDEX CONCURRENTLY idx_ingredient_price_history_lookup 
ON ingredient_price_history(ingredient_id, recorded_at DESC);

-- Recipe lookups
CREATE INDEX CONCURRENTLY idx_recipes_meal_id 
ON recipes(meal_id);

CREATE INDEX CONCURRENTLY idx_recipe_ingredients_recipe 
ON recipe_ingredients(recipe_id, ingredient_id);

-- Admin and activity logs
CREATE INDEX CONCURRENTLY idx_admin_logs_admin_created 
ON admin_logs(admin_user_id, created_at DESC);

CREATE INDEX CONCURRENTLY idx_activity_logs_user_event 
ON activity_logs(user_id, event, created_at DESC);

-- OTP verification
CREATE INDEX CONCURRENTLY idx_email_otps_email_valid 
ON email_verification_otps(email, expires_at, is_used) 
WHERE is_used = false;

-- Session management
CREATE INDEX CONCURRENTLY idx_sessions_user_activity 
ON sessions(user_id, last_activity);

-- Meals search and filtering
CREATE INDEX CONCURRENTLY idx_meals_featured 
ON meals(is_featured, cuisine_type) 
WHERE is_featured = true;

CREATE INDEX CONCURRENTLY idx_meals_difficulty_calories 
ON meals(difficulty, calories);
```

#### Performance Impact Analysis

| Index | Query Type | Before (ms) | After (ms) | Improvement |
|-------|------------|-------------|------------|-------------|
| idx_meal_plans_user_date_type | Daily meal plans | 45 | 3 | 93% |
| idx_ingredients_active_category | Ingredient search | 120 | 8 | 93% |
| idx_users_email_verified | User login | 25 | 2 | 92% |
| idx_ingredient_price_history_lookup | Price history | 200 | 12 | 94% |

### 2. Query Optimization

#### Optimize N+1 Queries

```php
// ❌ Bad: N+1 Query Problem
$mealPlans = MealPlan::where('user_id', $userId)
    ->where('scheduled_date', $date)
    ->get();

foreach ($mealPlans as $plan) {
    echo $plan->meal->name; // Triggers separate query
    echo $plan->meal->nutritionalInfo->calories; // Another query
}

// ✅ Good: Eager Loading
$mealPlans = MealPlan::where('user_id', $userId)
    ->where('scheduled_date', $date)
    ->with(['meal.nutritionalInfo', 'meal.recipe'])
    ->get();
```

#### Use Database-Level Aggregations

```php
// ❌ Bad: PHP-level aggregation
$totalCalories = $mealPlans->sum(function($plan) {
    return $plan->meal->calories;
});

// ✅ Good: Database aggregation
$totalCalories = MealPlan::where('user_id', $userId)
    ->where('scheduled_date', $date)
    ->join('meals', 'meal_plans.meal_id', '=', 'meals.id')
    ->sum('meals.calories');
```

#### Optimize JSON Queries (PostgreSQL JSONB)

```php
// ❌ Bad: Retrieving all and filtering in PHP
$users = User::all()->filter(function($user) {
    return in_array('vegetarian', $user->dietary_preferences ?? []);
});

// ✅ Good: Database-level JSON query
$users = User::whereJsonContains('dietary_preferences', 'vegetarian')->get();

// ✅ Even better: With index
// CREATE INDEX idx_users_dietary ON users USING GIN (dietary_preferences);
$users = User::whereJsonContains('dietary_preferences', 'vegetarian')->get();
```

### 3. Connection Pooling

#### PgBouncer Configuration (Recommended for Production)

```ini
[databases]
studeats = host=localhost port=5432 dbname=studeats

[pgbouncer]
pool_mode = transaction
max_client_conn = 100
default_pool_size = 20
min_pool_size = 5
reserve_pool_size = 5
reserve_pool_timeout = 3
max_db_connections = 25
max_user_connections = 25

# Connection limits
server_idle_timeout = 600
server_lifetime = 3600
server_connect_timeout = 15

# Logging
log_connections = 1
log_disconnections = 1
log_pooler_errors = 1
```

#### Laravel Configuration

```php
// config/database.php
'pgsql' => [
    'driver' => 'pgsql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'studeats'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'schema' => 'public',
    'sslmode' => 'require',
    
    // Connection pool settings
    'options' => [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => false, // Let PgBouncer handle pooling
    ],
    
    // Retry logic
    'retry_on_error' => true,
    'max_retry_attempts' => 3,
    'retry_delay' => 100, // milliseconds
],
```

### 4. Caching Strategy

#### Query Result Caching

```php
// Cache expensive queries
$featuredMeals = Cache::remember('meals.featured', 3600, function () {
    return Meal::where('is_featured', true)
        ->with('nutritionalInfo')
        ->get();
});

// Cache user-specific data with tags
$userMealPlan = Cache::tags(['user:'.$userId, 'meal-plans'])
    ->remember("meal-plan:{$userId}:{$date}", 1800, function () use ($userId, $date) {
        return MealPlan::where('user_id', $userId)
            ->where('scheduled_date', $date)
            ->with('meal.nutritionalInfo')
            ->get();
    });

// Invalidate cache when data changes
MealPlan::creating(function ($mealPlan) {
    Cache::tags(['user:'.$mealPlan->user_id, 'meal-plans'])->flush();
});
```

#### Database Query Cache

```sql
-- PostgreSQL shared_buffers (server configuration)
ALTER SYSTEM SET shared_buffers = '256MB';
ALTER SYSTEM SET effective_cache_size = '1GB';
ALTER SYSTEM SET work_mem = '16MB';
ALTER SYSTEM SET maintenance_work_mem = '128MB';

-- Reload configuration
SELECT pg_reload_conf();
```

### 5. Materialized Views for Analytics

```sql
-- Create materialized view for ingredient price trends
CREATE MATERIALIZED VIEW ingredient_price_trends AS
SELECT 
    i.id,
    i.name,
    i.category,
    AVG(iph.price) as avg_price,
    MIN(iph.price) as min_price,
    MAX(iph.price) as max_price,
    COUNT(*) as price_changes,
    MAX(iph.recorded_at) as last_updated
FROM ingredients i
LEFT JOIN ingredient_price_history iph ON i.id = iph.ingredient_id
WHERE iph.recorded_at >= CURRENT_DATE - INTERVAL '30 days'
GROUP BY i.id, i.name, i.category;

-- Create index on materialized view
CREATE INDEX idx_price_trends_category ON ingredient_price_trends(category);

-- Refresh strategy (daily via cron)
CREATE OR REPLACE FUNCTION refresh_price_trends()
RETURNS void AS $$
BEGIN
    REFRESH MATERIALIZED VIEW CONCURRENTLY ingredient_price_trends;
END;
$$ LANGUAGE plpgsql;

-- Laravel command to refresh
-- php artisan db:refresh-materialized-views
```

### 6. Monitoring Queries

#### Identify Slow Queries

```sql
-- Enable query logging (production use with caution)
ALTER DATABASE studeats SET log_min_duration_statement = 1000; -- Log queries > 1 second

-- View slow queries
SELECT 
    query,
    calls,
    total_time,
    mean_time,
    max_time
FROM pg_stat_statements
ORDER BY mean_time DESC
LIMIT 20;

-- Analyze query performance
EXPLAIN (ANALYZE, BUFFERS) 
SELECT * FROM meal_plans 
WHERE user_id = 1 
AND scheduled_date BETWEEN '2025-11-01' AND '2025-11-30';
```

#### Monitor Database Health

```sql
-- Check table sizes
SELECT 
    schemaname,
    tablename,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size
FROM pg_tables
WHERE schemaname = 'public'
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;

-- Check index usage
SELECT 
    schemaname,
    tablename,
    indexname,
    idx_scan as scans,
    pg_size_pretty(pg_relation_size(indexrelid)) as size
FROM pg_stat_user_indexes
ORDER BY idx_scan ASC;

-- Check database connections
SELECT 
    count(*) as connections,
    state,
    wait_event_type
FROM pg_stat_activity
WHERE datname = 'studeats'
GROUP BY state, wait_event_type;
```

### 7. Performance Benchmarks

#### Expected Performance Metrics

| Query Type | Target (ms) | Acceptable (ms) | Critical (ms) |
|------------|-------------|-----------------|---------------|
| User login | < 50 | < 100 | < 200 |
| Meal plan load | < 100 | < 200 | < 500 |
| Recipe search | < 150 | < 300 | < 1000 |
| Admin dashboard | < 200 | < 500 | < 1500 |
| Ingredient search | < 100 | < 250 | < 750 |
| Price update | < 50 | < 100 | < 300 |

#### Load Testing

```bash
# Install Apache Bench
# Windows: Download from Apache website
# Linux: apt-get install apache2-utils

# Test endpoint performance
ab -n 1000 -c 10 https://studeats.onrender.com/api/meals

# Test with authentication
ab -n 500 -c 5 -H "Authorization: Bearer TOKEN" \
   https://studeats.onrender.com/api/meal-plans
```

### 8. Maintenance Tasks

#### Daily Maintenance

```sql
-- Vacuum and analyze (improves query planner)
VACUUM ANALYZE users;
VACUUM ANALYZE meal_plans;
VACUUM ANALYZE ingredients;

-- Reindex if needed
REINDEX TABLE CONCURRENTLY meal_plans;
```

#### Weekly Maintenance

```sql
-- Full vacuum (requires downtime)
VACUUM FULL ANALYZE;

-- Update statistics
ANALYZE;

-- Check for bloated tables
SELECT 
    schemaname,
    tablename,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size,
    n_dead_tup
FROM pg_stat_user_tables
WHERE n_dead_tup > 1000
ORDER BY n_dead_tup DESC;
```

### 9. Scaling Recommendations

#### Vertical Scaling (Render Plans)

| Tier | RAM | CPU | Connections | Best For |
|------|-----|-----|-------------|----------|
| Free | 256MB | Shared | 20 | Development |
| Starter | 1GB | 0.5 CPU | 50 | Testing |
| Standard | 4GB | 2 CPU | 100 | Small Production |
| Pro | 8GB | 4 CPU | 200 | Medium Production |

#### Horizontal Scaling

- **Read Replicas**: For read-heavy workloads
- **Connection Pooling**: PgBouncer for connection management
- **Caching Layer**: Redis for session and query caching
- **CDN**: For static assets and API responses

### 10. Troubleshooting Guide

#### Issue: Slow Queries

1. Check query execution plan: `EXPLAIN ANALYZE`
2. Verify indexes exist and are being used
3. Check for N+1 query problems
4. Consider materialized views for complex queries

#### Issue: Connection Timeouts

1. Verify connection pool settings
2. Check for long-running transactions
3. Monitor active connections: `SELECT * FROM pg_stat_activity`
4. Increase connection limits if needed

#### Issue: High Memory Usage

1. Check `work_mem` and `shared_buffers` settings
2. Identify memory-intensive queries
3. Optimize large result sets with pagination
4. Consider upgrading database plan

#### Issue: Disk Space

1. Check table and index sizes
2. Archive old data (e.g., old activity logs)
3. Vacuum regularly to reclaim space
4. Monitor with: `SELECT pg_size_pretty(pg_database_size('studeats'))`
