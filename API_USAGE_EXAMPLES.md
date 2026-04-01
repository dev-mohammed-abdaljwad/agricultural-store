# Nile Harvest API - Complete Usage Examples

## Quick Start

### 1. Register as Customer
```bash
curl -X POST http://localhost:8000/api/v1/auth/register/customer \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ahmed Hassan",
    "email": "ahmed@example.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123",
    "phone": "+201001234567"
  }'
```

**Response**:
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
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    },
    "token": "1|lGvEWwLwf7RNMVXE..."
  }
}
```

### 2. Register as Vendor
```bash
curl -X POST http://localhost:8000/api/v1/auth/register/vendor \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Ahmad Al-Farmer",
    "email": "vendor@farm.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123",
    "phone": "+201234567890",
    "company_name": "Al-Nile Agriculture",
    "commercial_register": "REG123456789",
    "governorate": "Giza",
    "address": "123 Agricultural Street, Giza, Egypt",
    "primary_category": "Vegetables"
  }'
```

### 3. Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ahmed@example.com",
    "password": "SecurePass123"
  }'
```

**Store the returned token for subsequent requests!**

---

## Admin Operations

### Get All Users (Super Admin Only)
```bash
curl -X GET "http://localhost:8000/api/v1/admin/users" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Get Users by Role
```bash
curl -X GET "http://localhost:8000/api/v1/admin/users/role/vendor" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Get Single User
```bash
curl -X GET "http://localhost:8000/api/v1/admin/users/2" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Suspend User
```bash
curl -X PATCH "http://localhost:8000/api/v1/admin/users/2/suspend" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Activate User
```bash
curl -X PATCH "http://localhost:8000/api/v1/admin/users/2/activate" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Get Pending Vendors (Awaiting Approval)
```bash
curl -X GET "http://localhost:8000/api/v1/admin/vendors/pending" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Get Approved Vendors
```bash
curl -X GET "http://localhost:8000/api/v1/admin/vendors/approved" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN"
```

### Approve Vendor
```bash
curl -X PATCH "http://localhost:8000/api/v1/admin/vendors/2/status" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN" \
  -d '{
    "status": "approved"
  }'
```

### Suspend Vendor
```bash
curl -X PATCH "http://localhost:8000/api/v1/admin/vendors/2/status" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|YOUR_ADMIN_TOKEN" \
  -d '{
    "status": "suspended"
  }'
```

---

## Vendor Operations (Products)

### Get My Products
```bash
curl -X GET "http://localhost:8000/api/v1/vendor/products" \
  -H "Authorization: Bearer 1|VENDOR_TOKEN"
```

### Create Product
```bash
curl -X POST "http://localhost:8000/api/v1/vendor/products" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|VENDOR_TOKEN" \
  -d '{
    "category_id": 1,
    "name": "Fresh Tomatoes - Grade A",
    "description": "Red, ripe organic tomatoes from local farm. No pesticides.",
    "price": 45.50,
    "stock": 500,
    "unit": "kg"
  }'
```

**Response**:
```json
{
  "success": true,
  "message": "Product created successfully.",
  "data": {
    "id": 1,
    "vendor_id": 3,
    "category_id": 1,
    "name": "Fresh Tomatoes - Grade A",
    "description": "Red, ripe organic tomatoes from local farm. No pesticides.",
    "price": 45.50,
    "stock": 500,
    "unit": "kg",
    "status": "active",
    "created_at": "2024-01-15T11:00:00Z",
    "updated_at": "2024-01-15T11:00:00Z"
  }
}
```

### Get Product Details
```bash
curl -X GET "http://localhost:8000/api/v1/vendor/products/1" \
  -H "Authorization: Bearer 1|VENDOR_TOKEN"
```

### Update Product
```bash
curl -X PATCH "http://localhost:8000/api/v1/vendor/products/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|VENDOR_TOKEN" \
  -d '{
    "price": 50.00,
    "stock": 450,
    "status": "active"
  }'
```

