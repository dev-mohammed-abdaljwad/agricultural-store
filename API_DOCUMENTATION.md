# Nile Harvest - Agricultural Marketplace API Documentation

## 📋 Overview

Nile Harvest is a single-vendor agricultural marketplace API built with Laravel 11. It enables farmers and traders to browse and request agricultural products, while the admin/owner manages quotes, orders, and payments.

**API Version:** v1  
**Base URL:** `http://localhost:8000/api/v1`  
**Authentication:** Laravel Sanctum (API Tokens)

## 🏗️ Architecture

- **Controllers** → HTTP request handling and validation (FormRequest)
- **Services** → Business logic and coordination
- **Repositories** → Database queries and data persistence
- **Models** → Data structure and relationships
- **Resources** → Response transformation

## 📦 Response Format

All API responses follow this standard format:

```json
{
  "success": true,
  "message": "Success message",
  "data": {...} or [...],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "has_more": true
  }
}
```

## 🔐 Authentication

### Register as Customer

**Endpoint:** `POST /auth/register`

**Request:**
```json
{
  "name": "Ahmed Hassan",
  "email": "ahmed@example.com",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123",
  "phone": "+201001234567",
  "customer_type": "farmer",
  "governorate": "Giza",
  "address": "123 Main Street, Giza"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Customer registered successfully.",
  "data": {
    "user": {
      "id": 1,
      "name": "Ahmed Hassan",
      "email": "ahmed@example.com",
      "phone": "+201001234567",
      "role": "customer",
      "status": "active",
      "created_at": "2024-03-31T12:00:00Z"
    },
    "token": "1|your-api-token-here"
  }
}
```

### Login

**Endpoint:** `POST /auth/login`

**Request:**
```json
{
  "email": "ahmed@example.com",
  "password": "SecurePassword123"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Logged in successfully.",
  "data": {
    "user": {...},
    "token": "1|your-api-token-here"
  }
}
```

### Get Current User

**Endpoint:** `GET /auth/me`  
**Authentication:** Required

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Ahmed Hassan",
      "email": "ahmed@example.com",
      "phone": "+201001234567",
      "role": "customer",
      "status": "active"
    }
  }
}
```

### Logout

**Endpoint:** `POST /auth/logout`  
**Authentication:** Required

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Logged out successfully.",
  "data": {}
}
```

---

## 📦 Products (Public)

### List Products

**Endpoint:** `GET /products`

**Query Parameters:**
- `per_page` (integer, default: 15) - Items per page
- `page` (integer, default: 1) - Page number
- `category_id` (integer) - Filter by category
- `search` (string) - Search by name or description

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Egyptian Tomatoes",
      "description": "Fresh, high-quality Egyptian tomatoes",
      "unit": "kg",
      "min_order_qty": 5,
      "is_certified": true,
      "data_sheet_url": "https://example.com/tomato.pdf",
      "usage_instructions": "Use fresh for salads",
      "safety_instructions": "Wash before use",
      "manufacturer_info": "Grown in Nile Delta",
      "expert_tip": "Best used within 3 days",
      "expert_name": "Dr. Ahmed",
      "expert_title": "Agricultural Expert",
      "expert_image_url": "https://example.com/expert.jpg",
      "status": "active",
      "category": {
        "id": 1,
        "name": "Vegetables"
      },
      "primary_image": {
        "id": 1,
        "url": "https://example.com/tomato.jpg"
      },
      "images": [
        {
          "id": 1,
          "url": "https://example.com/tomato-1.jpg",
          "is_primary": true,
          "sort_order": 1
        }
      ],
      "specs": [
        {
          "id": 1,
          "key": "Origin",
          "value": "Egypt",
          "sort_order": 1
        }
      ],
      "created_at": "2024-03-31T12:00:00Z",
      "updated_at": "2024-03-31T12:00:00Z"
    }
  ],
  "pagination": {
    "total": 15,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "has_more": false
  }
}
```

**Notes:**
- `supplier_name` and `supplier_code` are hidden from customer responses
- Only active products are shown

### Get Single Product

**Endpoint:** `GET /products/{id}`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Egyptian Tomatoes",
    ...
  }
}
```

