# Nile Harvest API - Business Logic & Workflow Guide

## 🎯 Business Model Overview

Nile Harvest is a **single-vendor agricultural marketplace** where:
- **One vendor (Admin)** owns and manages the store
- **Multiple customers** (farmers & traders) can browse and order
- **Products are catalog-only** (no prices or inventory in system)
- **Dynamic pricing** through quote system for each order
- **Full communication** between customer and admin per order

---

## 📊 Core Business Workflows

### Workflow 1: Product Management

#### Admin Creates Product
```
Admin fills product form
    ↓
System validates:
  - Category exists
  - Images URLs are valid
  - Minimum data provided
    ↓
Product created with:
  - Basic info (name, description)
  - Unit & minimum order qty
  - Content tabs (data sheets, instructions)
  - Expert tip & credentials
  - Supplier info (hidden from customers)
    ↓
Product ready for browsing (status = active)
```

**Key Fields:**
- `name` - Product name
- `unit` - kg, liters, boxes, etc.
- `min_order_qty` - Minimum units customer can order
- `is_certified` - Shows "منتج معتمد" badge
- `supplier_name` / `supplier_code` - HIDDEN from customers

**Admin Adds Specifications:**
- Dynamic key-value specs (e.g., Origin: Egypt)
- Sort order for display
- Can be updated anytime

**Admin Adds Images:**
- Multiple images per product
- One can be marked as primary/featured
- Sort order for gallery display

---

### Workflow 2: Customer Registration & Login

#### Customer Registers
```
Customer enters:
  - Basic info (name, email, password)
  - Contact (phone)
  - Type (farmer or trader)
  - Location (governorate, address)
    ↓
System validates:
  - Email format & uniqueness
  - Password strength (min 8 chars)
  - Phone uniqueness
  - Required fields present
    ↓
Account created with:
  - role = 'customer'
  - status = 'active'
  - API token generated
    ↓
Customer receives token for API auth
```

**Login:**
```
Customer enters email & password
    ↓
System validates credentials
    ↓
If valid:
  - Return user info
  - Generate new API token
    ↓
If invalid:
  - Return error message
```

---

### Workflow 3: Browsing & Ordering

#### Customer Browses Products
```
Customer calls GET /products
    ↓
System returns:
  - All active products
  - NO supplier information
  - NO prices (not stored)
  - Product images & specs
  - Expert tips & credentials
  - Category info
    ↓
Customer can:
  - Search by name/description
  - Filter by category
  - View product details
```

**Product Card Shows:**
- Name, description
- Unit & minimum order qty
- "منتج معتمد" badge if certified
- Primary product image
- Expert tip (name, title)
- Content tabs (data sheets, instructions)

**NOT shown:**
- Supplier name/code
- Any pricing
- Any stock levels

#### Customer Places Order
```
Customer calls POST /orders with:
  - Item list (product_id + quantity)
  - Delivery address
  - Delivery governorate
  - Payment method (default: COD)
    ↓
System validates:
  - Products exist and are active
  - Each quantity ≥ product.min_order_qty
  - Delivery info provided
    ↓
Order created with:
  - order_number (auto-generated: NH-2024-0001)
  - status = 'placed'
  - customer_id
    ↓
Auto-created:
  ✅ Conversation for chat
  ✅ First tracking entry (status="placed")
    ↓
Admin notified via job:
  ✅ NotifyAdminOfNewOrder
```

**Order Validation Rules:**
- Product must be active
- Product must exist
- Quantity must be ≥ min_order_qty
- Delivery details required

---

### Workflow 4: Quote Generation & Negotiation

#### Admin Reviews Order
```
Admin calls GET /admin/orders/1
    ↓
Shows:
  - Customer info
  - Items requested
  - Delivery address
  - Current status
    ↓
Admin can:
  - View item details
  - See conversation
  - Send quote
```

#### Admin Creates & Sends Quote
```
Admin calls POST /admin/orders/1/quotes with:
  - Item prices (unit_price for each item)
  - Delivery fee
  - Notes (e.g., "Valid for 48 hours")
  - Expiration (hours from now)
    ↓
System calculates:
  - Line total = unit_price × quantity
  - Total = sum of line items + delivery_fee
  - Expiration timestamp
    ↓
Quote created with:
  - status = 'pending'
  - expires_at = now + expiration_hours
    ↓
Customer notified via job:
  ✅ NotifyCustomerOfQuote
    ↓
Order status updated:
  - status = 'quote_sent'
  - Tracking entry created
```

