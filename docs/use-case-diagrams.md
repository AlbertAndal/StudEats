# StudEats - Use Case Diagrams

## 1. Primary Use Case Diagram

```mermaid
graph TD
    %% Actors
    GUEST[👤 Guest User]
    USER[👤 Registered User] 
    ADMIN[👩‍💼 Admin]
    SUPER_ADMIN[👨‍💼 Super Admin]
    SYSTEM[🤖 System/Scheduler]
    
    %% Guest User Use Cases
    GUEST --> UC1[Register Account]
    GUEST --> UC2[Login]
    GUEST --> UC3[Browse Recipes]
    GUEST --> UC4[View Landing Page]
    GUEST --> UC5[Contact Support]
    
    %% Registered User Use Cases  
    USER --> UC6[Manage Profile]
    USER --> UC7[Create Meal Plans]
    USER --> UC8[View Nutrition Info]
    USER --> UC9[Browse Recipe Library]
    USER --> UC10[Track Daily Progress]
    USER --> UC11[Manage Dietary Preferences]
    USER --> UC12[Calculate BMI]
    USER --> UC13[View Weekly Calendar]
    USER --> UC14[Mark Meals Complete]
    USER --> UC15[Search Recipes]
    USER --> UC16[Upload Profile Photo]
    USER --> UC17[Change Password]
    USER --> UC18[Logout]
    
    %% Admin Use Cases
    ADMIN --> UC19[Manage Users]
    ADMIN --> UC20[Manage Recipes]
    ADMIN --> UC21[Update Ingredient Prices]
    ADMIN --> UC22[View System Analytics]
    ADMIN --> UC23[Monitor User Activity]
    ADMIN --> UC24[Manage Market Prices]
    ADMIN --> UC25[Create Admin Reports]
    
    %% Super Admin Use Cases
    SUPER_ADMIN --> UC26[Create Admin Accounts]
    SUPER_ADMIN --> UC27[System Configuration]
    SUPER_ADMIN --> UC28[Database Management]
    SUPER_ADMIN --> UC29[Monitor System Health]
    
    %% System Use Cases
    SYSTEM --> UC30[Update Nutrition Data]
    SYSTEM --> UC31[Refresh Market Prices]
    SYSTEM --> UC32[Send Email Notifications]
    SYSTEM --> UC33[Generate Analytics]
    SYSTEM --> UC34[Clean Expired OTPs]
```

## 2. User Authentication Use Cases

```mermaid
graph TB
    subgraph "Authentication System"
        %% Actors
        GUEST[Guest User]
        USER[Registered User]
        EMAIL_SERVICE[Email Service]
        
        %% Use Cases
        GUEST --> REGISTER[Register New Account]
        GUEST --> LOGIN[Login to Account]
        GUEST --> FORGOT[Forgot Password]
        
        USER --> LOGOUT[Logout]
        USER --> CHANGE_PASS[Change Password]
        USER --> UPDATE_PROFILE[Update Profile]
        
        %% Registration Flow
        REGISTER --> VALIDATE[Validate User Data]
        VALIDATE --> SEND_OTP[Send OTP Email]
        SEND_OTP --> VERIFY_EMAIL[Verify Email Address]
        VERIFY_EMAIL --> ACTIVATE[Activate Account]
        
        %% Login Flow  
        LOGIN --> CHECK_CREDS[Verify Credentials]
        CHECK_CREDS --> CHECK_VERIFIED[Check Email Verification]
        CHECK_VERIFIED --> RECORD_LOGIN[Record Login Attempt]
        RECORD_LOGIN --> SHOW_DASHBOARD[Show Dashboard]
        
        %% Password Recovery
        FORGOT --> SEND_RESET[Send Reset Link]
        SEND_RESET --> RESET_PASSWORD[Reset Password]
        
        %% Email Integration
        EMAIL_SERVICE -.-> SEND_OTP
        EMAIL_SERVICE -.-> SEND_RESET
        EMAIL_SERVICE -.-> ACTIVATE
    end
```

## 3. Meal Planning Use Cases