---

## 📂 Categories (Public)

### List All Categories

**Endpoint:** `GET /categories`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Vegetables",
      "parent_id": null,
      "icon": null,
      "is_active": true,
      "subcategories": []
    }
  ]
}
```

---

## 🛒 Orders (Customer)

### Place Order

**Endpoint:** `POST /orders`  
**Authentication:** Required (customer)

**Request:**
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 10
    },
    {
      "product_id": 2,
      "quantity": 5
    }
  ],
  "delivery_address": "123 Farm Road, Giza",
  "delivery_governorate": "Giza",
  "payment_method": "cod"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Order placed successfully.",
  "data": {
    "id": 1,
    "order_number": "NH-2024-0001",
    "customer_id": 1,
    "status": "placed",
    "delivery_fee": 0,
    "total_amount": null,
    "payment_method": "cod",
    "payment_status": "pending",
    "delivery_address": "123 Farm Road, Giza",
    "delivery_governorate": "Giza",
    "customer": {...},
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 10,
        "unit_price": null,
        "total_price": null,
        "product": {...}
      }
    ],
    "active_quote": null,
    "quotes": [],
    "tracking": [
      {
        "id": 1,
        "status": "placed",
        "title": "تم استلام طلبك",
        "description": null,
        "occurred_at": "2024-03-31T12:00:00Z"
      }
    ],
    "conversation": {
      "id": 1,
      "order_id": 1,
      "customer_id": 1,
      "last_message_at": null,
      "messages": []
    },
    "created_at": "2024-03-31T12:00:00Z"
  }
}
```

**Validation Rules:**
- `items` - Required, array of at least 1 item
- `items.*.product_id` - Required, must exist in products table
- `items.*.quantity` - Required, integer, minimum 1, must meet product's min_order_qty
- `delivery_address` - Required, max 500 characters
- `delivery_governorate` - Required, max 100 characters
- `payment_method` - Optional, values: `cod`, `online` (default: `cod`)

### List Customer Orders

**Endpoint:** `GET /orders`  
**Authentication:** Required (customer)

**Query Parameters:**
- `per_page` - Items per page (default: 15)
- `page` - Page number

**Response:** `200 OK` - Returns paginated list of orders

### Get Single Order

**Endpoint:** `GET /orders/{order_id}`  
**Authentication:** Required (customer)

**Response:** `200 OK`

### Accept Quote

**Endpoint:** `POST /orders/{order_id}/quotes/{quote_id}/accept`  
**Authentication:** Required (customer)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Quote accepted successfully.",
  "data": {
    "id": 1,
    "order_number": "NH-2024-0001",
    "status": "quote_accepted",
    "total_amount": 1500.00,
    "delivery_fee": 25.00,
    ...
  }
}
```

### Reject Quote

**Endpoint:** `POST /orders/{order_id}/quotes/{quote_id}/reject`  
**Authentication:** Required (customer)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Quote rejected. Admin will send a new quote.",
  "data": {
    "id": 1,
    "status": "quote_pending",
    ...
  }
}
```

---

## 💬 Messages/Chat (Customer)

### Get Order Messages

**Endpoint:** `GET /orders/{order_id}/messages`  
**Authentication:** Required