### Delete Product
```bash
curl -X DELETE "http://localhost:8000/api/v1/vendor/products/1" \
  -H "Authorization: Bearer 1|VENDOR_TOKEN"
```

---

## Customer Operations

### Get Current User Info
```bash
curl -X GET "http://localhost:8000/api/v1/auth/me" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### View Shopping Cart
```bash
curl -X GET "http://localhost:8000/api/v1/customer/cart" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### Add Item to Cart
```bash
curl -X POST "http://localhost:8000/api/v1/customer/cart/add" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN" \
  -d '{
    "product_id": 5,
    "quantity": 10
  }'
```

**Response**:
```json
{
  "success": true,
  "message": "Item added to cart successfully.",
  "data": {
    "items": {
      "5": {
        "product_id": 5,
        "quantity": 10
      }
    }
  }
}
```

### Update Cart Item Quantity
```bash
curl -X PATCH "http://localhost:8000/api/v1/customer/cart/update" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN" \
  -d '{
    "product_id": 5,
    "quantity": 15
  }'
```

### Remove Item from Cart
```bash
curl -X DELETE "http://localhost:8000/api/v1/customer/cart/5" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### Clear Entire Cart
```bash
curl -X DELETE "http://localhost:8000/api/v1/customer/cart" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### Create Order
```bash
curl -X POST "http://localhost:8000/api/v1/customer/orders" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN" \
  -d '{
    "vendor_id": 3,
    "items": [
      {
        "product_id": 5,
        "quantity": 10
      },
      {
        "product_id": 7,
        "quantity": 5
      }
    ],
    "payment_method": "cod",
    "notes": "Please deliver in the morning"
  }'
```

**Response**:
```json
{
  "success": true,
  "message": "Order created successfully.",
  "data": {
    "id": 1,
    "customer_id": 1,
    "vendor_id": 3,
    "status": "pending",
    "subtotal": 750.00,
    "delivery_fee": 0.00,
    "commission_rate": 0.00,
    "payment_method": "cod",
    "notes": "Please deliver in the morning",
    "items": [
      {
        "id": 1,
        "product_id": 5,
        "quantity": 10,
        "unit_price": 45.50,
        "total": 455.00
      },
      {
        "id": 2,
        "product_id": 7,
        "quantity": 5,
        "unit_price": 59.00,
        "total": 295.00
      }
    ],
    "created_at": "2024-01-15T12:00:00Z"
  }
}
```

### Get My Orders
```bash
curl -X GET "http://localhost:8000/api/v1/customer/orders" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### Get Order Details
```bash
curl -X GET "http://localhost:8000/api/v1/customer/orders/1" \
  -H "Authorization: Bearer 1|CUSTOMER_TOKEN"
```

### Logout
```bash
curl -X POST "http://localhost:8000/api/v1/auth/logout" \
  -H "Authorization: Bearer 1|YOUR_TOKEN"
```

---

## Error Response Examples

### Validation Error (400)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Unauthorized. You do not have the required role."
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Product not found."
}
```

---

## Tips for Testing

1. **Use Postman**: Import the curl commands above into Postman for easier testing
2. **Store Tokens**: Save returned tokens in environment variables
3. **Test Workflows**: 
   - Register → Login → Create Product → View Orders
   - Admin approval of vendors before they can sell
4. **Pagination**: Add `?page=2` to list endpoints
5. **Real Database**: Use MySQL or SQLite configured in `.env`

## Common Issues

**Issue**: "Vendor account is not approved yet"
**Solution**: Admin must approve vendor first using the approve endpoint

**Issue**: "Insufficient stock"
**Solution**: Product has less stock than requested quantity

**Issue**: "Product not found"
**Solution**: Product belongs to another vendor or is deleted (soft delete)

**Issue**: Invalid token
**Solution**: Token may have expired (check config/sanctum.php) or is malformed