```mermaid
graph TB
    subgraph "Meal Planning System"
        USER[Registered User]
        NUTRITION_API[Nutrition API]
        
        %% Main Use Cases
        USER --> PLAN_MEALS[Plan Meals]
        USER --> VIEW_PLANS[View Meal Plans]
        USER --> TRACK_NUTRITION[Track Nutrition]
        USER --> MANAGE_CALENDAR[Manage Calendar]
        
        %% Meal Planning Sub-processes
        PLAN_MEALS --> SELECT_DATE[Select Date & Time]
        SELECT_DATE --> BROWSE_RECIPES[Browse Recipe Catalog]
        BROWSE_RECIPES --> CHOOSE_MEAL[Choose Meal]
        CHOOSE_MEAL --> SET_SERVINGS[Set Serving Size]
        SET_SERVINGS --> CALC_COST[Calculate Cost]
        CALC_COST --> SAVE_PLAN[Save Meal Plan]
        
        %% Nutrition Tracking
        TRACK_NUTRITION --> GET_NUTRITION[Get Nutrition Data]
        GET_NUTRITION --> CALC_DAILY[Calculate Daily Totals]
        CALC_DAILY --> COMPARE_GOALS[Compare to Goals]
        COMPARE_GOALS --> SHOW_PROGRESS[Show Progress]
        
        %% Calendar Management
        MANAGE_CALENDAR --> VIEW_WEEKLY[View Weekly View]
        VIEW_WEEKLY --> EDIT_PLANS[Edit Existing Plans]
        VIEW_WEEKLY --> DELETE_PLANS[Delete Plans]
        VIEW_WEEKLY --> MARK_COMPLETE[Mark as Completed]
        
        %% Recipe Browsing
        BROWSE_RECIPES --> SEARCH_RECIPES[Search Recipes]
        BROWSE_RECIPES --> FILTER_CUISINE[Filter by Cuisine]
        BROWSE_RECIPES --> FILTER_DIET[Filter by Dietary Prefs]
        BROWSE_RECIPES --> VIEW_FEATURED[View Featured Recipes]
        
        %% External Integration
        NUTRITION_API -.-> GET_NUTRITION
        NUTRITION_API -.-> CALC_DAILY
    end
```

## 4. Admin Management Use Cases

```mermaid
graph TB
    subgraph "Admin Management System"
        ADMIN[Admin User]
        SUPER_ADMIN[Super Admin]
        
        %% Admin Use Cases
        ADMIN --> MANAGE_USERS[Manage Users]
        ADMIN --> MANAGE_RECIPES[Manage Recipes]
        ADMIN --> MANAGE_PRICES[Manage Prices]
        ADMIN --> VIEW_ANALYTICS[View Analytics]
        ADMIN --> SYSTEM_MONITOR[Monitor System]
        
        %% User Management
        MANAGE_USERS --> VIEW_USER_LIST[View User List]
        MANAGE_USERS --> SUSPEND_USER[Suspend Users]
        MANAGE_USERS --> RESET_USER_PASS[Reset User Passwords]
        MANAGE_USERS --> EXPORT_USERS[Export User Data]
        MANAGE_USERS --> VIEW_USER_ACTIVITY[View User Activity]
        
        %% Recipe Management
        MANAGE_RECIPES --> CREATE_RECIPE[Create Recipes]
        MANAGE_RECIPES --> EDIT_RECIPE[Edit Recipes]
        MANAGE_RECIPES --> DELETE_RECIPE[Delete Recipes]
        MANAGE_RECIPES --> FEATURE_RECIPE[Feature Recipes]
        MANAGE_RECIPES --> BULK_IMPORT[Bulk Import Recipes]
        
        %% Price Management
        MANAGE_PRICES --> UPDATE_INGREDIENT_PRICES[Update Ingredient Prices]
        MANAGE_PRICES --> VIEW_PRICE_HISTORY[View Price History]
        MANAGE_PRICES --> BULK_PRICE_UPDATE[Bulk Price Updates]
        MANAGE_PRICES --> PRICE_ALERTS[Set Price Alerts]
        
        %% Analytics
        VIEW_ANALYTICS --> USER_STATS[User Statistics]
        VIEW_ANALYTICS --> MEAL_STATS[Meal Planning Stats]
        VIEW_ANALYTICS --> RECIPE_POPULARITY[Recipe Popularity]
        VIEW_ANALYTICS --> SYSTEM_PERFORMANCE[System Performance]
        
        %% Super Admin Exclusive
        SUPER_ADMIN --> CREATE_ADMIN[Create Admin Accounts]
        SUPER_ADMIN --> SYSTEM_CONFIG[System Configuration]
        SUPER_ADMIN --> DATABASE_BACKUP[Database Management]
        SUPER_ADMIN --> EMERGENCY_ACCESS[Emergency Access]
    end
```

## 5. Recipe & Nutrition Use Cases

