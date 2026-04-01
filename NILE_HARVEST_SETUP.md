# Nile Harvest Agricultural Marketplace API - Setup Guide

## Project Overview
This is a complete Laravel 13 multi-role agricultural marketplace API with the following features:
- User role-based access control (super_admin, vendor, customer)
- Vendor profile management with approval workflow
- Product catalog with categories
- Order management system
- Shopping cart
- Layered architecture (Controller → Service → Repository → Model)
- Laravel Sanctum token-based authentication

## Architecture

### Layered Design
- **Controllers**: Validate requests via FormRequest, call Service, return Transformer responses
- **Services**: Business logic, call Repositories, fire Events, handle exceptions
- **Repositories**: All Eloquent queries, no business logic, implement Interfaces
- **Models**: Relationships and data transformations only
- **Transformers**: Laravel API Resources for consistent response formatting

### Directory Structure
```
app/
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── Auth/
│   │   ├── Admin/
│   │   ├── Vendor/
│   │   └── Customer/
│   ├── Requests/ (Form validation rules)
│   ├── Middleware/
│   └── Resources/ (API Transformers)
├── Services/
├── Repositories/
│   └── Interfaces/
├── Models/
├── Traits/
└── Providers/
```

## Installation Steps

### 1. Install Laravel Sanctum (for API token authentication)
```bash
composer require laravel/sanctum
```

### 2. Publish Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Update `.env` file
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

SANCTUM_STATEFUL_DOMAINS=localhost
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

This will create the following tables:
- users (with role and status)
- vendor_profiles
- categories
- products
- orders
- order_items
- personal_access_tokens (Sanctum)
- password_reset_tokens
- sessions
- cache_table
- jobs_table

### 5. Generate Initial Data (Optional)
Create a seeder to populate sample data:
```bash
# You can use the built-in User factory to create test users
php artisan tinker
# Then run:
# User::factory(10)->create()
```

### 6. Start Development Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api/v1`

## API Endpoints

### Authentication Routes
**Base URL**: `/api/v1/auth`

| Method | Endpoint | Authorization | Description |
|--------|----------|---|---|
| POST | `/register/customer` | Public | Register as customer |
| POST | `/register/vendor` | Public | Register as vendor |
| POST | `/login` | Public | Login user |
| POST | `/logout` | auth:sanctum | Logout user |
| GET | `/me` | auth:sanctum | Get authenticated user |

### Admin Routes
**Base URL**: `/api/v1/admin`
**Authorization**: `role:super_admin`

| Method | Endpoint | Description |
|--------|----------|---|
| GET | `/users` | List all users (paginated) |
| GET | `/users/{id}` | Get user by ID |
| GET | `/users/role/{role}` | Get users by role |
| PATCH | `/users/{id}/suspend` | Suspend user |
| PATCH | `/users/{id}/activate` | Activate user |
| GET | `/vendors/pending` | Get pending vendors |
| GET | `/vendors/approved` | Get approved vendors |
| PATCH | `/vendors/{vendorId}/status` | Approve/suspend vendor |

### Vendor Routes
**Base URL**: `/api/v1/vendor`
**Authorization**: `role:vendor, vendor.approved`

| Method | Endpoint | Description |
|--------|----------|---|
| GET | `/products` | List vendor's products |
| POST | `/products` | Create product |
| GET | `/products/{id}` | Get product details |
| PATCH | `/products/{id}` | Update product |
| DELETE | `/products/{id}` | Delete product |

### Customer Routes
**Base URL**: `/api/v1/customer`
**Authorization**: `role:customer`

**Orders**:
| Method | Endpoint | Description |
|--------|----------|---|
| GET | `/orders` | List customer orders |
| POST | `/orders` | Create order |
| GET | `/orders/{id}` | Get order details |

**Cart**:
| Method | Endpoint | Description |
|--------|----------|---|
| GET | `/cart` | Get cart items |
| POST | `/cart/add` | Add item to cart |
| PATCH | `/cart/update` | Update cart item |
| DELETE | `/cart/{productId}` | Remove item from cart |
| DELETE | `/cart` | Clear cart |

