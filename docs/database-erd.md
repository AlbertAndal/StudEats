# StudEats - Database Entity Relationship Diagram

## Entity Relationship Diagram

```mermaid
erDiagram
    %% Core User Management
    USERS {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        text dietary_preferences JSON
        decimal daily_budget
        varchar age
        varchar gender
        varchar activity_level
        decimal height
        varchar height_unit
        decimal weight
        varchar weight_unit
        varchar role
        boolean is_active
        timestamp suspended_at
        varchar suspended_reason
        varchar timezone
        varchar profile_photo
        timestamp email_verified_at
        timestamp first_login_at
        int login_count
        timestamp last_login_at
        timestamp created_at
        timestamp updated_at
    }

    %% Email Verification System
    EMAIL_VERIFICATION_OTPS {
        bigint id PK
        varchar email
        varchar otp_code
        varchar verification_token
        timestamp expires_at
        boolean is_used
        timestamp used_at
        varchar ip_address
        text user_agent
        timestamp created_at
        timestamp updated_at
    }

    %% Meal and Recipe System
    MEALS {
        bigint id PK
        varchar name
        text description
        int calories
        decimal cost
        varchar cuisine_type
        enum difficulty
        enum meal_type
        varchar image_path
        boolean is_featured
        timestamp created_at
        timestamp updated_at
    }

    RECIPES {
        bigint id PK
        bigint meal_id FK
        json ingredients
        text instructions
        int prep_time
        int cook_time
        int servings
        json local_alternatives
        timestamp created_at
        timestamp updated_at
    }

    NUTRITIONAL_INFO {
        bigint id PK
        bigint meal_id FK UK
        decimal calories
        decimal protein
        decimal carbs
        decimal fats
        decimal fiber
        decimal sugar
        decimal sodium
        timestamp created_at
        timestamp updated_at
    }

    %% Meal Planning System
    MEAL_PLANS {
        bigint id PK
        bigint user_id FK
        bigint meal_id FK
        date scheduled_date
        enum meal_type
        boolean is_completed
        text notes
        tinyint servings
        enum prep_reminder
        timestamp created_at
        timestamp updated_at
    }

    %% Ingredient and Pricing System
    INGREDIENTS {
        bigint id PK
        varchar name
        varchar bantay_presyo_name
        varchar unit
        varchar common_unit
        enum category
        int bantay_presyo_commodity_id
        decimal current_price
        varchar price_source
        timestamp price_updated_at
        boolean is_active
        json alternative_names
        text description
        timestamp created_at
        timestamp updated_at
    }

    INGREDIENT_PRICE_HISTORY {
        bigint id PK
        bigint ingredient_id FK
        decimal price
        varchar price_source
        varchar region_code
        timestamp recorded_at
        json raw_data
        timestamp created_at
        timestamp updated_at
    }

    RECIPE_INGREDIENTS {
        bigint id PK
        bigint recipe_id FK
        bigint ingredient_id FK
        decimal quantity
        varchar unit
        decimal estimated_cost
        text notes
        timestamp created_at
        timestamp updated_at
    }

    %% Nutrition Reference Data
    PDRI_REFERENCES {
        bigint id PK
        varchar gender
        int age_min
        int age_max
        varchar activity_level
        int energy_kcal
        decimal protein_g
        decimal carbohydrates_g
        decimal total_fat_g
        decimal fiber_g
        decimal sodium_mg
        decimal sugar_g
        timestamp created_at
        timestamp updated_at
    }

    %% Admin and Logging System
    ADMIN_LOGS {
        bigint id PK
        bigint admin_user_id FK
        varchar action
        varchar target_type
        bigint target_id
        text description
        json metadata
        varchar ip_address
        text user_agent
        timestamp created_at
        timestamp updated_at
    }

    ACTIVITY_LOGS {
        bigint id PK
        bigint user_id FK
        varchar event
        varchar description
        json properties
        varchar ip_address
        text user_agent
        timestamp created_at
        timestamp updated_at
    }

    %% System Tables
    SESSIONS {
        varchar id PK
        bigint user_id
        varchar ip_address
        text user_agent
        text payload
        int last_activity
    }

    JOBS {
        bigint id PK
        varchar queue
        text payload
        tinyint attempts
        int reserved_at
        int available_at
        int created_at
    }

    FAILED_JOBS {
        bigint id PK
        varchar uuid UK
        text connection
        text queue
        text payload
        text exception
        timestamp failed_at
    }

    CACHE {
        varchar key PK
        text value
        int expiration
    }

    PASSWORD_RESET_TOKENS {
        varchar email PK
        varchar token
        timestamp created_at
    }

    %% Relationships
    USERS ||--o{ MEAL_PLANS : "has"
    USERS ||--o{ ACTIVITY_LOGS : "generates"
    USERS ||--o{ ADMIN_LOGS : "performs"

    MEALS ||--|| RECIPES : "has"
    MEALS ||--|| NUTRITIONAL_INFO : "has"
    MEALS ||--o{ MEAL_PLANS : "used_in"

    RECIPES ||--o{ RECIPE_INGREDIENTS : "contains"
    
    INGREDIENTS ||--o{ RECIPE_INGREDIENTS : "used_in"
    INGREDIENTS ||--o{ INGREDIENT_PRICE_HISTORY : "has_history"

    %% Constraints and Indexes
    %% MEAL_PLANS has unique constraint on (user_id, scheduled_date, meal_type)
    %% RECIPE_INGREDIENTS has unique constraint on (recipe_id, ingredient_id)
```