**Quote Contents:**
```
Quote {
  items: [
    {
      product_id: 1,
      quantity: 10,
      unit_price: 45.00,
      total_price: 450.00
    }
  ],
  delivery_fee: 30.00,
  total_amount: 480.00,
  expires_at: "2024-04-02T12:00:00Z",
  notes: "Valid for 48 hours"
}
```

#### Customer Reviews & Responds
```
Customer calls GET /orders/1/messages
    ↓
Sees:
  - Quote details
  - Delivery information
  - Any notes from admin
    ↓
Customer can:
  - Send messages asking questions
  - Ask for price adjustments
  - Request different quantities
```

```
Customer calls POST /orders/1/quotes/1/accept
    ↓
System updates:
  - Order status = 'quote_accepted'
  - Order total_amount = quote.total_amount
  - Order delivery_fee = quote.delivery_fee
  - Quote status = 'accepted'
  - Order items updated with prices
    ↓
Creates tracking entry:
  - status = 'quote_accepted'
  - title = "تم قبول عرض السعر"
    ↓
OR customer calls POST /orders/1/quotes/1/reject
    ↓
System updates:
  - Order status = 'quote_pending'
  - Quote status = 'rejected'
  - Tracking entry created
    ↓
Admin can send new quote
```

---

### Workflow 5: Chat & Communication

#### Customer-Admin Conversation

**Automatic Creation:**
```
When customer places order:
    ↓
System creates conversation:
  - conversation_id
  - order_id
  - customer_id
  - last_message_at = null
```

**Customer Sends Message:**
```
Customer calls POST /orders/1/messages with:
  - body: "When will delivery be?"
    ↓
System creates message:
  - sender_id = customer.id
  - sender_type = 'customer'
  - is_read = false
  - created_at = now
    ↓
Updates conversation:
  - last_message_at = now
    ↓
Admin sees unread message
```

**Admin Sends Message:**
```
Admin calls POST /orders/1/messages with:
  - body: "Delivery tomorrow at 9 AM"
    ↓
System creates message:
  - sender_id = admin.id
  - sender_type = 'admin'
  - is_read = false
  - created_at = now
    ↓
Customer can retrieve and read
```

**Getting Conversation:**
```
Either side calls GET /orders/1/messages
    ↓
System returns:
  - All messages (ordered by created_at)
  - Sender info for each
  - Read status
    ↓
Auto-marks opposite sender's messages as read:
  - If customer fetches: marks admin messages as read
  - If admin fetches: marks customer messages as read
```

---

### Workflow 6: Order Fulfillment & Tracking

#### Payment & Status Updates

**After Quote Accepted:**
```
Customer makes payment (outside system for COD)
    ↓
Admin marks payment received:
  PATCH /admin/orders/1/status with:
    - status = 'paid'
    ↓
System updates:
  - Order status = 'paid'
  - Tracking entry created with title "تم تأكيد الدفع"
```

**Preparing Order:**
```
Admin prepares items:
  PATCH /admin/orders/1/status with:
    - status = 'preparing'
    - description = 'Packing items'
    ↓
Creates tracking entry
```

**Out for Delivery:**
```
Admin ships order:
  PATCH /admin/orders/1/status with:
    - status = 'out_for_delivery'
    - description = 'With logistics provider'
    ↓
Customer can track delivery
```

**Delivered:**
```
Order arrives:
  PATCH /admin/orders/1/status with:
    - status = 'delivered'
    ↓
Order complete
```

**Order Status Transitions:**
```
placed
  ↓
quote_pending → quote_sent → quote_accepted → quote_rejected
                                  ↓
                                 paid
                                  ↓
                            preparing
                                  ↓
                          out_for_delivery
                                  ↓
                             delivered ✅
```

**Any Status → Cancelled (on request)**

#### Order Tracking History