```mermaid
graph TB
    subgraph "Recipe & Nutrition System"
        USER[User]
        ADMIN[Admin]
        USDA_API[USDA Nutrition API]
        PRICE_API[Price API]
        
        %% User Recipe Use Cases
        USER --> BROWSE_RECIPES[Browse Recipes]
        USER --> SEARCH_RECIPES[Search Recipes] 
        USER --> VIEW_RECIPE[View Recipe Details]
        USER --> GET_NUTRITION[Get Nutrition Info]
        USER --> CALC_PORTIONS[Calculate Portions]
        
        %% Admin Recipe Management
        ADMIN --> ADD_RECIPE[Add New Recipe]
        ADMIN --> EDIT_RECIPE[Edit Recipe]
        ADMIN --> DELETE_RECIPE[Delete Recipe]
        ADMIN --> MANAGE_INGREDIENTS[Manage Ingredients]
        ADMIN --> UPDATE_NUTRITION[Update Nutrition Data]
        
        %% Recipe Browsing Features
        BROWSE_RECIPES --> FILTER_CUISINE[Filter by Cuisine]
        BROWSE_RECIPES --> FILTER_DIFFICULTY[Filter by Difficulty]
        BROWSE_RECIPES --> FILTER_MEAL_TYPE[Filter by Meal Type]
        BROWSE_RECIPES --> VIEW_FEATURED[View Featured Recipes]
        
        %% Recipe Details
        VIEW_RECIPE --> VIEW_INGREDIENTS[View Ingredients]
        VIEW_RECIPE --> VIEW_INSTRUCTIONS[View Instructions]
        VIEW_RECIPE --> VIEW_NUTRITION_FACTS[View Nutrition Facts]
        VIEW_RECIPE --> CALC_COST[Calculate Recipe Cost]
        VIEW_RECIPE --> ADJUST_SERVINGS[Adjust Servings]
        
        %% Nutrition Calculation
        GET_NUTRITION --> FETCH_INGREDIENT_DATA[Fetch Ingredient Data]
        FETCH_INGREDIENT_DATA --> CALC_RECIPE_NUTRITION[Calculate Recipe Nutrition]
        CALC_RECIPE_NUTRITION --> CACHE_RESULTS[Cache Results]
        
        %% Cost Calculation
        CALC_COST --> GET_INGREDIENT_PRICES[Get Ingredient Prices]
        GET_INGREDIENT_PRICES --> CALC_TOTAL_COST[Calculate Total Cost]
        CALC_TOTAL_COST --> SHOW_BUDGET_IMPACT[Show Budget Impact]
        
        %% External API Integration
        USDA_API -.-> FETCH_INGREDIENT_DATA
        PRICE_API -.-> GET_INGREDIENT_PRICES
    end
```

## 6. Profile Management Use Cases

```mermaid
graph TB
    subgraph "Profile Management System"
        USER[Registered User]
        
        %% Main Profile Use Cases
        USER --> VIEW_PROFILE[View Profile]
        USER --> EDIT_PROFILE[Edit Profile]
        USER --> MANAGE_PREFERENCES[Manage Dietary Preferences]
        USER --> HEALTH_METRICS[Manage Health Metrics]
        USER --> ACCOUNT_SETTINGS[Account Settings]
        
        %% Profile Information
        EDIT_PROFILE --> UPDATE_BASIC_INFO[Update Basic Info]
        EDIT_PROFILE --> UPLOAD_PHOTO[Upload Profile Photo]
        EDIT_PROFILE --> CROP_PHOTO[Crop Profile Photo]
        EDIT_PROFILE --> UPDATE_CONTACT[Update Contact Info]
        
        %% Dietary Preferences
        MANAGE_PREFERENCES --> SET_ALLERGIES[Set Allergies]
        MANAGE_PREFERENCES --> SET_DIET_TYPE[Set Diet Type]
        MANAGE_PREFERENCES --> SET_RESTRICTIONS[Set Food Restrictions]
        MANAGE_PREFERENCES --> SAVE_PREFERENCES[Save Preferences]
        
        %% Health Metrics
        HEALTH_METRICS --> UPDATE_HEIGHT[Update Height]
        HEALTH_METRICS --> UPDATE_WEIGHT[Update Weight]
        HEALTH_METRICS --> SET_ACTIVITY_LEVEL[Set Activity Level]
        HEALTH_METRICS --> CALC_BMI[Calculate BMI]
        HEALTH_METRICS --> SET_GOALS[Set Calorie Goals]
        
        %% Account Settings
        ACCOUNT_SETTINGS --> CHANGE_PASSWORD[Change Password]
        ACCOUNT_SETTINGS --> SET_TIMEZONE[Set Timezone]
        ACCOUNT_SETTINGS --> EMAIL_PREFERENCES[Email Preferences]
        ACCOUNT_SETTINGS --> PRIVACY_SETTINGS[Privacy Settings]
        ACCOUNT_SETTINGS --> DELETE_ACCOUNT[Delete Account]
        
        %% BMI Calculation Flow
        CALC_BMI --> VALIDATE_METRICS[Validate Height/Weight]
        VALIDATE_METRICS --> PERFORM_CALCULATION[Perform BMI Calculation]
        PERFORM_CALCULATION --> CATEGORIZE_BMI[Categorize BMI Result]
        CATEGORIZE_BMI --> SUGGEST_CALORIES[Suggest Calorie Intake]
        SUGGEST_CALORIES --> UPDATE_GOALS[Update Daily Goals]
    end
```