## Authentication

### Getting Auth Token
1. Register or login via `/api/v1/auth/register/customer` or `/api/v1/auth/login`
2. Response will include `token` field
3. Add token to all protected requests:
```
Authorization: Bearer {token}
```

### Example Login Request
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```

## Response Format

All API responses follow this structure:

**Success Response (200)**:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Paginated Response (200)**:
```json
{
  "success": true,
  "message": "Data retrieved",
  "data": [ ... ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "has_more": true
  }
}
```

**Error Response (400/401/403/404/500)**:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error details"]
  }
}
```

## Database Schema

### users
- id, name, email, password, phone
- role (enum: super_admin, vendor, customer)
- status (enum: active, suspended)
- email_verified_at, remember_token
- timestamps, soft_deletes

### vendor_profiles
- id, user_id (FK)
- company_name, commercial_register
- governorate, address, primary_category
- status (enum: pending, approved, suspended)
- timestamps

### categories
- id, name
- parent_id (FK - self-referencing)
- timestamps

### products
- id, vendor_id (FK), category_id (FK)
- name, description, price (decimal 10,2), stock (int)
- unit (enum: kg, liter, piece, box)
- status (enum: active, inactive)
- timestamps, soft_deletes

### orders
- id, customer_id (FK), vendor_id (FK)
- status (enum: pending, confirmed, preparing, picked_up, in_transit, delivered, failed, cancelled)
- subtotal, delivery_fee, commission_rate (all decimal)
- payment_method (enum: cod), notes
- timestamps, soft_deletes

### order_items
- id, order_id (FK), product_id (FK)
- quantity (int), unit_price (decimal 10,2)
- timestamps

## Testing the API

### 1. Create User (Customer)
```bash
curl -X POST http://localhost:8000/api/v1/auth/register/customer \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Farmer",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+201001234567"
  }'
```

### 2. Create Vendor
```bash
curl -X POST http://localhost:8000/api/v1/auth/register/vendor \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ahmad Supplier",
    "email": "ahmad@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+201001234567",
    "company_name": "Ahmad Agriculture",
    "commercial_register": "123456789",
    "governorate": "Cairo",
    "address": "123 Market St",
    "primary_category": "Vegetables"
  }'
```

### 3. Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 4. Create Product (as vendor)
```bash
curl -X POST http://localhost:8000/api/v1/vendor/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "category_id": 1,
    "name": "Fresh Tomatoes",
    "description": "Organic tomatoes from local farm",
    "price": 25.50,
    "stock": 100,
    "unit": "kg"
  }'
```

## Key Features Implemented

✅ Multi-role authentication (super_admin, vendor, customer)
✅ Vendor approval workflow
✅ Product catalog with categories
✅ Order management system
✅ Shopping cart with caching
✅ Layered architecture (Repository Pattern)
✅ Comprehensive API resource formatting
✅ Input validation via FormRequests
✅ Role-based middleware
✅ Tender trait for API responses
✅ Soft deletes for data security
✅ Eloquent relationships
✅ Pagination support

## Customization

### Adding New Roles
1. Update the enum in [roles migration](database/migrations/0001_01_01_000000_create_users_table.php)
2. Add role method to [HasRole trait](app/Traits/HasRole.php)
3. Create new controller in `app/Http/Controllers/Api/V1/{RoleName}/`

### Adding New Endpoints
1. Create FormRequest in `app/Http/Requests/`
2. Add method to Service
3. Create Repository Interface method if needed
4. Create Repository implementation
5. Add Controller method
6. Add route in `routes/api.php`

### Error Handling
The application includes global exception handling in `app/Exceptions/Handler.php`. You can customize error messages and logging there.

## Notes

- All responses are JSON
- Pagination defaults to 15 items per page
- Cart items are stored in cache (7-day expiration)
- Soft deletes are enabled for products and orders
- Stock is automatically decreased when orders are placed
- Token expiration can be configured in `config/sanctum.php`

## Support
For issues or enhancements, refer to:
- Laravel Documentation: https://laravel.com/docs
- Sanctum Docs: https://laravel.com/docs/sanctum
