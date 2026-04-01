# Nile Harvest - Complete API Setup & Deployment Guide

## 🚀 Quick Start

### Prerequisites
- PHP 8.3 or higher
- Composer
- MySQL 8.0+
- Git (optional)

### Installation Steps

#### 1. Navigate to Project Directory
```bash
cd c:\Users\AYAD\ GROUP\ STORE\agricultural-store
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Setup Environment File
```bash
copy .env.example .env
```

Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nile_harvest
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Generate Application Key
```bash
php artisan key:generate
```

#### 5. Create Database
```bash
# Using MySQL directly
mysql -u root -p
CREATE DATABASE nile_harvest;
EXIT;
```

#### 6. Run Database Migrations
```bash
php artisan migrate
```

#### 7. Seed Sample Data
```bash
php artisan db:seed
```

This will create:
- 1 Admin user
- 3 Sample customers
- 4 Product categories
- 3 Products with images and specifications

#### 8. Start Development Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api/v1`

---

## 📊 Default Test Users

### Admin Account
- **Email:** `admin@nileharvest.com`
- **Password:** `password`
- **Role:** admin

### Customer Accounts
| Name | Email | Password | Type |
|------|-------|----------|------|
| Ahmed Hassan | ahmed@example.com | password | farmer |
| Fatima Mohamed | fatima@example.com | password | trader |
| Ibrahim Khalil | ibrahim@example.com | password | farmer |

---

## 🧪 Testing the API

### Option 1: Using REST Client Extension (VS Code)
1. Install "REST Client" extension
2. Open `/test-api-examples.http`
3. Set variables at the top of the file
4. Click "Send Request" on any endpoint

### Option 2: Using cURL
```bash
# Get products list
curl -X GET http://localhost:8000/api/v1/products

# Login as customer
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ahmed@example.com",
    "password": "password"
  }'

# Place an order (replace TOKEN with actual token)
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "items": [{"product_id": 1, "quantity": 10}],
    "delivery_address": "123 Farm Rd",
    "delivery_governorate": "Giza"
  }'
```

### Option 3: Using Postman
1. Open Postman
2. Import: `Nile_Harvest_Auth_Collection.postman_collection.json`
3. Configure environment variables for `{{baseUrl}}` and `{{token}}`
4. Start testing

### Option 4: Using Insomnia or Thunder Client
- Import OpenAPI/Swagger specification if available
- Or manually create requests based on documentation

---

## 🗄️ Database Schema Overview

### Core Tables
- **users** - Admin and customer accounts
- **categories** - Product categories
- **products** - Product catalog (no pricing/stock)
- **product_images** - Product images with sort order
- **product_specs** - Dynamic product specifications
- **orders** - Customer orders
- **order_items** - Products in each order
- **pricing_quotes** - Price quotes from admin
- **pricing_quote_items** - Individual items in quotes
- **order_tracking** - Order status history
- **conversations** - Chat conversations per order
- **messages** - Individual messages in conversations

### Supporting Tables
- **categories** - Root categories (no vendor concept)
- **personal_access_tokens** - Sanctum API tokens
- **password_reset_tokens** - Password reset tokens
- **sessions** - Session data

---

## 📁 Project Structure

```
agricultural-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/V1/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── ChatController.php
│   │   │   ├── Admin/
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── UserController.php
│   │   │   └── Customer/
│   │   │       └── OrderController.php
│   │   ├── Requests/ - Form validation classes
│   │   ├── Resources/ - API response transformers
│   │   └── Middleware/
│   ├── Models/ - Eloquent models
│   ├── Services/ - Business logic
│   ├── Repositories/ - Data access layer
│   ├── Traits/ - Reusable functionality
│   ├── Jobs/ - Background jobs
│   └── Providers/ - Service providers
├── database/
│   ├── migrations/ - Database schema
│   └── seeders/ - Test data
├── routes/
│   ├── api.php - API routes
│   └── web.php - Web routes
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── ...
├── API_DOCUMENTATION.md - Complete API reference
├── test-api-examples.http - HTTP test examples
├── IMPLEMENTATION_SUMMARY.md - Implementation overview
└── README.md
```

---

## 🔐 Security Features