**Customer View:**
```
Customer calls GET /orders/1
    ↓
Response includes tracking:
[
  {
    status: 'delivered',
    title: 'تم تسليم طلبك بنجاح',
    description: null,
    occurred_at: '2024-03-31T15:00:00Z'
  },
  {
    status: 'out_for_delivery',
    title: 'طلبك في الطريق إليك',
    description: 'With DHL',
    occurred_at: '2024-03-31T10:00:00Z'
  },
  ...
]
```

**Arabic Status Titles (Automatic):**
```
placed → "تم استلام طلبك"
quote_pending → "جاري تجهيز عرض السعر"
quote_sent → "تم إرسال عرض السعر"
quote_accepted → "تم قبول عرض السعر"
paid → "تم تأكيد الدفع"
preparing → "جاري تجهيز طلبك"
out_for_delivery → "طلبك في الطريق إليك"
delivered → "تم تسليم طلبك بنجاح"
cancelled → "تم إلغاء الطلب"
returned → "تم إرجاع الطلب"
```

---

## 💡 Business Logic Rules

### Product Rules
- ✅ Products are catalog only (no prices stored)
- ✅ Each product has minimum order quantity
- ✅ Supplier info is NEVER shown to customers
- ✅ Is_certified flag triggers badge display
- ✅ Can have multiple images and specs
- ✅ Can be active or inactive

### Order Rules
- ✅ Customer must order at least min_order_qty of each product
- ✅ Each order gets unique order_number (NH-2024-XXXX)
- ✅ Each order auto-creates conversation & tracking
- ✅ Only one quote can be "pending" at a time
- ✅ Prices only set after quote acceptance
- ✅ Order can only progress through defined states

### Quote Rules
- ✅ Admin provides pricing for each order item
- ✅ Quotes have expiration (default 48 hours)
- ✅ Customer can only accept/reject pending quotes
- ✅ Total = sum(unit_price × qty) + delivery_fee
- ✅ Once accepted, order items get prices filled in

### Chat Rules
- ✅ One conversation per order
- ✅ Either party can send messages anytime
- ✅ Messages marked as read when retrieved
- ✅ Full history maintained
- ✅ Unread counts tracked for admin

### Authorization Rules
- ✅ Customers can only see their own orders
- ✅ Customers cannot modify other customer data
- ✅ 

 can only accept/reject quotes on own orders
- ✅ Admin can see all orders, customers, conversations
- ✅ Admin can modify any order/product

---

## 🔄 Common Integration Points

### External Systems
- **Payment Gateway** - Process payments (outside API)
- **Notification Service** - Email/SMS confirmations
- **Logistics API** - Track shipments
- **Analytics** - Order trends & metrics
- **Accounting** - Invoice generation

### Job Queue Integration
```
- NotifyAdminOfNewOrder
- NotifyCustomerOfQuote
- SendOrderConfirmation
- SendDeliveryNotification
```

---

## 📈 Data Relationships Summary

```
User (admin/customer)
  ├─ Orders (many) → OrderItems → Products
  ├─ Conversations → Messages
  ├─ Created Quotes (admin only)
  └─ PricingQuotes.quoted_by

Order
  ├─ Customer → User
  ├─ Items → OrderItems → Products
  ├─ Quotes → PricingQuotes → Items
  ├─ Tracking → OrderTracking
  └─ Conversation → Messages

Product
  ├─ Category
  ├─ Images → ProductImages
  ├─ Specs → ProductSpecs
  └─ OrderItems → Orders

PricingQuote
  ├─ Order
  ├─ QuotedBy → User (admin)
  └─ Items → PricingQuoteItems

Conversation
  ├─ Order
  ├─ Customer → User
  └─ Messages
```

---

## 🎯 Key Metrics to Track

**For Customers:**
- Total orders placed
- Total spent
- Average order value
- Repeat purchase rate

**For Admin:**
- New orders today
- Pending quotes (need action)
- Orders by status
- Average quote acceptance rate
- Most popular products
- Customer satisfaction (via reviews future feature)

**For Business:**
- Revenue by date
- Orders by category
- Average delivery time
- Quote negotiation rate

---

This guide should help developers and product managers understand the complete business model and workflow implementation of the Nile Harvest API.
