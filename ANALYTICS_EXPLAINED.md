# Analytics System Documentation

## Understanding the Data Sources

### 1. Historical View Counter (`posts.views`)
- **Location**: `posts` table, `views` column
- **What it tracks**: Total page loads (historical)
- **When it updates**: Every time someone visits a post
- **Limitations**: 
  - No unique visitor tracking
  - No time tracking
  - No geographic data
  - Includes bot traffic

### 2. Advanced Tracking System (`tracking_events`)
- **Location**: `tracking_events` table
- **What it tracks**: Detailed user behavior
- **When it started**: Only after the tracking system was fixed (August 2, 2025)
- **Features**:
  - Unique visitor tracking (via session IDs)
  - Time on page
  - Geographic data
  - Device/browser info
  - Scroll depth
  - Real user activity

## Why the Numbers Don't Match

1. **Historical vs New Data**:
   - `posts.views` has ALL historical data (including before tracking was fixed)
   - `tracking_events` only has data from when tracking started working

2. **Counting Methods**:
   - `posts.views`: Counts EVERY page load
   - `tracking_events`: Counts unique sessions and filters bots

## What Each Metric Means

### Total Views
- **Source**: `posts.views` OR `tracking_events` (whichever is higher)
- **Meaning**: Total number of times the page was loaded

### Unique Views
- **Source**: `COUNT(DISTINCT user_session_id)` from `tracking_events`
- **Meaning**: Number of unique visitors (by session)
- **Note**: Only available for posts visited after tracking was fixed

### Average Time
- **Source**: `AVG(time_on_page)` from `tracking_events` where `time_on_page > 0`
- **Meaning**: Average time users spent on the page
- **Note**: Only tracks active time (user must be scrolling/clicking)

### Geographic Data
- **Source**: `user_sessions` joined with `tracking_events`
- **Requirement**: Events must have `time_on_page > 10` seconds
- **Meaning**: Where your readers are from

## How to Interpret the Data

1. **If a post shows high views but 0 unique visitors**:
   - The views are historical (before tracking was fixed)
   - No one has visited since tracking started working

2. **If unique visitors < total views**:
   - This is normal - some users visit multiple times
   - The ratio shows reader loyalty

3. **If average time is 0**:
   - No tracking data exists yet
   - OR users are leaving very quickly

## Commands for Debugging

```bash
# See overall tracking status
php artisan tracking:check --hours=24

# Audit analytics data
php artisan analytics:audit

# Check specific post
php artisan analytics:audit {post_id}

# View diagnostics page
https://yourdomain.com/admin/analytics/diagnostics
```

## Future Improvements

1. **Data Migration**: Create a command to estimate historical unique visitors
2. **Unified View**: Show clearly which data is "tracked" vs "estimated"
3. **Real-time Updates**: WebSocket integration for live analytics