✅ **Implemented:**
- Laravel Sanctum for API authentication
- Form request validation on all endpoints
- Role-based access control (RBAC)
- Middleware for authorization checks
- Hash password storage
- SQL injection protection (Eloquent ORM)
- CSRF tokens (for web routes)
- Soft deletes for data recovery
- Request throttling (can be added)

⚠️ **Recommended for Production:**
- Enable HTTPS/SSL
- Set `APP_DEBUG=false` in `.env`
- Use environment variables for sensitive data
- Implement rate limiting
- Add API key rotation mechanism
- Monitor logs for suspicious activity
- Regular security updates
- Backup database regularly

---

## 🛠️ Development Commands

```bash
# Database
php artisan migrate                # Run migrations
php artisan migrate:rollback       # Undo last migration
php artisan migrate:refresh        # Reset and re-run migrations
php artisan db:seed                # Seed sample data

# Code Generation
php artisan make:model Product             # Create model
php artisan make:controller ProductController  # Create controller
php artisan make:request StoreProductRequest   # Create form request
php artisan make:resource ProductResource      # Create API resource

# Testing
php artisan test                   # Run tests
php artisan test --filter=ProductTest  # Run specific tests

# Debugging
php artisan tinker                 # Interactive shell
php artisan route:list             # Show all routes
php artisan config:cache           # Cache configuration

# Cache
php artisan cache:clear            # Clear application cache
php artisan config:clear           # Clear config cache
php artisan view:clear             # Clear view cache

# Logs
tail -f storage/logs/laravel.log   # Watch log file (Unix/Mac)
Get-Content storage\logs\laravel.log -Tail 100 -Wait  # Watch logs (Windows)
```

---

## 🌐 API Endpoints Overview

### Authentication (Public)
- `POST /auth/register` - Register customer
- `POST /auth/login` - Login
- `POST /auth/logout` - Logout (requires auth)
- `GET /auth/me` - Get current user (requires auth)

### Products (Public)
- `GET /products` - List all products
- `GET /products/{id}` - Get single product
- `GET /categories` - List categories

### Orders (Customer - requires auth)
- `POST /orders` - Create order
- `GET /orders` - List customer's orders
- `GET /orders/{id}` - Get single order
- `POST /orders/{id}/quotes/{quote_id}/accept` - Accept quote
- `POST /orders/{id}/quotes/{quote_id}/reject` - Reject quote

### Messages (Customer - requires auth)
- `GET /orders/{id}/messages` - Get conversation messages
- `POST /orders/{id}/messages` - Send message

### Admin Products (requires admin role)
- `GET /admin/products` - List products with supplier info
- `POST /admin/products` - Create product
- `PUT /admin/products/{id}` - Update product
- `DELETE /admin/products/{id}` - Delete product
- `POST /admin/products/{id}/images` - Add images
- `PUT /admin/products/{id}/specs` - Sync specifications

### Admin Orders (requires admin role)
- `GET /admin/orders` - List all orders
- `GET /admin/orders/{id}` - Get order details
- `POST /admin/orders/{id}/quotes` - Send quote
- `PATCH /admin/orders/{id}/status` - Update status
- `GET /admin/orders/{id}/tracking` - Get tracking history

### Admin Conversations (requires admin role)
- `GET /admin/conversations` - List all conversations

---

## 📊 Response Examples

### Success Response (Paginated)
```json
{
  "success": true,
  "message": "Products retrieved successfully",
  "data": [...],
  "pagination": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4,
    "has_more": true
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email field is required"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

---

## 🚀 Deployment Checklist

- [ ] Copy `.env.example` to `.env`
- [ ] Update `.env` with production database credentials
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan db:seed` (optional, for test data)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Configure web server (Nginx/Apache)
- [ ] Setup SSL certificate (HTTPS)
- [ ] Configure mail driver if needed
- [ ] Setup background job queue (optional)
- [ ] Configure logging and monitoring
- [ ] Backup database
- [ ] Test all endpoints

---

## 📝 File Structure - Key Files Created/Modified

### Controllers (7 total)
- `App/Http/Controllers/Api/V1/Auth/AuthController.php` ✅
- `App/Http/Controllers/Api/V1/ProductController.php` ✅
- `App/Http/Controllers/Api/V1/CategoryController.php` ✅
- `App/Http/Controllers/Api/V1/ChatController.php` ✅
- `App/Http/Controllers/Api/V1/Admin/ProductController.php` ✅
- `App/Http/Controllers/Api/V1/Admin/OrderController.php` ✅
- `App/Http/Controllers/Api/V1/Customer/OrderController.php` ✅