**Query Parameters:**
- `per_page` - Items per page

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "conversation_id": 1,
      "sender_id": 1,
      "sender_type": "customer",
      "body": "When will the goods arrive?",
      "is_read": true,
      "sender": {...},
      "created_at": "2024-03-31T12:00:00Z"
    },
    {
      "id": 2,
      "conversation_id": 1,
      "sender_id": 2,
      "sender_type": "admin",
      "body": "Tomorrow by 3 PM",
      "is_read": false,
      "sender": {...},
      "created_at": "2024-03-31T13:00:00Z"
    }
  ]
}
```

### Send Message

**Endpoint:** `POST /orders/{order_id}/messages`  
**Authentication:** Required

**Request:**
```json
{
  "body": "When will my order be delivered?"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Message sent.",
  "data": {
    "id": 3,
    "conversation_id": 1,
    "sender_id": 1,
    "sender_type": "customer",
    "body": "When will my order be delivered?",
    "is_read": false,
    "created_at": "2024-03-31T14:00:00Z"
  }
}
```

---

## 👨‍💼 Admin Routes

### Products Management (Admin)

#### List All Products (with supplier info)

**Endpoint:** `GET /admin/products`  
**Authentication:** Required (admin)

**Query Parameters:**
- `category_id` - Filter by category
- `status` - Filter by status (active/inactive)
- `search` - Search by name or supplier
- `per_page` - Items per page

**Response:** `200 OK` - Includes supplier fields

#### Create Product

**Endpoint:** `POST /admin/products`  
**Authentication:** Required (admin)

**Request:**
```json
{
  "category_id": 1,
  "name": "Bell Peppers",
  "description": "Colorful bell peppers",
  "unit": "kg",
  "min_order_qty": 3,
  "is_certified": true,
  "data_sheet_url": "https://example.com/peppers.pdf",
  "usage_instructions": "Remove seeds and use in cooking",
  "safety_instructions": "Wash thoroughly",
  "manufacturer_info": "Grown in Upper Egypt",
  "expert_tip": "Rich in Vitamin C",
  "expert_name": "Prof. Fatima",
  "expert_title": "Nutritionist",
  "expert_image_url": "https://example.com/fatima.jpg",
  "supplier_name": "Upper Egypt Foods",
  "supplier_code": "UEF-002",
  "status": "active"
}
```

**Response:** `201 Created`

#### Update Product

**Endpoint:** `PUT /admin/products/{product_id}`  
**Authentication:** Required (admin)

**Request:** Same as create (all fields optional)

**Response:** `200 OK`

#### Delete Product

**Endpoint:** `DELETE /admin/products/{product_id}`  
**Authentication:** Required (admin)

**Response:** `200 OK`

#### Add Product Images

**Endpoint:** `POST /admin/products/{product_id}/images`  
**Authentication:** Required (admin)

**Request:**
```json
{
  "images": [
    {
      "url": "https://example.com/peppers-1.jpg",
      "is_primary": true,
      "sort_order": 1
    },
    {
      "url": "https://example.com/peppers-2.jpg",
      "is_primary": false,
      "sort_order": 2
    }
  ]
}
```

**Response:** `201 Created` - Returns updated product with images

#### Sync Product Specs

**Endpoint:** `PUT /admin/products/{product_id}/specs`  
**Authentication:** Required (admin)

**Request:**
```json
{
  "specs": [
    {
      "key": "Origin",
      "value": "Egypt",
      "sort_order": 1
    },
    {
      "key": "Shelf Life",
      "value": "10 days",
      "sort_order": 2
    },
    {
      "key": "Organic",
      "value": "Yes",
      "sort_order": 3
    }
  ]
}
```

**Response:** `200 OK` - Replaces all existing specs with new ones

---

### Orders Management (Admin)

#### List All Orders

**Endpoint:** `GET /admin/orders`  
**Authentication:** Required (admin)

**Query Parameters:**
- `status` - Filter by order status
- `per_page` - Items per page

**Order Statuses:**
- `placed` - Customer placed order
- `quote_pending` - Getting quote from suppliers
- `quote_sent` - Quote sent to customer
- `quote_accepted` - Customer accepted quote
- `quote_rejected` - Customer rejected quote
- `paid` - Payment confirmed
- `preparing` - Preparing order
- `out_for_delivery` - Out for delivery
- `delivered` - Delivered
- `cancelled` - Cancelled
- `returned` - Returned

**Response:** `200 OK` - Returns paginated orders

#### Get Single Order

**Endpoint:** `GET /admin/orders/{order_id}`  
**Authentication:** Required (admin)

**Response:** `200 OK` - Full order with all relationships

#### Send Quote

**Endpoint:** `POST /admin/orders/{order_id}/quotes`  
**Authentication:** Required (admin)

**Request:**
```json
{
  "items": [
    {
      "order_item_id": 1,
      "unit_price": 50.00
    },
    {
      "order_item_id": 2,
      "unit_price": 75.00
    }
  ],
  "delivery_fee": 25.00,
  "notes": "Valid for 48 hours",
  "expires_in_hours": 48
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Quote sent to customer.",
  "data": {
    "id": 1,
    "order_number": "NH-2024-0001",
    "status": "quote_sent",
    "active_quote": {
      "id": 1,
      "order_id": 1,
      "quoted_by": 2,
      "delivery_fee": 25.00,
      "total_amount": 1500.00,
      "notes": "Valid for 48 hours",
      "expires_at": "2024-04-02T12:00:00Z",
      "is_expired": false,
      "status": "pending",
      "items": [
        {
          "id": 1,
          "pricing_quote_id": 1,
          "order_item_id": 1,
          "unit_price": 50.00,
          "total_price": 500.00
        }
      ]
    },
    ...
  }
}
```

#### Update Order Status

**Endpoint:** `PATCH /admin/orders/{order_id}/status`  
**Authentication:** Required (admin)

**Request:**
```json
{
  "status": "preparing",
  "description": "Starting preparation"
}
```

**Valid Status Transitions:**
- `paid` → `preparing` → `out_for_delivery` → `delivered`
- Any status → `cancelled`

**Response:** `200 OK`

#### Get Order Tracking

**Endpoint:** `GET /admin/orders/{order_id}/tracking`  
**Authentication:** Required (admin)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "status": "delivered",
      "title": "تم تسليم طلبك بنجاح",
      "description": null,
      "occurred_at": "2024-03-31T15:00:00Z"
    },
    {
      "id": 2,
      "status": "out_for_delivery",
      "title": "طلبك في الطريق إليك",
      "description": null,
      "occurred_at": "2024-03-31T10:00:00Z"
    }
  ]
}
```

