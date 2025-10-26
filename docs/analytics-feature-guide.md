# Analytics Feature Documentation

## Overview
The StudEats Admin Analytics feature provides real-time data visualization and insights for administrators to monitor application performance, user activity, and system health.

## Features

### 1. Real-Time Data Collection
- **User Metrics**: Total users, active users, verified accounts, growth rates
- **Meal Analytics**: Recipe counts, cuisine distribution, difficulty levels, popularity metrics
- **Activity Tracking**: Meal plan statistics, completion rates, admin actions
- **Trend Analysis**: 7-day historical data for user registrations and meal plan creation
- **System Metrics**: Memory usage, database status, queue monitoring

### 2. Interactive Dashboard
- **Dropdown Panel**: Accessible from admin header navigation
- **Auto-Refresh**: Updates every 30 seconds when open
- **Manual Refresh**: One-click cache refresh with spinning indicator
- **Responsive Design**: Optimized for all screen sizes
- **Real-Time Updates**: Live timestamp showing last update

### 3. Data Visualization

#### Main Analytics Cards (4 Cards)
1. **Total Users Card** (Blue Gradient)
   - Total user count
   - Growth rate percentage
   - New users this week
   - Lucide users icon

2. **Active Users Card** (Green Gradient)
   - Active user count
   - Activity percentage
   - Verified accounts count
   - Lucide activity icon

3. **Meal Plans Card** (Orange Gradient)
   - Total meal plans
   - Completion rate
   - Completed plans count
   - Lucide calendar-check icon

4. **Recipes Card** (Purple Gradient)
   - Total recipes
   - Featured recipes count
   - Average recipe cost
   - Lucide chef-hat icon

#### Quick Stats Bar (4 Metrics)
- New users today
- Meal plans created today
- Admin actions today
- System status indicator

## Technical Implementation

### Backend Components

#### Controller: `AnalyticsController.php`
Location: `app/Http/Controllers/Admin/AnalyticsController.php`

**Key Methods:**
```php
getData()              // Main analytics endpoint
getUserAnalytics()     // User-specific metrics
getMealAnalytics()     // Meal and recipe data
getActivityAnalytics() // Activity tracking
getTrendAnalytics()    // Historical trend data
getSystemMetrics()     // System health metrics
getHourlyActivity()    // Hourly activity breakdown
refresh()              // Cache refresh endpoint
```

**Caching Strategy:**
- Primary cache key: `admin_analytics`
- Cache duration: 60 seconds
- Hourly activity cache: 300 seconds (5 minutes)
- Manual refresh clears all analytics caches

#### Routes
Location: `routes/web.php`

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/analytics/data', [AnalyticsController::class, 'getData'])
        ->name('analytics.data');
    Route::get('/analytics/hourly', [AnalyticsController::class, 'getHourlyActivity'])
        ->name('analytics.hourly');
    Route::post('/analytics/refresh', [AnalyticsController::class, 'refresh'])
        ->name('analytics.refresh');
});
```

### Frontend Components

#### Header Integration
Location: `resources/views/admin/partials/header.blade.php`

**UI Elements:**
- Analytics button with chevron indicator
- Full-width dropdown panel
- Loading states
- Error handling
- Responsive grid layout

**JavaScript Functions:**
```javascript
toggleAnalytics()      // Open/close dropdown
closeAnalytics()       // Close dropdown programmatically
loadAnalytics()        // Fetch data from API
renderAnalytics(data)  // Render data to DOM
updateLastUpdateTime() // Update timestamp display
refreshAnalytics()     // Manual refresh with animation
showAnalyticsError()   // Display error state
```

**Auto-Refresh System:**
- Interval: 30 seconds
- Only active when dropdown is open
- Automatically stops when dropdown closes
- Cleanup on page unload

### Data Structure

#### API Response Format
```json
{
    "success": true,
    "data": {
        "users": {
            "total": 150,
            "active": 120,
            "verified": 100,
            "new_today": 5,
            "new_this_week": 25,
            "new_this_month": 80,
            "suspended": 3,
            "by_role": {
                "user": 145,
                "admin": 4,
                "super_admin": 1
            },
            "growth_rate": 12.5
        },
        "meals": {
            "total": 50,
            "featured": 10,
            "new_this_week": 3,
            "by_cuisine": {
                "Filipino": 20,
                "Asian": 15,
                "Western": 10,
                "International": 5
            },
            "by_difficulty": {
                "easy": 20,
                "medium": 25,
                "hard": 5
            },
            "avg_cost": 85.50,
            "avg_calories": 450,
            "popular": [...]
        },
        "activity": {
            "meal_plans_total": 500,
            "meal_plans_completed": 350,
            "meal_plans_pending": 150,
            "completion_rate": 70.0,
            "plans_today": 15,
            "plans_this_week": 120,
            "by_meal_type": {
                "breakfast": 170,
                "lunch": 165,
                "dinner": 165
            },
            "admin_actions_today": 8,
            "admin_actions_week": 45
        },
        "trends": {
            "labels": ["Sep 27", "Sep 28", ...],
            "datasets": {
                "user_registrations": [5, 8, 3, ...],
                "meal_plans_created": [25, 30, 28, ...]
            }
        },
        "system": {
            "memory_usage": {
                "used": "128 MB",
                "limit": "512 MB",
                "percentage": 25.0
            },
            "database": {
                "status": "healthy",
                "tables": 25
            },
            "cache": {
                "status": "operational",
                "driver": "file"
            },
            "queue": {
                "pending_jobs": 5,
                "failed_jobs": 0
            }
        }
    },
    "timestamp": "2025-10-04T10:30:00Z"
}
```

## Usage Guide

### For Administrators

#### Accessing Analytics
1. Log in to admin dashboard
2. Click "Analytics" button in header navigation
3. Dropdown panel opens with live data
4. Data auto-refreshes every 30 seconds

#### Manual Refresh
1. Click "Refresh" button in analytics panel
2. Watch spinning icon animation
3. Data updates with new timestamp
4. Cache is cleared and rebuilt

#### Interpreting Metrics

**Growth Rate:**
- Positive (green): User base growing
- Negative (red): User base declining
- Calculated: (This week - Last week) / Last week * 100

**Completion Rate:**
- Percentage of meal plans marked complete
- Higher is better (engaged users)
- Formula: (Completed / Total) * 100

**System Status:**
- Green "Online": All systems operational
- Red "Error": Database connection issues

### Performance Considerations

**Caching:**
- Analytics data cached for 60 seconds
- Reduces database load
- Ensures fast response times
- Manual refresh clears cache

**Query Optimization:**
- Uses indexed columns for filtering
- Aggregate queries use groupBy
- Limits result sets appropriately
- Eager loading for relationships

**Auto-Refresh Impact:**
- Only runs when panel open
- 30-second interval prevents overload
- Automatic cleanup on close
- No background polling

## Database Queries

### Key Queries Used

```sql
-- User growth rate
SELECT COUNT(*) FROM users 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK);

