# Product Images API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
Most endpoints require Bearer token authentication:
```
Authorization: Bearer {your_sanctum_token}
```

---

## Public Endpoints (View Only)

### 1. Get All Product Images
Retrieve all images for a specific product.

**Endpoint:**
```
GET /products/{product_id}/images
```

**Parameters:**
- `product_id` (required): Product ID

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "url": "/storage/products/product-image-1.jpg",
      "is_primary": true,
      "sort_order": 0,
      "created_at": "2026-04-01T10:30:00Z"
    },
    {
      "id": 2,
      "url": "/storage/products/product-image-2.jpg",
      "is_primary": false,
      "sort_order": 1,
      "created_at": "2026-04-01T10:31:00Z"
    }
  ],
  "count": 2
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/products/1/images" \
  -H "Accept: application/json"
```

---

### 2. Get Product Primary Image
Retrieve the primary (main) image for a product.

**Endpoint:**
```
GET /products/{product_id}/images/primary
```

**Parameters:**
- `product_id` (required): Product ID

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "url": "/storage/products/product-image-1.jpg",
    "is_primary": true,
    "sort_order": 0
  }
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "No primary image found"
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/products/1/images/primary" \
  -H "Accept: application/json"
```

---

### 3. Get Specific Product Image
Retrieve details of a specific image.

**Endpoint:**
```
GET /products/{product_id}/images/{image_id}
```

**Parameters:**
- `product_id` (required): Product ID
- `image_id` (required): Image ID

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "product_id": 1,
    "url": "/storage/products/product-image-1.jpg",
    "is_primary": true,
    "sort_order": 0,
    "created_at": "2026-04-01T10:30:00Z"
  }
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/products/1/images/1" \
  -H "Accept: application/json"
```

---

## Admin Endpoints (Protected - Requires Auth)

### 4. Upload Product Image
Upload a new image for a product.

**Endpoint:**
```
POST /admin/products/{product_id}/images
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Parameters:**
- `image` (required): Image file (JPEG, PNG, GIF, WebP - max 5MB)
- `is_primary` (optional): Boolean - Set as primary image (default: false)
- `sort_order` (optional): Integer - Image order (default: auto)

**Response (201):**
```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "id": 5,
    "url": "/storage/products/product-image-5.jpg",
    "is_primary": false,
    "sort_order": 4
  }
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/admin/products/1/images" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "image=@/path/to/image.jpg" \
  -F "is_primary=false"
```

**PHP Example (Using Guzzle):**
```php
$client = new \GuzzleHttp\Client();

$response = $client->post('http://localhost:8000/api/v1/admin/products/1/images', [
    'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ],
    'multipart' => [
        [
            'name' => 'image',
            'contents' => fopen('/path/to/image.jpg', 'r'),
        ],
        [
            'name' => 'is_primary',
            'contents' => 'true',
        ],
    ],
]);

$data = json_decode($response->getBody(), true);
```

**JavaScript/Fetch Example:**
```javascript
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('is_primary', true);

fetch('http://localhost:8000/api/v1/admin/products/1/images', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json',
  },
  body: formData
})
.then(res => res.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

---

### 5. Update Product Image
Update image properties (mark as primary, change sort order).

**Endpoint:**
```
PUT /admin/products/{product_id}/images/{image_id}
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "is_primary": true,
  "sort_order": 2
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Image updated successfully",
  "data": {
    "id": 1,
    "url": "/storage/products/product-image-1.jpg",
    "is_primary": true,
    "sort_order": 2
  }
}
```

**cURL Example:**
```bash
curl -X PUT "http://localhost:8000/api/v1/admin/products/1/images/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "is_primary": true,
    "sort_order": 0
  }'
```

**JavaScript/Fetch Example:**
```javascript
fetch('http://localhost:8000/api/v1/admin/products/1/images/1', {
  method: 'PUT',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  body: JSON.stringify({
    is_primary: true,
    sort_order: 0
  })
})
.then(res => res.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

---

### 6. Delete Product Image
Remove an image from a product.

**Endpoint:**
```
DELETE /admin/products/{product_id}/images/{image_id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Image deleted successfully"
}
```

**Response (404):**
```json
{
  "success": false,
  "message": "Image not found for this product"
}
```

**cURL Example:**
```bash
curl -X DELETE "http://localhost:8000/api/v1/admin/products/1/images/5" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**JavaScript/Fetch Example:**
```javascript
fetch('http://localhost:8000/api/v1/admin/products/1/images/5', {
  method: 'DELETE',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json',
  }
})
.then(res => res.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

---

### 7. Reorder Product Images
Change the display order of multiple images.

**Endpoint:**
```
POST /admin/products/{product_id}/images/reorder
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "images": [
    {
      "id": 5,
      "sort_order": 0
    },
    {
      "id": 1,
      "sort_order": 1
    },
    {
      "id": 2,
      "sort_order": 2
    }
  ]
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Images reordered successfully"
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/admin/products/1/images/reorder" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "images": [
      {"id": 5, "sort_order": 0},
      {"id": 1, "sort_order": 1},
      {"id": 2, "sort_order": 2}
    ]
  }'
```

---

## Error Responses

### 400 - Bad Request
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "image": ["The image field is required."]
  }
}
```

### 401 - Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 - Forbidden (Not Admin)
```json
{
  "message": "This action is unauthorized."
}
```

### 404 - Not Found
```json
{
  "success": false,
  "message": "Image not found for this product"
}
```

### 500 - Server Error
```json
{
  "success": false,
  "message": "Error uploading image: [error details]"
}
```

---

## Quick Start Examples

### Example 1: Get All Images for a Product
```javascript
async function getProductImages(productId) {
  const response = await fetch(`/api/v1/products/${productId}/images`);
  const data = await response.json();
  return data.data;
}
```

### Example 2: Upload Image (with Authentication)
```javascript
async function uploadProductImage(productId, file, isPrimary = false, token) {
  const formData = new FormData();
  formData.append('image', file);
  formData.append('is_primary', isPrimary);

  const response = await fetch(`/api/v1/admin/products/${productId}/images`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
    body: formData
  });

  return await response.json();
}
```

### Example 3: Set Image as Primary
```javascript
async function setImageAsPrimary(productId, imageId, token) {
  const response = await fetch(
    `/api/v1/admin/products/${productId}/images/${imageId}`,
    {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ is_primary: true })
    }
  );

  return await response.json();
}
```

### Example 4: Delete Image
```javascript
async function deleteProductImage(productId, imageId, token) {
  const response = await fetch(
    `/api/v1/admin/products/${productId}/images/${imageId}`,
    {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      }
    }
  );

  return await response.json();
}
```

---

## Best Practices

1. **Always validate image size** before uploading (max 5MB)
2. **Use appropriate image formats**: JPEG, PNG, GIF, or WebP
3. **Set a primary image** for each product for better UX
4. **Organize images** with proper sort_order values
5. **Handle errors gracefully** and show user-friendly messages
6. **Cache image URLs** to reduce API calls
7. **Use pagination** if loading many products with images

---

## Status Codes Reference

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 500 | Server Error |