### Services (8 total)
- `App/Services/AuthService.php` ✅
- `App/Services/ProductService.php` ✅
- `App/Services/OrderService.php` ✅
- `App/Services/QuoteService.php` ✅
- `App/Services/ChatService.php` ✅
- `App/Services/OrderTrackingService.php` ✅
- `App/Services/UserService.php` ✅
- `App/Services/VendorService.php` ✅

### Repositories (4 interfaces + 4 implementations)
- `App/Repositories/UserRepository.php` ✅
- `App/Repositories/ProductRepository.php` ✅
- `App/Repositories/OrderRepository.php` ✅
- `App/Repositories/VendorRepository.php` ✅

### API Resources (11 total)
- `App/Http/Resources/UserResource.php` ✅
- `App/Http/Resources/ProductResource.php` ✅
- `App/Http/Resources/ProductAdminResource.php` ✅
- `App/Http/Resources/OrderResource.php` ✅
- `App/Http/Resources/OrderItemResource.php` ✅
- `App/Http/Resources/CategoryResource.php` ✅
- `App/Http/Resources/PricingQuoteResource.php` ✅
- `App/Http/Resources/PricingQuoteItemResource.php` ✅
- `App/Http/Resources/OrderTrackingResource.php` ✅
- `App/Http/Resources/ConversationResource.php` ✅
- `App/Http/Resources/MessageResource.php` ✅

### Models (13 total)
- `App/Models/User.php` ✅
- `App/Models/Product.php` ✅
- `App/Models/ProductImage.php` ✅
- `App/Models/ProductSpec.php` ✅
- `App/Models/Category.php` ✅
- `App/Models/Order.php` ✅
- `App/Models/OrderItem.php` ✅
- `App/Models/PricingQuote.php` ✅
- `App/Models/PricingQuoteItem.php` ✅
- `App/Models/OrderTracking.php` ✅
- `App/Models/Conversation.php` ✅
- `App/Models/Message.php` ✅
- `App/Models/VendorProfile.php` ✅

### Form Requests (7 request classes)
- `App/Http/Requests/Auth/*.php` ✅
- `App/Http/Requests/Order/*.php` ✅
- `App/Http/Requests/Product/*.php` ✅
- `App/Http/Requests/Chat/*.php` ✅
- `App/Http/Requests/Admin/*.php` ✅

### Migrations (11 total)
- All database schema migrations ✅

### Traits
- `App/Traits/ApiResponseTrait.php` ✅
- `App/Traits/HasRole.php` ✅

### Middleware
- `App/Http/Middleware/RoleMiddleware.php` ✅
- `App/Http/Middleware/EnsureVendorApproved.php` ✅

---

## 📚 Documentation Files

All documentation is in the root directory:

- `API_DOCUMENTATION.md` - Complete API reference (this file)
- `test-api-examples.http` - REST Client examples
- `IMPLEMENTATION_SUMMARY.md` - Technical implementation details
- `SETUP_CHECKLIST.md` - Setup verification checklist

---

## 🤝 Support & Troubleshooting

### Common Issues

**1. Database connection error**
```
Error: SQLSTATE[HY000]: General error: 1030 Got error 28...
```
- Check MySQL is running: `mysql -u root -p`
- Verify credentials in `.env`
- Create database if missing

**2. Port 8000 already in use**
```bash
# Use different port
php artisan serve --port=8001
```

**3. Cache/Config issues**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:cache --clear
```

**4. Migrations fail**
```bash
# Refresh everything
php artisan migrate:refresh --seed
```

**5. Permissions error**
```bash
# Fix permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

---

## 📞 API Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Success |
| 201 | Created - Resource created |
| 204 | No Content - Success with no response |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Missing authentication |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error |

---

## 🎯 Next Steps

1. ✅ Setup development environment
2. ✅ Run migrations and seeders
3. ✅ Start development server
4. ✅ Test endpoints with provided examples
5. ✅ Review API documentation
6. ✅ Customize for production (SSL, domain, etc.)
7. ✅ Deploy to production server

---

**Last Updated:** March 2026  
**Laravel Version:** 11  
**PHP Version:** 8.3+