-- Meal plan completion rate
SELECT 
    COUNT(*) as total,
    SUM(is_completed) as completed
FROM meal_plans;

-- Popular meals
SELECT m.*, COUNT(mp.id) as plans_count
FROM meals m
LEFT JOIN meal_plans mp ON m.id = mp.meal_id
GROUP BY m.id
ORDER BY plans_count DESC
LIMIT 5;

-- Cuisine distribution
SELECT cuisine_type, COUNT(*) as count
FROM meals
GROUP BY cuisine_type
ORDER BY count DESC
LIMIT 10;
```

## Error Handling

### Frontend Error States
1. **Network Error**: "Network error - please try again"
2. **API Error**: "Failed to load analytics data"
3. **Loading State**: Spinner with "Loading analytics data..."

### Backend Error Handling
```php
try {
    // Data collection
} catch (\Exception $e) {
    Log::error('Analytics data retrieval failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
    
    return response()->json([
        'success' => false,
        'error' => 'Failed to load analytics data',
    ], 500);
}
```

## Future Enhancements

### Planned Features
1. **Chart Visualization**: Line charts for trend data
2. **Export Functionality**: Download analytics as CSV/PDF
3. **Date Range Filtering**: Custom date range selection
4. **Email Reports**: Scheduled analytics emails
5. **Advanced Metrics**: User retention, churn rate, engagement scores
6. **Comparison Mode**: Week-over-week, month-over-month comparisons
7. **Real-Time Notifications**: Alert on significant metric changes

### Additional Metrics
- Average session duration
- User location distribution
- Device/browser analytics
- Peak usage hours
- Revenue tracking (if applicable)
- User journey analytics

## Testing

### Manual Testing
```bash
# Access analytics endpoint directly
curl -X GET http://localhost:8000/admin/analytics/data \
  -H "Cookie: laravel_session=..." \
  -H "Accept: application/json"

# Test cache refresh
curl -X POST http://localhost:8000/admin/analytics/refresh \
  -H "Cookie: laravel_session=..." \
  -H "X-CSRF-TOKEN: ..." \
  -H "Accept: application/json"
```

### Laravel Tinker Testing
```php
// Test analytics controller
$controller = new \App\Http\Controllers\Admin\AnalyticsController();
$response = $controller->getData();
$data = $response->getData();
dump($data);

// Test individual analytics methods
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getUserAnalytics');
$method->setAccessible(true);
$userAnalytics = $method->invoke($controller);
dump($userAnalytics);
```

## Security

### Access Control
- Requires authentication (`auth` middleware)
- Admin role required (`admin` middleware)
- CSRF protection on refresh endpoint
- No sensitive data exposure

### Rate Limiting
- Consider adding rate limiting for refresh endpoint
- Recommended: 10 requests per minute per user

## Troubleshooting

### Common Issues

**1. Analytics not loading**
- Check admin authentication
- Verify database connection
- Check browser console for errors
- Clear Laravel cache: `php artisan cache:clear`

**2. Stale data showing**
- Click manual refresh button
- Check cache driver configuration
- Verify cache is writable

**3. Auto-refresh not working**
- Check JavaScript console for errors
- Verify dropdown is open
- Check network tab for API calls

**4. Performance issues**
- Review database query performance
- Optimize with indexes
- Increase cache duration
- Consider read replicas for large datasets

## Maintenance

### Regular Tasks
- Monitor cache hit rates
- Review query performance
- Update metrics as needed
- Archive historical data

### Cache Management
```bash
# Clear analytics cache
php artisan cache:forget admin_analytics
php artisan cache:forget hourly_activity

# Clear all cache
php artisan cache:clear
```

## Support

For issues or feature requests related to analytics:
1. Check this documentation
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for frontend errors
4. Contact development team

---

**Last Updated**: October 4, 2025  
**Version**: 1.0.0  
**Author**: StudEats Development Team
