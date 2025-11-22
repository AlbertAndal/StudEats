# StudEats - System Flowcharts and Architecture Diagrams

## 1. High-Level System Architecture

```mermaid
graph TB
    subgraph "User Interface Layer"
        WEB[Web Browser]
        MOBILE[Mobile App Ready]
    end
    
    subgraph "Application Layer"
        LARAVEL[Laravel 12 Framework]
        BLADE[Blade Templates]
        REACT[React Components]
        TAILWIND[TailwindCSS 4]
    end
    
    subgraph "Business Logic Layer"
        AUTH[Authentication System]
        MEAL[Meal Planning Engine]
        NUTRITION[Nutrition Calculator]
        ADMIN[Admin Management]
        PRICE[Price Tracking]
    end
    
    subgraph "Data Layer"
        MYSQL[(MySQL Database)]
        CACHE[(Redis Cache)]
        STORAGE[File Storage]
    end
    
    subgraph "External Services"
        USDA[USDA Nutrition API]
        BANTAY[Bantay Presyo API]
        EMAIL[Email Service]
        QUEUE[Job Queue System]
    end
    
    WEB --> LARAVEL
    MOBILE --> LARAVEL
    LARAVEL --> AUTH
    LARAVEL --> MEAL
    LARAVEL --> NUTRITION
    LARAVEL --> ADMIN
    LARAVEL --> PRICE
    
    AUTH --> MYSQL
    MEAL --> MYSQL
    NUTRITION --> USDA
    ADMIN --> MYSQL
    PRICE --> BANTAY
    
    LARAVEL --> CACHE
    LARAVEL --> STORAGE
    LARAVEL --> EMAIL
    LARAVEL --> QUEUE
    
    BLADE --> REACT
    REACT --> TAILWIND
```

## 2. User Authentication Flow

```mermaid
flowchart TD
    START([User Starts Registration]) --> REGISTER[Fill Registration Form]
    REGISTER --> VALIDATE{Valid Data?}
    VALIDATE -->|No| REGISTER
    VALIDATE -->|Yes| CREATE_USER[Create User Account]
    
    CREATE_USER --> SEND_OTP[Send OTP Email]
    SEND_OTP --> VERIFY_FORM[Show OTP Verification Form]
    VERIFY_FORM --> ENTER_OTP[User Enters OTP]
    ENTER_OTP --> CHECK_OTP{Valid OTP?}
    
    CHECK_OTP -->|No| RETRY{Attempts < 5?}
    RETRY -->|Yes| VERIFY_FORM
    RETRY -->|No| BLOCKED[Account Blocked]
    
    CHECK_OTP -->|Yes| VERIFY_EMAIL[Mark Email Verified]
    VERIFY_EMAIL --> LOGIN_TRACKING[Record Login Count]
    LOGIN_TRACKING --> FIRST_LOGIN{First Login?}
    
    FIRST_LOGIN -->|Yes| WELCOME[Show "Welcome [Name]"]
    FIRST_LOGIN -->|No| WELCOME_BACK[Show "Welcome Back [Name]"]
    
    WELCOME --> DASHBOARD[Redirect to Dashboard]
    WELCOME_BACK --> DASHBOARD
    
    BLOCKED --> END([End])
    DASHBOARD --> END
    
    %% Login Flow
    LOGIN_START([User Starts Login]) --> LOGIN_FORM[Enter Credentials]
    LOGIN_FORM --> AUTH_CHECK{Valid Credentials?}
    AUTH_CHECK -->|No| LOGIN_FORM
    AUTH_CHECK -->|Yes| EMAIL_VERIFIED{Email Verified?}
    EMAIL_VERIFIED -->|No| VERIFY_FORM
    EMAIL_VERIFIED -->|Yes| LOGIN_TRACKING
```

## 3. Meal Planning System Flow