---

### Admin Conversations

#### List All Conversations

**Endpoint:** `GET /admin/conversations`  
**Authentication:** Required (admin)

**Query Parameters:**
- `per_page` - Items per page

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_id": 1,
      "customer_id": 1,
      "last_message_at": "2024-03-31T14:00:00Z",
      "unread_count": 2,
      "customer": {...},
      "order": {...},
      "messages": [...]
    }
  ]
}
```

---

## ❌ Error Responses

### Validation Error

**Status:** `422 Unprocessable Entity`

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

### Authentication Error

**Status:** `401 Unauthorized`

```json
{
  "success": false,
  "message": "Unauthenticated.",
  "errors": {}
}
```

### Authorization Error

**Status:** `403 Forbidden`

```json
{
  "success": false,
  "message": "Unauthorized. You do not have the required role.",
  "errors": {}
}
```

### Not Found Error

**Status:** `404 Not Found`

```json
{
  "success": false,
  "message": "Product not found.",
  "errors": {}
}
```

### Server Error

**Status:** `500 Internal Server Error`

```json
{
  "success": false,
  "message": "Internal server error",
  "errors": {}
}
```

---

## 🧪 Testing the API

### Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ahmed Hassan",
    "email": "ahmed@example.com",
    "password": "SecurePassword123",
    "password_confirmation": "SecurePassword123",
    "phone": "+201001234567"
  }'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ahmed@example.com",
    "password": "SecurePassword123"
  }'

# Get current user (replace TOKEN with actual token)
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer TOKEN"

# List products
curl -X GET http://localhost:8000/api/v1/products

# Place order
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "items": [
      {
        "product_id": 1,
        "quantity": 10
      }
    ],
    "delivery_address": "123 Farm Road",
    "delivery_governorate": "Giza"
  }'
```

