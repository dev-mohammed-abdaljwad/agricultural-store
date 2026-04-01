# Nile Harvest API - Final Setup Checklist

## ✅ Completed by Code Generation
- [x] Database Models (User, VendorProfile, Category, Product, Order, OrderItem)
- [x] Database Migrations (all tables with relationships)
- [x] Service Layer (AuthService, UserService, VendorService, ProductService, OrderService, CartService)
- [x] Repository Pattern (Interfaces + Implementations)
- [x] API Controllers (Auth, Admin, Vendor, Customer)
- [x] Form Requests (Validation for all endpoints)
- [x] API Resources/Transformers (Consistent response formatting)
- [x] Middleware (RoleMiddleware, EnsureVendorApproved)
- [x] Traits (ApiResponseTrait, HasRole)
- [x] Service Provider (RepositoryServiceProvider)
- [x] API Routes (/api/v1 with versioning)
- [x] Bootstrap Configuration (middleware aliases)
- [x] Folder Structure
- [x] Documentation (NILE_HARVEST_SETUP.md, API_USAGE_EXAMPLES.md)

## ⚠️ MUST DO - Installation Steps

### Step 1: Install Laravel Sanctum (REQUIRED for authentication)
```bash
cd c:\Users\AYAD_GROUP_STORE\agricultural-store
composer require laravel/sanctum
```

### Step 2: Publish Sanctum Config
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 3: Create Database
```bash
# Update your .env file with database credentials first
# DB_DATABASE=nile_harvest
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Then create the database in MySQL:
# CREATE DATABASE nile_harvest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 4: Run Migrations
```bash
php artisan migrate
```

**This will create:**
- users table (with role and status enums)
- vendor_profiles table
- categories table
- products table
- orders table
- order_items table
- personal_access_tokens table (Sanctum)
- password_reset_tokens table
- sessions table
- cache table (for cart)
- jobs table

### Step 5: (Optional) Seed Sample Data
```bash
# Create a seeder with sample categories, users, and products
php artisan make:seeder CategorySeeder
php artisan make:seeder UserSeeder
php artisan make:seeder ProductSeeder

# Then run: php artisan db:seed
```

### Step 6: Create Super Admin Account
```bash
php artisan tinker
```

Then run in Tinker:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@nile-harvest.com',
    'password' => Hash::make('SecureAdminPassword123'),
    'phone' => '+201001234567',
    'role' => 'super_admin',
    'status' => 'active'
]);
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Server will run at: **http://localhost:8000**
API Base URL: **http://localhost:8000/api/v1**

---

## ✨ Quick Test After Setup

1. **Register as Customer**:
```bash
curl -X POST http://localhost:8000/api/v1/auth/register/customer \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "TestPass123",
    "password_confirmation": "TestPass123",
    "phone": "+201234567890"
  }'
```

2. **Login** (use the token from response above):
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "TestPass123"
  }'
```

3. **Get Current User**:
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📋 Configuration Files Generated

### .env Configuration
Add these to your `.env`:
```env
APP_NAME="Nile Harvest"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nile_harvest
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### Required Environment Variables
```env
APP_KEY=base64:xxxxx (generated with php artisan key:generate)
SANCTUM_STATEFUL_DOMAINS=localhost
```

---

## 🔍 File Structure Verification

Run these commands to verify structure:

```bash
# Check Models exist
ls -la app/Models/

# Check Services exist
ls -la app/Services/

# Check Repositories exist
ls -la app/Repositories/
ls -la app/Repositories/Interfaces/

# Check Controllers exist
ls -la app/Http/Controllers/Api/V1/

# Check Requests exist
ls -la app/Http/Requests/

# Check Resources exist
ls -la app/Http/Resources/

# Check Middleware exist
ls -la app/Http/Middleware/

# Check routes exist
ls -la routes/
```

All should return files and directories.

---

## 🧪 Recommended Testing Order

### 1. Test Authentication Flow
- [ ] Register as Customer
- [ ] Register as Vendor
- [ ] Login
- [ ] Get Current User
- [ ] Logout

### 2. Test Admin Functions
- [ ] Create super_admin user manually
- [ ] Login as admin
- [ ] Get all users
- [ ] Get users by role
- [ ] Get pending vendors
- [ ] Approve a vendor

### 3. Test Vendor Functions
- [ ] Create categories (via seeder or manually)
- [ ] Create products
- [ ] Update products
- [ ] Delete products
- [ ] Get vendor products

### 4. Test Customer Functions
- [ ] Add items to cart
- [ ] View cart
- [ ] Create order
- [ ] Get orders
- [ ] View order details

---

## 🚀 Performance Optimization (Optional)

After initial setup:

```bash
# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Optimize auto-loader
composer install --optimize-autoloader --no-dev
```

For development:
```bash
# Clear caches
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

---

## 📝 Important Notes

1. **Sanctum Installation**: Without this, API token authentication won't work
2. **Database Migrations**: Must run AFTER Sanctum is installed
3. **Enum Support**: MySQL 8.0.17+ required for enum columns
4. **Token Format**: Sanctum tokens are in format: `{ID}|{TOKEN}`
5. **CORS**: Configure in config/cors.php if calling from frontend
6. **Rate Limiting**: Configure in app/Http/Middleware/

---

## 📚 Additional Resources Included

- **NILE_HARVEST_SETUP.md**: Comprehensive overview and API documentation
- **API_USAGE_EXAMPLES.md**: curl commands for testing all endpoints
- **This file**: Setup checklist and verification steps

---

## ⚡ Quick Reference - Key Commands

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration + seeding
php artisan migrate:fresh --seed

# View routes
php artisan route:list

# Interactive shell (for testing)
php artisan tinker

# Start server
php artisan serve

# Run tests (when available)
php artisan test
```

---

## ❓ Troubleshooting

**Error: SQLSTATE[42S01]: Table 'xyz' doesn't exist**
- Run: `php artisan migrate`

**Error: Class 'Laravel\Sanctum\...' not found**
- Run: `composer require laravel/sanctum`
- Run: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

**Error: "Vendor account is not approved yet"**
- This is expected before admin approval. Admin must approve vendor status.

**Error: Token invalid or expired**
- Check token format and that it hasn't expired (default: 365 days)
- Regenerate token by registering/logging in again

**Error: CORS issues with frontend**
- Configure in config/cors.php
- Add frontend domain to SANCTUM_STATEFUL_DOMAINS

---

## Next Steps

1. ✅ Complete setup checklist above
2. ✅ Test authentication flow
3. ✅ Test all endpoints with provided curl commands
4. ✅ Create seed data (categories, sample products)
5. ✅ Deploy to production (with proper .env configuration)
6. ✅ Set up error monitoring (e.g., Sentry)
7. ✅ Configure email notifications
8. ✅ Set up payment gateway (future enhancement)

---

**Last Updated**: March 29, 2026
**Laravel Version**: 13.0+
**PHP Version**: 8.3+
**Database**: MySQL 8.0.17+