```mermaid
flowchart TD
    START([User Opens Meal Planning]) --> VIEW_DASHBOARD[View Dashboard]
    VIEW_DASHBOARD --> CHOOSE_ACTION{User Action}
    
    CHOOSE_ACTION -->|Create Meal Plan| CREATE_FLOW[Create Meal Plan Flow]
    CHOOSE_ACTION -->|View Weekly Plans| WEEKLY_VIEW[Weekly Calendar View]
    CHOOSE_ACTION -->|Browse Recipes| RECIPE_BROWSER[Recipe Browser]
    
    CREATE_FLOW --> SELECT_DATE[Select Date & Meal Type]
    SELECT_DATE --> BROWSE_MEALS[Browse Available Meals]
    BROWSE_MEALS --> SELECT_MEAL[Select Meal]
    SELECT_MEAL --> SET_SERVINGS[Set Servings Count]
    SET_SERVINGS --> CALC_NUTRITION[Calculate Nutrition Info]
    
    CALC_NUTRITION --> CHECK_API{Use USDA API?}
    CHECK_API -->|Yes| FETCH_NUTRITION[Fetch from USDA API]
    CHECK_API -->|No| USE_STORED[Use Stored Nutrition Data]
    
    FETCH_NUTRITION --> CACHE_DATA[Cache Nutrition Data]
    USE_STORED --> DISPLAY_INFO[Display Nutrition Info]
    CACHE_DATA --> DISPLAY_INFO
    
    DISPLAY_INFO --> CONFIRM{User Confirms?}
    CONFIRM -->|No| BROWSE_MEALS
    CONFIRM -->|Yes| SAVE_PLAN[Save Meal Plan]
    SAVE_PLAN --> UPDATE_DASHBOARD[Update Dashboard]
    
    WEEKLY_VIEW --> CALENDAR_DISPLAY[Display Weekly Calendar]
    CALENDAR_DISPLAY --> MEAL_ACTIONS{User Action}
    MEAL_ACTIONS -->|Edit| EDIT_MEAL[Edit Meal Plan]
    MEAL_ACTIONS -->|Delete| DELETE_MEAL[Delete Meal Plan]
    MEAL_ACTIONS -->|Mark Complete| TOGGLE_COMPLETE[Toggle Completion Status]
    
    EDIT_MEAL --> SELECT_DATE
    DELETE_MEAL --> CONFIRM_DELETE{Confirm Delete?}
    CONFIRM_DELETE -->|Yes| REMOVE_PLAN[Remove from Database]
    CONFIRM_DELETE -->|No| CALENDAR_DISPLAY
    REMOVE_PLAN --> UPDATE_DASHBOARD
    
    TOGGLE_COMPLETE --> UPDATE_STATUS[Update Completion Status]
    UPDATE_STATUS --> UPDATE_DASHBOARD
    
    UPDATE_DASHBOARD --> END([End])
```

## 4. Nutrition Calculation System

```mermaid
flowchart TD
    START([Nutrition Calculation Request]) --> IDENTIFY_SOURCE{Data Source}
    
    IDENTIFY_SOURCE -->|Recipe| RECIPE_CALC[Recipe Calculation]
    IDENTIFY_SOURCE -->|Single Ingredient| INGREDIENT_CALC[Ingredient Calculation]
    
    RECIPE_CALC --> GET_INGREDIENTS[Get Recipe Ingredients]
    GET_INGREDIENTS --> LOOP_INGREDIENTS[For Each Ingredient]
    
    LOOP_INGREDIENTS --> CHECK_CACHE{In Cache?}
    CHECK_CACHE -->|Yes| USE_CACHED[Use Cached Data]
    CHECK_CACHE -->|No| API_REQUEST[Request USDA API]
    
    API_REQUEST --> PARSE_RESPONSE[Parse API Response]
    PARSE_RESPONSE --> EXTRACT_NUTRIENTS[Extract Nutrients:
    - Calories (208)
    - Protein (203)  
    - Carbs (205)
    - Fat (204)
    - Fiber (291)
    - Sugar (269)
    - Sodium (1093)]
    
    EXTRACT_NUTRIENTS --> CACHE_RESULT[Cache Results]
    CACHE_RESULT --> CALCULATE_PORTION[Calculate Per Portion]
    USE_CACHED --> CALCULATE_PORTION
    
    CALCULATE_PORTION --> SUM_NUTRIENTS[Sum All Nutrients]
    SUM_NUTRIENTS --> MORE_INGREDIENTS{More Ingredients?}
    MORE_INGREDIENTS -->|Yes| LOOP_INGREDIENTS
    MORE_INGREDIENTS -->|No| FINAL_CALC[Final Calculation]
    
    INGREDIENT_CALC --> CHECK_CACHE
    
    FINAL_CALC --> APPLY_SERVINGS[Apply Serving Size]
    APPLY_SERVINGS --> RETURN_DATA[Return Nutrition Data]
    RETURN_DATA --> END([End])
    
    %% Error Handling
    API_REQUEST --> API_ERROR{API Error?}
    API_ERROR -->|Yes| LOG_ERROR[Log Error]
    API_ERROR -->|No| PARSE_RESPONSE
    LOG_ERROR --> USE_DEFAULT[Use Default Values]
    USE_DEFAULT --> CALCULATE_PORTION
```

## 5. Admin Management System