### Using Postman

1. Import the included `Nile_Harvest_Auth_Collection.postman_collection.json`
2. Set variables for base URL and auth token
3. Test each endpoint

---

## 📊 Database Schema

### Users
- `id` (primary key)
- `name`, `email`, `phone` (unique)
- `password` (hashed)
- `role` (enum: admin, customer)
- `status` (enum: active, suspended)
- `customer_type` (enum: farmer, trader - for customers)
- `governorate`, `address`
- Timestamps and soft deletes

### Products
- `id`, `category_id` (foreign key)
- `name`, `description`, `unit`, `min_order_qty`
- `is_certified` (boolean)
- Content tabs: `data_sheet_url`, `usage_instructions`, `safety_instructions`, `manufacturer_info`
- Expert tip: `expert_tip`, `expert_name`, `expert_title`, `expert_image_url`
- Supplier info (hidden): `supplier_name`, `supplier_code`
- `status` (enum: active, inactive)

### Products Images
- `id`, `product_id` (foreign key)
- `url`, `is_primary`, `sort_order`

### Product Specs
- `id`, `product_id` (foreign key)
- `key`, `value`, `sort_order`

### Orders
- `id`, `customer_id` (foreign key)
- `order_number` (unique, auto-generated)
- `status` (enum with multiple values)
- `total_amount`, `delivery_fee`
- `payment_method`, `payment_status`
- `delivery_address`, `delivery_governorate`
- Timestamps and soft deletes

### Order Items
- `id`, `order_id`, `product_id` (foreign keys)
- `quantity`, `unit_price`, `total_price`

### Pricing Quotes
- `id`, `order_id`, `quoted_by` (foreign keys)
- `delivery_fee`, `total_amount`
- `notes`, `expires_at`
- `status` (enum: pending, accepted, rejected, expired)

### Pricing Quote Items
- `id`, `pricing_quote_id`, `order_item_id` (foreign keys)
- `unit_price`, `total_price`

### Order Tracking
- `id`, `order_id` (foreign key)
- `status`, `title` (Arabic), `description`
- `occurred_at` (timestamp)

### Conversations
- `id`, `order_id`, `customer_id` (foreign keys)
- `last_message_at`
- Unique constraint on `order_id`

### Messages
- `id`, `conversation_id`, `sender_id` (foreign keys)
- `sender_type` (enum: admin, customer)
- `body`, `is_read`

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.3+
- Laravel 11
- MySQL 8.0+
- Composer
- Redis (optional, for caching)

### Installation

```bash
# Clone the repository
cd agricultural-store

# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Configure environment
cp .env.example .env
# Edit .env with your database credentials

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Generate Sanctum tokens table (if needed)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Start the development server
php artisan serve

# The API will be available at http://localhost:8000/api/v1
```

### Test Admin User
Email: `admin@nileharvest.com`  
Password: `password`  
Role: `admin`

### Test Customer Users
Email: `ahmed@example.com` / Password: `password`  
Email: `fatima@example.com` / Password: `password`  
Email: `ibrahim@example.com` / Password: `password`

---

## 📝 Notes

- All timestamps are in UTC
- Numeric values with money (prices, fees) use 2 decimal places
- Supply codes and supplier names are admin-only fields
- Customer API responses automatically hide supplier information
- Order status transitions follow a logical flow
- Soft deletes are used for users, products, and orders
- All endpoints except auth and public products require authentication
- Admin endpoints require `admin` role
- Customer endpoints require `customer` role

---

## 📚 Additional Resources

- [API_USAGE_EXAMPLES.md](API_USAGE_EXAMPLES.md) - Curl and HTTP examples
- [NILE_HARVEST_SETUP.md](NILE_HARVEST_SETUP.md) - Setup and deployment guide
- [SETUP_CHECKLIST.md](SETUP_CHECKLIST.md) - Installation checklist
