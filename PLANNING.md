# GB-Scheduler V2 Analysis

## Current Database Structure

### Core Tables (Existing)
- `users` - coaches, admins, managers
- `locations` - Davenport, Celebration
- `class_templates` - class schedules
- `user_locations` - coach-location mapping
- `schedule_events` - generated schedules
- `schedule_locks` - locked weeks
- `private_classes` - private lesson bookings
- `private_rates` - coach rates per location

### Payments (Existing)
- `coach_payments` - payment tracking
- `user_conversions` - student conversions
- `user_deductions` - deductions
- `gb_expenses` - expense tracking
- `gb_members`, `gb_holds`, `gb_revenue`, `gb_cancellations` - from ZenPlanner import

### Inventory (Existing)
- `product_categories`
- `products`
- `inventory_counts`
- `order_requests`

### Auth (Existing)
- `password_resets`

---

## V2 - What We Need to Add

### 1. Multi-Tenancy (NEW)
```sql
-- Organizations/Gym Owners
CREATE TABLE organizations (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    slug VARCHAR(100) UNIQUE,
    plan ENUM('starter', 'pro', 'business'),
    trial_ends_at DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP
);

-- Add to existing tables
ALTER TABLE users ADD COLUMN organization_id INT;
ALTER TABLE locations ADD COLUMN organization_id INT;
```

### 2. Plans & Subscriptions (NEW)
```sql
CREATE TABLE plans (
    id INT PRIMARY KEY,
    name VARCHAR(50), -- starter, pro, business
    price DECIMAL(10,2),
    max_locations INT,
    max_coaches INT,
    features JSON
);

CREATE TABLE subscriptions (
    id INT PRIMARY KEY,
    organization_id INT,
    plan_id INT,
    stripe_customer_id VARCHAR(255),
    stripe_subscription_id VARCHAR(255),
    status ENUM('active', 'trialing', 'cancelled'),
    current_period_end DATETIME
);
```

### 3. Permissions (NEW)
```sql
CREATE TABLE roles (
    id INT PRIMARY KEY,
    name VARCHAR(50), -- owner, manager, coach, staff
    permissions JSON
);
```

### 4. Leads Module (NEW - Priority)
```sql
CREATE TABLE leads (
    id INT PRIMARY KEY,
    organization_id INT,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    source VARCHAR(100), -- instagram, referral, etc
    status ENUM('new', 'contacted', 'scheduled', 'converted', 'lost'),
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE lead_schedules (
    id INT PRIMARY KEY,
    lead_id INT,
    scheduled_at DATETIME,
    confirmed BOOLEAN DEFAULT FALSE,
    reminder_sent BOOLEAN DEFAULT FALSE
);
```

---

## V2 Feature Roadmap

### Phase 1: Multi-User + Plans
1. Add organizations table
2. Add plans table
3. Add subscription tracking
4. Update users to belong to org
5. Add permission system

### Phase 2: Lead Management
1. Leads table
2. Lead scheduling
3. Confirmation emails
4. Reminder cron

### Phase 3: Payments
1. Stripe integration
2. Subscription billing
3. Invoice generation

---

## Stack Decision
- Laravel + Vue.js + Inertia
- Laravel Reverb for real-time
- Stripe for payments
- DigitalOcean for hosting