```mermaid
flowchart TD
    START([Admin Access]) --> ADMIN_LOGIN[Admin Login]
    ADMIN_LOGIN --> VERIFY_ROLE{Admin Role?}
    
    VERIFY_ROLE -->|No| ACCESS_DENIED[Access Denied]
    VERIFY_ROLE -->|Yes| ADMIN_DASHBOARD[Admin Dashboard]
    
    ADMIN_DASHBOARD --> ADMIN_MENU{Admin Action}
    
    ADMIN_MENU -->|User Management| USER_MGMT[User Management]
    ADMIN_MENU -->|Recipe Management| RECIPE_MGMT[Recipe Management] 
    ADMIN_MENU -->|Price Management| PRICE_MGMT[Price Management]
    ADMIN_MENU -->|System Analytics| ANALYTICS[System Analytics]
    
    USER_MGMT --> USER_ACTIONS{User Action}
    USER_ACTIONS -->|View Users| LIST_USERS[List All Users]
    USER_ACTIONS -->|Suspend User| SUSPEND_USER[Suspend User Account]
    USER_ACTIONS -->|Reset Password| RESET_PASS[Reset User Password]
    USER_ACTIONS -->|Change Role| UPDATE_ROLE[Update User Role]
    
    RECIPE_MGMT --> RECIPE_ACTIONS{Recipe Action}
    RECIPE_ACTIONS -->|Add Recipe| CREATE_RECIPE[Create New Recipe]
    RECIPE_ACTIONS -->|Edit Recipe| EDIT_RECIPE[Edit Existing Recipe]
    RECIPE_ACTIONS -->|Delete Recipe| DELETE_RECIPE[Delete Recipe]
    RECIPE_ACTIONS -->|Feature Recipe| FEATURE_RECIPE[Toggle Featured Status]
    
    PRICE_MGMT --> PRICE_ACTIONS{Price Action}
    PRICE_ACTIONS -->|Update Prices| UPDATE_PRICES[Update Ingredient Prices]
    PRICE_ACTIONS -->|View History| PRICE_HISTORY[View Price History]
    PRICE_ACTIONS -->|Bulk Upload| BULK_UPLOAD[Bulk Price Upload]
    
    ANALYTICS --> ANALYTICS_DATA[Generate Analytics Data:
    - User Activity
    - Meal Planning Stats  
    - Popular Recipes
    - System Performance]
    
    LIST_USERS --> LOG_ACTION[Log Admin Action]
    SUSPEND_USER --> LOG_ACTION
    RESET_PASS --> LOG_ACTION
    UPDATE_ROLE --> LOG_ACTION
    CREATE_RECIPE --> LOG_ACTION
    EDIT_RECIPE --> LOG_ACTION
    DELETE_RECIPE --> LOG_ACTION
    FEATURE_RECIPE --> LOG_ACTION
    UPDATE_PRICES --> LOG_ACTION
    
    LOG_ACTION --> ADMIN_DASHBOARD
    ACCESS_DENIED --> END([End])
    ANALYTICS_DATA --> ADMIN_DASHBOARD
```

## 6. Price Management System Flow

```mermaid
flowchart TD
    START([Price Management]) --> PRICE_UPDATE[Schedule Price Update]
    PRICE_UPDATE --> BANTAY_API[Call Bantay Presyo API]
    
    BANTAY_API --> API_SUCCESS{API Success?}
    API_SUCCESS -->|No| RETRY_LOGIC{Retry Count < 3?}
    API_SUCCESS -->|Yes| PARSE_PRICES[Parse Price Data]
    
    RETRY_LOGIC -->|Yes| WAIT[Wait 30 seconds]
    RETRY_LOGIC -->|No| LOG_FAILURE[Log API Failure]
    WAIT --> BANTAY_API
    
    PARSE_PRICES --> VALIDATE_DATA{Valid Price Data?}
    VALIDATE_DATA -->|No| LOG_FAILURE
    VALIDATE_DATA -->|Yes| UPDATE_DB[Update Database]
    
    UPDATE_DB --> CREATE_HISTORY[Create Price History Record]
    CREATE_HISTORY --> UPDATE_INGREDIENT[Update Current Price in Ingredients]
    UPDATE_INGREDIENT --> CALC_STATS[Calculate Price Statistics]
    
    CALC_STATS --> NOTIFY_ADMIN{Significant Change?}
    NOTIFY_ADMIN -->|Yes| SEND_NOTIFICATION[Send Admin Notification]
    NOTIFY_ADMIN -->|No| UPDATE_CACHE[Update Price Cache]
    
    SEND_NOTIFICATION --> UPDATE_CACHE
    LOG_FAILURE --> UPDATE_CACHE
    UPDATE_CACHE --> END([End])
```