## Database Relationships Explanation

### **Core Relationships**

#### **User-Centric Relationships**
- **Users** → **Meal Plans**: One-to-Many (A user can have multiple meal plans)
- **Users** → **Activity Logs**: One-to-Many (User activities are tracked)
- **Users** → **Admin Logs**: One-to-Many (Admin actions are logged)

#### **Meal System Relationships**
- **Meals** → **Recipes**: One-to-One (Each meal has one recipe)
- **Meals** → **Nutritional Info**: One-to-One (Each meal has nutrition data)
- **Meals** → **Meal Plans**: One-to-Many (A meal can be used in multiple plans)

#### **Recipe and Ingredient Relationships**
- **Recipes** → **Recipe Ingredients**: One-to-Many (A recipe has multiple ingredients)
- **Ingredients** → **Recipe Ingredients**: One-to-Many (An ingredient is used in multiple recipes)
- **Ingredients** → **Ingredient Price History**: One-to-Many (Price tracking over time)

### **Key Business Rules**

#### **Data Integrity**
1. **Unique Meal Plans**: A user cannot have duplicate meal plans for the same date and meal type
2. **Recipe-Ingredient Uniqueness**: A recipe cannot have the same ingredient listed twice
3. **Email Uniqueness**: Each email address can only be associated with one user account

#### **Referential Integrity**
- Deleting a user cascades to their meal plans and activity logs
- Deleting a meal cascades to its recipe, nutritional info, and meal plans
- Deleting an ingredient cascades to its price history and recipe associations

#### **Data Validation**
- `dietary_preferences` must be valid JSON
- `alternative_names` in ingredients must be valid JSON
- `properties` in activity logs must be valid JSON
- `metadata` in admin logs must be valid JSON

### **Indexing Strategy**

#### **Performance Indexes**
- **Users**: `email` (unique), for login authentication
- **Meal Plans**: `user_id + scheduled_date` for calendar queries
- **Ingredients**: `category + is_active` for filtering active ingredients
- **Price History**: `ingredient_id + recorded_at` for price trend analysis
- **Admin Logs**: `admin_user_id + created_at` for admin activity tracking

#### **Query Optimization**
- **Featured Meals**: `is_featured` index for homepage queries
- **Price Updates**: `price_updated_at` index for scheduling updates
- **Session Management**: `user_id` and `last_activity` indexes for active session tracking

### **Data Security Considerations**

#### **Sensitive Data**
- **Passwords**: Hashed using Laravel's bcrypt
- **Personal Health Data**: BMI calculations, dietary preferences
- **Email Verification**: OTP codes with 5-minute expiration

#### **Audit Trail**
- **Admin Actions**: Complete audit trail in `admin_logs`
- **User Activity**: Tracked in `activity_logs` for analytics
- **Login Tracking**: First login, login count, and last login timestamps

#### **Data Retention**
- **OTP Cleanup**: Expired OTPs are automatically cleaned
- **Session Management**: Old sessions are cleaned periodically
- **Price History**: Maintained for trend analysis and reporting

### **Scalability Design**

#### **Caching Strategy**
- **Nutrition Data**: Cached for 24 hours to reduce API calls
- **Price Data**: Cached for efficient recipe cost calculations
- **Session Data**: Stored in cache for faster authentication

#### **Queue System**
- **Email Verification**: Queued for asynchronous processing
- **Price Updates**: Scheduled jobs for market data synchronization
- **Nutrition Calculations**: Background processing for complex calculations

This database design supports the full functionality of StudEats while maintaining data integrity, performance, and scalability for future growth.