## 7. System Integration Use Cases

```mermaid
graph TB
    subgraph "System Integration"
        SCHEDULER[System Scheduler]
        EMAIL_SYSTEM[Email System]
        CACHE_SYSTEM[Cache System]
        
        %% Scheduled Tasks
        SCHEDULER --> UPDATE_PRICES[Update Market Prices]
        SCHEDULER --> REFRESH_NUTRITION[Refresh Nutrition Data]
        SCHEDULER --> CLEANUP_EXPIRED[Cleanup Expired Data]
        SCHEDULER --> GENERATE_REPORTS[Generate Daily Reports]
        SCHEDULER --> BACKUP_DATA[Backup Database]
        
        %% Email Operations
        EMAIL_SYSTEM --> SEND_VERIFICATION[Send Verification Emails]
        EMAIL_SYSTEM --> SEND_WELCOME[Send Welcome Emails]
        EMAIL_SYSTEM --> SEND_NOTIFICATIONS[Send Notifications]
        EMAIL_SYSTEM --> SEND_REPORTS[Send Admin Reports]
        EMAIL_SYSTEM --> PASSWORD_RESET[Send Password Reset]
        
        %% Cache Operations
        CACHE_SYSTEM --> CACHE_NUTRITION[Cache Nutrition Data]
        CACHE_SYSTEM --> CACHE_PRICES[Cache Price Data]
        CACHE_SYSTEM --> CACHE_RECIPES[Cache Recipe Data]
        CACHE_SYSTEM --> INVALIDATE_CACHE[Invalidate Old Cache]
        
        %% External API Calls
        UPDATE_PRICES --> CALL_BANTAY_API[Call Bantay Presyo API]
        REFRESH_NUTRITION --> CALL_USDA_API[Call USDA API]
        
        %% Error Handling
        CALL_BANTAY_API --> HANDLE_API_ERRORS[Handle API Errors]
        CALL_USDA_API --> HANDLE_API_ERRORS
        HANDLE_API_ERRORS --> LOG_ERRORS[Log System Errors]
        HANDLE_API_ERRORS --> RETRY_REQUESTS[Retry Failed Requests]
        
        %% Cleanup Operations
        CLEANUP_EXPIRED --> CLEAN_OTP[Clean Expired OTPs]
        CLEANUP_EXPIRED --> CLEAN_SESSIONS[Clean Old Sessions]
        CLEANUP_EXPIRED --> CLEAN_CACHE[Clean Expired Cache]
        CLEANUP_EXPIRED --> ARCHIVE_LOGS[Archive Old Logs]
    end
```

## Use Case Relationships and Dependencies

### **Actor Relationships**
- **Guest User** → **Registered User** (through registration)
- **Registered User** → **Admin** (role promotion by Super Admin)
- **Admin** → **Super Admin** (role promotion by existing Super Admin)

### **System Dependencies**
- **Email Verification** depends on **Email Service**
- **Nutrition Calculation** depends on **USDA API**
- **Price Updates** depend on **Bantay Presyo API**
- **Meal Planning** depends on **Recipe Database**
- **User Analytics** depend on **Activity Logging**

### **Data Flow Dependencies**
1. **User Registration** → **Email Verification** → **Account Activation**
2. **Recipe Creation** → **Nutrition Calculation** → **Cost Calculation**
3. **Meal Planning** → **Nutrition Tracking** → **Progress Monitoring**
4. **Ingredient Updates** → **Price Refresh** → **Cost Recalculation**
5. **User Activity** → **Analytics Generation** → **Admin Reports**

### **Business Rules**
- Users must verify email before accessing full features
- Only verified users can create meal plans
- Admins can manage users but not other admins (except Super Admin)
- Super Admin has unrestricted access to all system functions
- Nutrition data is cached for 24 hours to reduce API calls
- Price data is updated automatically every 6 hours
- OTP codes expire after 5 minutes for security