## 7. Email Verification System

```mermaid
flowchart TD
    START([Email Verification]) --> GENERATE_OTP[Generate 6-digit OTP]
    GENERATE_OTP --> CREATE_TOKEN[Create Verification Token]
    CREATE_TOKEN --> STORE_OTP[Store OTP in Database:
    - Email
    - OTP Code  
    - Expires in 5 minutes
    - Not used]
    
    STORE_OTP --> QUEUE_EMAIL[Queue Email Job]
    QUEUE_EMAIL --> SEND_EMAIL[Send OTP Email]
    
    SEND_EMAIL --> EMAIL_SUCCESS{Email Sent?}
    EMAIL_SUCCESS -->|No| EMAIL_RETRY{Retry < 3?}
    EMAIL_SUCCESS -->|Yes| WAIT_INPUT[Wait for User Input]
    
    EMAIL_RETRY -->|Yes| QUEUE_EMAIL
    EMAIL_RETRY -->|No| EMAIL_FAILED[Mark Email Failed]
    
    WAIT_INPUT --> USER_SUBMIT[User Submits OTP]
    USER_SUBMIT --> VALIDATE_OTP{Valid OTP?}
    
    VALIDATE_OTP -->|No| ATTEMPT_COUNT{Attempts < 5?}
    VALIDATE_OTP -->|Yes| CHECK_EXPIRY{Not Expired?}
    
    ATTEMPT_COUNT -->|Yes| SHOW_ERROR[Show Error Message]
    ATTEMPT_COUNT -->|No| RATE_LIMIT[Rate Limit Applied]
    SHOW_ERROR --> WAIT_INPUT
    
    CHECK_EXPIRY -->|No| EXPIRED_OTP[OTP Expired]
    CHECK_EXPIRY -->|Yes| MARK_VERIFIED[Mark Email Verified]
    
    MARK_VERIFIED --> UPDATE_OTP[Mark OTP as Used]
    UPDATE_OTP --> RECORD_LOGIN[Record First Login]
    RECORD_LOGIN --> REDIRECT_DASHBOARD[Redirect to Dashboard]
    
    EXPIRED_OTP --> RESEND_OPTION[Offer Resend Option]
    RESEND_OPTION --> GENERATE_OTP
    
    RATE_LIMIT --> BLOCK_TEMP[Temporary Block (1 hour)]
    EMAIL_FAILED --> SHOW_ERROR
    REDIRECT_DASHBOARD --> END([End])
    BLOCK_TEMP --> END
```

## 8. Data Flow Architecture

```mermaid
graph LR
    subgraph "Client Side"
        USER[User Interface]
        FORM[Forms & Input]
    end
    
    subgraph "Server Side"
        ROUTES[Laravel Routes]
        MIDDLEWARE[Middleware Stack]
        CONTROLLERS[Controllers]
        SERVICES[Service Layer]
        MODELS[Eloquent Models]
    end
    
    subgraph "Database Layer"
        MYSQL[(MySQL)]
        MIGRATIONS[Migrations]
    end
    
    subgraph "External APIs"
        USDA_API[USDA Nutrition API]
        BANTAY_API[Bantay Presyo API]
    end
    
    USER --> FORM
    FORM -->|HTTP Request| ROUTES
    ROUTES --> MIDDLEWARE
    MIDDLEWARE --> CONTROLLERS
    CONTROLLERS --> SERVICES
    SERVICES --> MODELS
    MODELS --> MYSQL
    
    SERVICES --> USDA_API
    SERVICES --> BANTAY_API
    
    MYSQL --> MODELS
    MODELS --> SERVICES
    SERVICES --> CONTROLLERS
    CONTROLLERS -->|HTTP Response| USER
    
    MIGRATIONS --> MYSQL
```

## Technology Stack Summary

### **Frontend**
- **TailwindCSS 4.1.12**: Modern utility-first CSS framework
- **React 19.1.1**: Component-based UI library
- **Blade Templates**: Laravel's templating engine

### **Backend**
- **Laravel 12.25.0**: PHP web application framework
- **PHP 8.2.12**: Server-side scripting language
- **MySQL**: Relational database management system

### **External Integrations**
- **USDA FoodData Central API**: Nutrition information
- **Bantay Presyo API**: Philippine market prices
- **Laravel Queues**: Asynchronous job processing
- **Email Services**: User verification and notifications

### **Development Tools**
- **Laravel Pint 1.24.0**: PHP code style fixer
- **Vite**: Frontend build tool
- **Composer**: PHP dependency management
- **npm**: JavaScript package management